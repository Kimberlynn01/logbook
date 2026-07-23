<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsLogbookEntries;
use App\Http\Controllers\Concerns\HasSharedLayoutData;
use App\Models\Logbook;
use App\Models\LogbookDocument;
use App\Models\LogbookImage;
use App\Models\Notification;
use App\Models\User;
use App\Services\NagerDateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use HasSharedLayoutData, FormatsLogbookEntries;

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            abort_if($request->user()->role === 'mentor', 403);

            return $next($request);
        });
    }

    public function dashboard(Request $request)
    {
        $student = $request->user();
        $currentMentor = $student->mentor()->first();

        $recentLogs = $student->logbooks()
            ->withCount(['images', 'documents'])
            ->orderBy('activity_date', 'desc')
            ->take(5)
            ->get()
            ->map(fn (Logbook $log) => $this->formatLogEntry($log, $student));

        $rejectedLogs = $student->logbooks()
            ->where('status', 'rejected')
            ->with(['images', 'documents'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn (Logbook $log) => [
                'id' => $log->id,
                'title' => $log->title,
                'detail' => $log->activity_detail,
                'challenges' => $log->challenges,
                'mentor_note' => $log->mentor_note,
                'images' => $log->images->map(fn ($img) => [
                    'id' => $img->id,
                    'name' => $img->image_name,
                    'url' => Storage::disk('public')->url($img->image_path),
                ])->values(),
                'documents' => $log->documents->map(fn ($doc) => [
                    'id' => $doc->id,
                    'name' => $doc->document_name,
                    'url' => Storage::disk('public')->url($doc->document_path),
                ])->values(),
            ]);

        return view('student.dashboard', array_merge($this->sharedLayoutData($student), [
            'totalLogbook' => $student->logbooks()->count(),
            'totalPending' => $student->logbooks()->where('status', 'pending')->count(),
            'recentLogs' => $recentLogs,
            'currentMentor' => $currentMentor,
            'rejectedLogs' => $rejectedLogs,
        ]));
    }



    public function pilihMentor(Request $request)
    {
        $student = $request->user();
        $search = $request->query('q');

        $currentMentor = $student->mentor()->first();

        $mentors = User::where('role', 'mentor')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->withCount(['students' => fn ($q) => $q->wherePivot('status', 'approved')])
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $myRequests = $student->studentRequests()->pluck('status', 'mentor_id');

        return view('student.pilih-mentor', array_merge($this->sharedLayoutData($student), [
            'mentors' => $mentors,
            'myRequests' => $myRequests,
            'currentMentor' => $currentMentor,
            'search' => $search,
        ]));
    }

    public function ajukanMentor(Request $request, User $mentor): RedirectResponse
    {
        abort_unless($mentor->role === 'mentor', 404);

        $student = $request->user();

        if ($student->mentor()->exists()) {
            return back()->with('error', 'Anda sudah memiliki mentor yang disetujui.');
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:500'],
        ]);


        $student->studentRequests()->updateOrCreate(
            ['mentor_id' => $mentor->id],
            ['status' => 'pending', 'message' => $validated['message'] ?? null, 'mentor_note' => null]
        );

        Notification::create([
            'user_id' => $mentor->id,
            'sender_name' => $student->name,
            'title' => "{$student->name} mengajukan bimbingan kepada Anda.",
        ]);

        return back()->with('status', 'Pengajuan bimbingan berhasil dikirim, menunggu persetujuan mentor.');
    }

    public function store(Request $request, NagerDateService $nagerDate): RedirectResponse
    {
        $student = $request->user();

        abort_unless(
            $student->mentor()->exists(),
            403,
            'Anda harus memiliki mentor yang disetujui sebelum bisa mengisi logbook.'
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string'],
            'challenges' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,doc,docx', 'max:8192'],
        ]);

        $activityDate = now();
        $holiday = $nagerDate->checkHoliday($activityDate);

        $logbook = $student->logbooks()->create([
            'title' => $validated['title'],
            'activity_date' => $activityDate->toDateString(),
            'activity_detail' => $validated['detail'],
            'challenges' => $validated['challenges'] ?? null,
            'is_holiday' => $holiday['is_holiday'],
            'holiday_name' => $holiday['holiday_name'],
            'status' => 'pending',
        ]);

        foreach ($request->file('images') ?? [] as $image) {
            $path = $image->store('logbook-images', 'public');
            $logbook->images()->create(['image_path' => $path, 'image_name' => $image->getClientOriginalName()]);
        }

        foreach ($request->file('documents') ?? [] as $document) {
            $path = $document->store('logbook-documents', 'public');
            $logbook->documents()->create(['document_path' => $path, 'document_name' => $document->getClientOriginalName()]);
        }

        $mentor = $student->mentor()->first();
        if ($mentor) {
            Notification::create([
                'user_id' => $mentor->id,
                'sender_name' => $student->name,
                'title' => "{$student->name} mengirim logbook baru: \"{$logbook->title}\".",
            ]);
        }

        return back()->with('status', $holiday['is_holiday']
            ? "Logbook berhasil disimpan. Sistem mendeteksi hari ini adalah {$holiday['holiday_name']}."
            : 'Logbook berhasil disimpan.');
    }

    public function update(Request $request, Logbook $logbook): RedirectResponse
    {
        $student = $request->user();

        abort_unless($logbook->user_id === $student->id, 403);
        abort_unless($logbook->status === 'rejected', 403, 'Hanya logbook berstatus ditolak yang dapat diedit.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string'],
            'challenges' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,doc,docx', 'max:8192'],
        ]);

        $logbook->update([
            'title' => $validated['title'],
            'activity_detail' => $validated['detail'],
            'challenges' => $validated['challenges'] ?? null,
            'status' => 'pending',
            'mentor_note' => null,
        ]);

        foreach ($request->file('images') ?? [] as $image) {
            $path = $image->store('logbook-images', 'public');
            $logbook->images()->create(['image_path' => $path, 'image_name' => $image->getClientOriginalName()]);
        }

        foreach ($request->file('documents') ?? [] as $document) {
            $path = $document->store('logbook-documents', 'public');
            $logbook->documents()->create(['document_path' => $path, 'document_name' => $document->getClientOriginalName()]);
        }

        $mentor = $student->mentor()->first();
        if ($mentor) {
            Notification::create([
                'user_id' => $mentor->id,
                'sender_name' => $student->name,
                'title' => "{$student->name} mengirim ulang logbook: \"{$logbook->title}\".",
            ]);
        }

        return back()->with('status', 'Logbook berhasil diperbarui dan dikirim ulang untuk direview.');
    }

    public function deleteImage(Request $request, LogbookImage $image): RedirectResponse
    {
        $this->authorizeAttachmentOwnership($request, $image->logbook);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('status', 'Foto berhasil dihapus.');
    }

    public function deleteDocument(Request $request, LogbookDocument $document): RedirectResponse
    {
        $this->authorizeAttachmentOwnership($request, $document->logbook);

        Storage::disk('public')->delete($document->document_path);
        $document->delete();

        return back()->with('status', 'Dokumen berhasil dihapus.');
    }

       private function authorizeAttachmentOwnership(Request $request, Logbook $logbook): void
    {
        abort_unless($logbook->user_id === $request->user()->id, 403);
        abort_unless($logbook->status === 'rejected', 403, 'Lampiran hanya dapat dihapus saat logbook berstatus ditolak.');
    }

    public function riwayat(Request $request)
    {
        $student = $request->user();

        $logbookHistory = $student->logbooks()
            ->withCount(['images', 'documents'])
            ->orderBy('activity_date', 'desc')
            ->get()
            ->map(fn (Logbook $log) => $this->formatLogEntry($log, $student));

        return view('student.riwayat', array_merge($this->sharedLayoutData($student), [
            'selectedStudent' => [
                'id' => $student->id,
                'name' => $student->name,
                'total_logbook' => $logbookHistory->count(),
            ],
            'logbookHistory' => $logbookHistory,
        ]));
    }
}
