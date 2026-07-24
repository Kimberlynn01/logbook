@extends('layouts.app')

@section('title', 'Riwayat Logbook')
@section('activeMenu', 'riwayat')

@section('content')
    <div class="px-5 py-6 md:px-7 border-b border-[#1A1C1E]/[0.12]">
        <h1 class="mt-2 text-[1.3rem] font-extrabold">Riwayat Logbook</h1>
        <p class="mt-1 text-[#6C7278] text-[0.9rem]">{{ count($logbookHistory) }} logbook yang telah Anda isi.</p>
    </div>

    @php
        $statusClasses = [
            'approved' => 'border border-[#1A1C1E] text-[#1A1C1E]',
            'pending' => 'border border-dashed border-[#9AA0A6] text-[#6C7278]',
            'rejected' => 'border border-[#B8422E] text-[#B8422E]',
            'holiday' => 'border border-[#B8422E] text-[#B8422E]',
        ];
    @endphp

    <div class="mx-5 mb-7 md:mx-7 border-t border-b border-[#1A1C1E]/[0.12]">
        @forelse ($logbookHistory as $index => $entry)
            <button type="button" onclick="showLogbookDetail({{ $index }})"
                class="flex items-center justify-between w-full px-2 py-4 border-b border-[#1A1C1E]/[0.12] last:border-b-0 text-left cursor-pointer bg-transparent border-t-0 border-l-0 border-r-0 hover:bg-[#1A1C1E]/[0.02]">
                <div>
                    <p class="m-0 text-[0.95rem] font-bold">{{ $entry['date'] }}</p>
                    <p class="mt-[3px] text-[0.82rem] text-[#6C7278]">{{ Str::limit($entry['detail'], 100) }}</p>
                    @if ($entry['mentor'])
                        <p class="mt-[3px] text-[0.75rem] text-[#6C7278]">
                            Pembimbing: <strong>{{ $entry['mentor'] }}</strong>
                        </p>
                    @endif
                    @if (($entry['image_count'] ?? 0) > 0 || ($entry['document_count'] ?? 0) > 0)
                        <p class="mt-[3px] text-[0.75rem] text-[#9AA0A6]">
                            {{ $entry['image_count'] }} foto · {{ $entry['document_count'] }} dokumen
                        </p>
                    @endif
                </div>
                <span
                    class="px-2 py-0.5 text-[0.72rem] font-['Space_Grotesk'] uppercase rounded-[2px] whitespace-nowrap shrink-0 ml-3 {{ $statusClasses[$entry['status']] ?? 'text-[#C2C6CA]' }}">
                    {{ $entry['status_label'] ?? ucfirst($entry['status']) }}
                </span>
            </button>
        @empty
            <div class="py-10 px-2 text-center text-[#6C7278]">
                <p>Belum ada logbook yang diisi.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        const logbookHistory = @json($logbookHistory);
        const csrfToken = '{{ csrf_token() }}';

        @php
            $deleteUrls = $logbookHistory->pluck('id')->mapWithKeys(fn($id) => [$id => route('logbook.destroy', $id)]);
        @endphp
        const deleteUrls = @json($deleteUrls);

        const statusMeta = {
            approved: {
                label: 'Disetujui',
                icon: 'success'
            },
            pending: {
                label: 'Menunggu',
                icon: 'info'
            },
            rejected: {
                label: 'Ditolak',
                icon: 'warning'
            },
            holiday: {
                label: 'Libur',
                icon: 'info'
            },
        };

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text ?? '';
            return div.innerHTML;
        }

        function confirmDeleteLogbook(logbookId) {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus logbook ini?',
                text: 'Logbook beserta foto dan dokumen di dalamnya akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#B8422E',
                cancelButtonColor: '#9AA0A6',
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch(deleteUrls[logbookId], {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    })
                    .then((response) => {
                        if (!response.ok) throw new Error('Gagal menghapus logbook.');
                        window.location.reload();
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menghapus logbook',
                            text: 'Silakan coba lagi.',
                            confirmButtonColor: '#1A1C1E',
                        });
                    });
            });
        }

        function showLogbookDetail(index) {
            const entry = logbookHistory[index];
            if (!entry) return;

            const meta = statusMeta[entry.status] ?? {
                label: entry.status_label ?? entry.status,
                icon: 'info'
            };
            const canDelete = entry.status !== 'approved';

            const imagesHtml = (entry.images ?? []).length > 0 ? `
                <div style="margin-bottom:12px;">
                    <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;color:#6C7278;margin-bottom:6px;">Foto Bukti Kegiatan</p>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        ${entry.images.map(img => `
                                <a href="${img.url}" target="_blank" rel="noopener">
                                    <img src="${img.url}" alt="${escapeHtml(img.name)}"
                                        style="width:64px;height:64px;object-fit:cover;border:1px solid rgba(26,28,30,0.12);">
                                </a>
                            `).join('')}
                    </div>
                </div>
            ` : '';

            const documentsHtml = (entry.documents ?? []).length > 0 ? `
                <div style="margin-bottom:12px;">
                    <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;color:#6C7278;margin-bottom:6px;">Dokumen Pendukung</p>
                    <div style="display:grid;gap:4px;">
                        ${entry.documents.map(doc => `
                                <a href="${doc.url}" target="_blank" rel="noopener"
                                    style="font-size:0.85rem;color:#1A1C1E;text-decoration:underline;">
                                    ${escapeHtml(doc.name)}
                                </a>
                            `).join('')}
                    </div>
                </div>
            ` : '';

            const challengesHtml = entry.challenges ? `
                <div style="margin-bottom:12px;">
                    <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;color:#6C7278;margin-bottom:4px;">Kendala</p>
                    <p style="font-size:0.88rem;margin:0;white-space:pre-wrap;">${escapeHtml(entry.challenges)}</p>
                </div>
            ` : '';

            const mentorNoteHtml = entry.mentor_note ? `
                <div style="background:rgba(184,66,46,0.08);border-left:3px solid #B8422E;padding:8px 10px;font-size:0.85rem;margin-bottom:12px;">
                    <strong>Catatan Mentor:</strong><br>${escapeHtml(entry.mentor_note)}
                </div>
            ` : '';

            const holidayHtml = entry.is_holiday ? `
                <div style="background:rgba(184,66,46,0.08);border-left:3px solid #B8422E;padding:8px 10px;font-size:0.85rem;margin-bottom:12px;">
                    Terdeteksi sebagai hari libur: <strong>${escapeHtml(entry.holiday_name ?? '-')}</strong>
                </div>
            ` : '';

            Swal.fire({
                title: entry.title ?? entry.date,
                html: `
                    <div style="text-align:left;">
                        <p style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:#6C7278;margin-bottom:10px;">${entry.date}</p>
                        <p style="font-size:0.9rem;margin:0 0 14px;white-space:pre-wrap;">${escapeHtml(entry.detail)}</p>
                        ${challengesHtml}
                        ${holidayHtml}
                        ${mentorNoteHtml}
                        ${imagesHtml}
                        ${documentsHtml}
                    </div>
                `,
                icon: meta.icon,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#1A1C1E',
                showDenyButton: canDelete,
                denyButtonText: 'Hapus Logbook',
                denyButtonColor: '#B8422E',
                width: 480,
            }).then((result) => {
                if (result.isDenied) {
                    confirmDeleteLogbook(entry.id);
                }
            });
        }
    </script>
@endpush
