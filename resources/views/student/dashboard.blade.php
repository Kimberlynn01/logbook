@extends('layouts.app')

@section('title', 'Dashboard')
@section('activeMenu', 'dashboard')

@section('content')
    @php
        $editingLogId = old('_edit_logbook_id', request()->query('edit'));
        $editingLog = $editingLogId ? $rejectedLogs->firstWhere('id', (int) $editingLogId) : null;
    @endphp

    <section
        class="flex flex-wrap items-center gap-2.5 md:flex-nowrap md:gap-[18px] px-5 py-4 md:px-7 border-b border-[#1A1C1E]/[0.12] text-[#6C7278] text-[0.95rem]">
        <div><span class="text-[1.35rem] font-extrabold text-[#1A1C1E] mr-1">{{ $totalLogbook }}</span> Total
            Logbook</div>
        <div class="hidden md:block w-px h-6 bg-[#1A1C1E]/[0.12]"></div>
        <div><span class="text-[1.35rem] font-extrabold text-[#B8422E] mr-1">{{ $totalPending }}</span> Menunggu
            Review</div>
    </section>

    <section class="px-5 pt-4 pb-6 md:px-7 md:pt-5 md:pb-7 flex flex-col gap-[18px]">
        <div class="max-w-[600px]">
            @if ($currentMentor)
                <div id="logbookFormCard" class="border border-[#1A1C1E]/[0.12] p-5 bg-[#FCFAF7]">
                    <div id="logbookFormTitle"
                        class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-3">
                        {{ $editingLog ? 'Edit Logbook Ditolak' : 'Isi Logbook Hari Ini' }}
                    </div>

                    <div id="mentorNoteBanner"
                        class="mb-4 px-3 py-2.5 bg-[#B8422E]/[0.08] border-l-2 border-[#B8422E] text-[0.85rem] text-[#B8422E] {{ $editingLog ? '' : 'hidden' }}">
                        <strong>Catatan Mentor:</strong>
                        <span id="mentorNoteText">{{ $editingLog['mentor_note'] ?? '' }}</span>
                    </div>

                    <form id="logbookForm" method="POST"
                        action="{{ $editingLog ? route('logbook.update', $editingLog['id']) : route('logbook.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($editingLog)
                            @method('PUT')
                        @endif
                        <input type="hidden" name="_edit_logbook_id" id="editLogbookId"
                            value="{{ $editingLog['id'] ?? '' }}">

                        <div class="mb-4">
                            <label
                                class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                Judul Aktivitas
                            </label>
                            <input type="text" name="title" id="formTitle" required
                                value="{{ old('title', $editingLog['title'] ?? '') }}"
                                class="w-full border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem] focus:outline-none focus:border-[#1A1C1E]"
                                placeholder="Judul singkat aktivitas Anda">
                            @error('title')
                                <span class="block mt-1 text-[0.78rem] text-[#B8422E]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                Detail Aktivitas
                            </label>
                            <textarea name="detail" id="formDetail" required
                                class="w-full min-h-[92px] border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem] resize-y"
                                placeholder="Deskripsikan aktivitas Anda hari ini...">{{ old('detail', $editingLog['detail'] ?? '') }}</textarea>
                            @error('detail')
                                <span class="block mt-1 text-[0.78rem] text-[#B8422E]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                Kendala (Opsional)
                            </label>
                            <textarea name="challenges" id="formChallenges"
                                class="w-full min-h-[64px] border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem] resize-y"
                                placeholder="Tuliskan kendala yang dihadapi...">{{ old('challenges', $editingLog['challenges'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                Foto Bukti Kegiatan (Opsional, boleh lebih dari satu)
                            </label>
                            <input type="file" name="images[]" multiple accept="image/*"
                                class="w-full border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.85rem] file:mr-3 file:border-0 file:bg-[#1A1C1E] file:text-[#F7F5F2] file:px-3 file:py-1.5 file:text-[0.8rem] file:cursor-pointer">
                            @error('images.*')
                                <span class="block mt-1 text-[0.78rem] text-[#B8422E]">{{ $message }}</span>
                            @enderror
                            @if ($editingLog)
                                <p class="mt-1 text-[0.75rem] text-[#9AA0A6]">Foto lama tetap tersimpan, ini untuk
                                    tambahan baru saja.</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                Dokumen Pendukung (Opsional, PDF/Word, boleh lebih dari satu)
                            </label>
                            <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx"
                                class="w-full border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.85rem] file:mr-3 file:border-0 file:bg-[#1A1C1E] file:text-[#F7F5F2] file:px-3 file:py-1.5 file:text-[0.8rem] file:cursor-pointer">
                            @error('documents.*')
                                <span class="block mt-1 text-[0.78rem] text-[#B8422E]">{{ $message }}</span>
                            @enderror
                            @if ($editingLog)
                                <p class="mt-1 text-[0.75rem] text-[#9AA0A6]">Dokumen lama tetap tersimpan, ini untuk
                                    tambahan baru saja.</p>
                            @endif
                        </div>

                        @if ($editingLog && ($editingLog['images']->count() > 0 || $editingLog['documents']->count() > 0))
                            <div class="mb-4">
                                <label
                                    class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                    Lampiran Tersimpan
                                </label>

                                @if ($editingLog['images']->count() > 0)
                                    <div class="flex flex-wrap gap-3 mb-2">
                                        @foreach ($editingLog['images'] as $img)
                                            <div class="relative w-16 h-16" id="imageWrap{{ $img['id'] }}">
                                                <a href="{{ $img['url'] }}" target="_blank" rel="noopener"
                                                    class="block w-full h-full border border-[#1A1C1E]/[0.12] overflow-hidden">
                                                    <img src="{{ $img['url'] }}" alt="{{ $img['name'] }}"
                                                        class="w-full h-full object-cover">
                                                </a>
                                                <button type="button"
                                                    onclick="deleteAttachment('{{ route('logbook.images.destroy', $img['id']) }}', 'imageWrap{{ $img['id'] }}')"
                                                    class="absolute -top-2 -right-2 w-5 h-5 flex items-center justify-center bg-[#B8422E] text-white text-[0.65rem] leading-none rounded-full hover:opacity-90 border-2 border-[#FCFAF7]"
                                                    title="Hapus foto">✕</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($editingLog['documents']->count() > 0)
                                    <div class="grid gap-1.5">
                                        @foreach ($editingLog['documents'] as $doc)
                                            <div class="flex items-center justify-between px-3 py-2 border border-[#1A1C1E]/[0.12] text-[0.82rem]"
                                                id="documentWrap{{ $doc['id'] }}">
                                                <a href="{{ $doc['url'] }}" target="_blank" rel="noopener"
                                                    class="text-[#1A1C1E] no-underline hover:underline">
                                                    {{ $doc['name'] }}
                                                </a>
                                                <button type="button"
                                                    onclick="deleteAttachment('{{ route('logbook.documents.destroy', $doc['id']) }}', 'documentWrap{{ $doc['id'] }}')"
                                                    class="text-[#B8422E] text-[0.78rem] hover:underline"
                                                    title="Hapus dokumen">Hapus</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <p class="mb-4 text-[0.8rem] text-[#6C7278]">
                            Status hari libur nasional terdeteksi otomatis oleh sistem berdasarkan tanggal hari ini —
                            tidak perlu diisi manual.
                        </p>

                        <div class="flex gap-2.5">
                            <button type="submit" id="formSubmitBtn"
                                class="border-0 bg-[#1A1C1E] text-[#F7F5F2] px-3.5 py-2.5 font-semibold cursor-pointer text-[0.92rem] hover:opacity-90">
                                {{ $editingLog ? 'Kirim Ulang Logbook' : 'Simpan Logbook' }}
                            </button>
                            <button type="button" id="cancelEditBtn" onclick="exitEditMode()"
                                class="border border-[#1A1C1E]/[0.16] text-[#1A1C1E] px-3.5 py-2.5 font-semibold cursor-pointer text-[0.92rem] hover:bg-[#1A1C1E]/[0.03] {{ $editingLog ? '' : 'hidden' }}">
                                Batal Edit
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="border border-dashed border-[#9AA0A6] p-6 bg-[#FCFAF7] text-center">
                    <div class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-2">
                        Belum Ada Mentor
                    </div>
                    <p class="text-[0.92rem] text-[#1A1C1E] mb-4">
                        Anda perlu mengajukan bimbingan ke mentor terlebih dahulu sebelum bisa mengisi logbook.
                    </p>
                    <a href="{{ route('pilih-mentor.index') }}"
                        class="inline-block border-0 bg-[#1A1C1E] text-[#F7F5F2] px-4 py-2.5 font-semibold text-[0.92rem] no-underline hover:opacity-90">
                        Cari & Ajukan Mentor
                    </a>
                </div>
            @endif
        </div>

        @if ($recentLogs->count() > 0)
            @php
                $statusClasses = [
                    'approved' => 'border border-[#1A1C1E] text-[#1A1C1E]',
                    'pending' => 'border border-dashed border-[#9AA0A6] text-[#6C7278]',
                    'rejected' => 'border border-[#B8422E] text-[#B8422E]',
                ];
            @endphp

            <div class="mt-7">
                <div class="text-[0.95rem] font-bold mb-4">Logbook Terbaru</div>
                <div class="border-t border-b border-[#1A1C1E]/[0.12]">
                    @foreach ($recentLogs as $log)
                        <a href="{{ route('riwayat') }}"
                            class="flex items-center justify-between px-2 py-4 border-b border-[#1A1C1E]/[0.12] last:border-b-0 hover:bg-[#1A1C1E]/[0.02]">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="m-0 text-[0.95rem] font-bold">{{ $log['title'] }}</p>
                                    @if ($log['is_holiday'])
                                        <span
                                            class="px-2 py-0.5 text-[0.68rem] font-['Space_Grotesk'] uppercase rounded-[2px] bg-orange-100 text-orange-800 whitespace-nowrap">
                                            {{ $log['holiday_name'] ?? 'Hari Libur' }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-[3px] text-[0.82rem] text-[#6C7278]">{{ $log['date'] }} ·
                                    {{ Str::limit($log['detail'], 80) }}</p>
                                @if (($log['image_count'] ?? 0) > 0 || ($log['document_count'] ?? 0) > 0)
                                    <p class="mt-[3px] text-[0.75rem] text-[#9AA0A6]">
                                        {{ $log['image_count'] }} foto · {{ $log['document_count'] }} dokumen
                                    </p>
                                @endif
                            </div>
                            <span
                                class="px-2 py-0.5 text-[0.72rem] font-['Space_Grotesk'] uppercase rounded-[2px] whitespace-nowrap {{ $statusClasses[$log['status']] ?? 'text-[#C2C6CA]' }}">
                                {{ $log['status_label'] ?? ucfirst($log['status']) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        const rejectedLogs = @json($rejectedLogs);
        const hasServerSideEdit = {{ $editingLog ? 'true' : 'false' }};
        const csrfToken = '{{ csrf_token() }}';

        function enterEditMode(log) {
            window.location.href = `${window.location.pathname}?edit=${log.id}#logbookFormCard`;
        }

        function exitEditMode() {
            window.location.href = window.location.pathname;
        }

        /**
         * Hapus lampiran (foto/dokumen) lewat fetch DELETE — BUKAN <form> di
         * dalam <form>, karena HTML tidak mengizinkan form bersarang (browser
         * akan menutup paksa form induk lebih awal, merusak tombol submit utama
         * DAN tombol hapus ini sendiri).
         */
        function deleteAttachment(url, wrapperId) {
            if (!confirm('Yakin ingin menghapus lampiran ini?')) return;

            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                })
                .then((response) => {
                    if (!response.ok) throw new Error('Gagal menghapus lampiran.');

                    const el = document.getElementById(wrapperId);
                    if (el) el.remove();
                })
                .catch(() => {
                    alert('Terjadi kesalahan saat menghapus lampiran. Silakan coba lagi.');
                });
        }

        function showRejectedAlert(index) {
            if (index >= rejectedLogs.length) return;

            const log = rejectedLogs[index];

            Swal.fire({
                title: 'Logbook Ditolak',
                html: `
                    <div style="text-align:left;">
                        <p style="font-weight:700; margin-bottom:4px;">${log.title ?? ''}</p>
                        <p style="font-size:0.85rem; color:#6C7278; margin-bottom:12px;">${log.detail ?? ''}</p>
                        <div style="background:rgba(184,66,46,0.08); border-left:3px solid #B8422E; padding:10px 12px; font-size:0.88rem;">
                            <strong>Catatan Mentor:</strong><br>${log.mentor_note ?? 'Tidak ada catatan.'}
                        </div>
                    </div>
                `,
                icon: 'warning',
                confirmButtonText: 'Edit Logbook',
                confirmButtonColor: '#1A1C1E',
                showCancelButton: true,
                cancelButtonText: rejectedLogs.length > index + 1 ? 'Lihat Berikutnya' : 'Tutup',
            }).then((result) => {
                if (result.isConfirmed) {
                    enterEditMode(log);
                } else {
                    showRejectedAlert(index + 1);
                }
            });
        }

        if (!hasServerSideEdit && rejectedLogs.length > 0) {
            showRejectedAlert(0);
        }
    </script>
@endpush
