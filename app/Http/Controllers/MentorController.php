<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsLogbookEntries;
use App\Http\Controllers\Concerns\HasSharedLayoutData;
use App\Models\BimbinganRequest;
use App\Models\Logbook;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    use HasSharedLayoutData, FormatsLogbookEntries;

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            abort_unless($request->user()->role === 'mentor', 403);

            return $next($request);
        });
    }

    public function antrian(Request $request)
    {
        $mentor = $request->user();
        $studentIds = $mentor->students()->pluck('users.id');

        $mentorQueue = Logbook::whereIn('user_id', $studentIds)
            ->where('status', 'pending')
            ->with(['user', 'images', 'documents'])
            ->orderBy('activity_date', 'asc') // FIFO
            ->get()
            ->map(fn (Logbook $log) => $this->formatQueueEntry($log))
            ->values();

        return view('mentor.antrian', array_merge($this->sharedLayoutData($mentor), [
            'totalMahasiswa' => $studentIds->count(),
            'totalPending' => $mentorQueue->count(),
            'direviewHariIni' => Logbook::whereIn('user_id', $studentIds)
                ->whereIn('status', ['approved', 'rejected'])
                ->whereDate('updated_at', today())
                ->count(),
            'mentorQueue' => $mentorQueue,
        ]));
    }

    public function setujui(Request $request, Logbook $logbook): RedirectResponse
    {
        $this->authorizeOwnership($request, $logbook);

        $validated = $request->validate([
            'mentor_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $logbook->update([
            'status' => 'approved',
            'mentor_note' => $validated['mentor_note'] ?? null,
        ]);

        Notification::create([
            'user_id' => $logbook->user_id,
            'sender_name' => $request->user()->name,
            'title' => "Logbook \"{$logbook->title}\" Anda disetujui oleh {$request->user()->name}.",
        ]);

        return back()->with('status', 'Logbook berhasil disetujui.');
    }

    public function tolak(Request $request, Logbook $logbook): RedirectResponse
    {
        $this->authorizeOwnership($request, $logbook);

        $validated = $request->validate([
            'mentor_note' => ['required', 'string', 'max:1000'],
        ], [
            'mentor_note.required' => 'Catatan mentor diperlukan saat menolak logbook.',
        ]);

        $logbook->update([
            'status' => 'rejected',
            'mentor_note' => $validated['mentor_note'],
        ]);

        Notification::create([
            'user_id' => $logbook->user_id,
            'sender_name' => $request->user()->name,
            'title' => "Logbook \"{$logbook->title}\" Anda ditolak oleh {$request->user()->name}.",
        ]);

        return back()->with('status', 'Logbook ditolak.');
    }

    public function mahasiswa(Request $request)
    {
        $mentor = $request->user();

        $students = $mentor->students()
            ->withCount('logbooks as total_logbook')
            ->withCount(['logbooks as pending_count' => fn ($q) => $q->where('status', 'pending')])
            ->withMax('logbooks', 'activity_date')
            ->orderBy('name')
            ->get()
            ->map(fn (User $student) => [
                'id' => $student->id,
                'name' => $student->name,
                'total_logbook' => $student->total_logbook,
                'pending_count' => $student->pending_count,
                'last_entry' => optional($student->logbooks_max_activity_date)
                    ->translatedFormat('d F Y') ?? '-',
            ]);

        return view('mentor.mahasiswa', array_merge($this->sharedLayoutData($mentor), [
            'students' => $students,
        ]));
    }

    public function riwayat(Request $request, User $student)
    {
        $mentor = $request->user();

        abort_unless(
            $mentor->students()->where('users.id', $student->id)->exists(),
            403,
            'Mahasiswa ini bukan bimbingan Anda.'
        );

        $logbookHistory = $student->logbooks()
            ->withCount(['images', 'documents'])
            ->with(['images', 'documents'])
            ->orderBy('activity_date', 'desc')
            ->get()
            ->map(fn (Logbook $log) => $this->formatLogEntry($log, $student));

        return view('mentor.riwayat', array_merge($this->sharedLayoutData($mentor), [
            'selectedStudent' => [
                'id' => $student->id,
                'name' => $student->name,
                'total_logbook' => $logbookHistory->count(),
            ],
            'logbookHistory' => $logbookHistory,
        ]));
    }

    private function formatQueueEntry(Logbook $log): array
    {
        return [
            'id' => $log->id,
            'student' => $log->user->name,
            'title' => $log->title,
            'date' => $log->activity_date->translatedFormat('l, d F Y'),
            'preview' => Str::limit($log->activity_detail, 60),
            'detail' => $log->activity_detail,
            'challenge' => $log->challenges ?: 'Belum ada.',
            'is_holiday' => (bool) $log->is_holiday,
            'holiday_name' => $log->holiday_name,
            'images' => $log->images->map(fn ($img) => [
                'name' => $img->image_name,
                'url' => Storage::disk('public')->url($img->image_path),
            ])->values(),
            'documents' => $log->documents->map(fn ($doc) => [
                'name' => $doc->document_name,
                'url' => Storage::disk('public')->url($doc->document_path),
            ])->values(),
        ];
    }

    private function authorizeOwnership(Request $request, Logbook $logbook): void
    {
        $mentor = $request->user();

        abort_unless(
            $mentor->students()->where('users.id', $logbook->user_id)->exists(),
            403,
            'Logbook ini bukan milik mahasiswa bimbingan Anda.'
        );
    }

    public function pengajuan(Request $request)
    {
        $mentor = $request->user();

        $pending = $mentor->mentorRequests()
            ->where('status', 'pending')
            ->with('student')
            ->latest()
            ->get();

        return view('mentor.pengajuan', array_merge($this->sharedLayoutData($mentor), [
            'pengajuanList' => $pending,
        ]));
    }

    private function authorizeRequestOwnership(Request $request, BimbinganRequest $bimbinganRequest): void
    {
        abort_unless($bimbinganRequest->mentor_id === $request->user()->id, 403);
    }

    public function setujuiPengajuan(Request $request, BimbinganRequest $bimbinganRequest): RedirectResponse
    {
        $this->authorizeRequestOwnership($request, $bimbinganRequest);

        $bimbinganRequest->update(['status' => 'approved', 'mentor_note' => null]);

        Notification::create([
            'user_id' => $bimbinganRequest->student_id,
            'sender_name' => $request->user()->name,
            'title' => "Pengajuan bimbingan Anda disetujui oleh {$request->user()->name}.",
        ]);

        return back()->with('status', 'Pengajuan bimbingan disetujui.');
    }

    public function tolakPengajuan(Request $request, BimbinganRequest $bimbinganRequest): RedirectResponse
    {
        $this->authorizeRequestOwnership($request, $bimbinganRequest);

        $validated = $request->validate([
            'mentor_note' => ['required', 'string', 'max:500'],
        ], [
            'mentor_note.required' => 'Alasan penolakan diperlukan.',
        ]);

        $bimbinganRequest->update(['status' => 'rejected', 'mentor_note' => $validated['mentor_note']]);

        Notification::create([
            'user_id' => $bimbinganRequest->student_id,
            'sender_name' => $request->user()->name,
            'title' => "Pengajuan bimbingan Anda ditolak oleh {$request->user()->name}.",
        ]);

        return back()->with('status', 'Pengajuan bimbingan ditolak.');
    }


}
