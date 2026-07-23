@extends('layouts.app')

@section('title', 'Antrian Review')
@section('activeMenu', 'antrian')

@section('content')
    @php
        $currentEntry = $mentorQueue[0] ?? null;
    @endphp

    <section
        class="flex flex-wrap items-center gap-2.5 md:flex-nowrap md:gap-[18px] px-5 py-4 md:px-7 border-b border-[#1A1C1E]/[0.12] text-[#6C7278] text-[0.95rem]">
        <div><span class="text-[1.35rem] font-extrabold text-[#1A1C1E] mr-1">{{ $totalMahasiswa }}</span> Mahasiswa
            Bimbingan</div>
        <div class="hidden md:block w-px h-6 bg-[#1A1C1E]/[0.12]"></div>
        <div><span class="text-[1.35rem] font-extrabold text-[#B8422E] mr-1">{{ $totalPending }}</span> Menunggu
            Review</div>
        <div class="hidden md:block w-px h-6 bg-[#1A1C1E]/[0.12]"></div>
        <div><span class="text-[1.35rem] font-extrabold text-[#1A1C1E] mr-1">{{ $direviewHariIni }}</span> Direview
            Hari Ini</div>
    </section>

    <section class="px-5 py-4 md:px-7 md:py-5">
        <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-5">
            {{-- List Antrian --}}
            <div class="border border-[#1A1C1E]/[0.12] bg-white max-h-[70vh] overflow-y-auto">
                <div
                    class="px-4 py-3 text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] border-b border-[#1A1C1E]/[0.12] sticky top-0 bg-white">
                    Antrian ({{ count($mentorQueue) }})
                </div>
                @forelse ($mentorQueue as $index => $entry)
                    <a href="#" data-id="{{ $entry['id'] }}" data-student="{{ $entry['student'] }}"
                        data-title="{{ $entry['title'] }}" data-date="{{ $entry['date'] }}"
                        data-detail="{{ $entry['detail'] }}" data-challenge="{{ $entry['challenge'] }}"
                        data-is-holiday="{{ $entry['is_holiday'] ? '1' : '0' }}"
                        data-holiday-name="{{ $entry['holiday_name'] }}" data-images="{{ json_encode($entry['images']) }}"
                        data-documents="{{ json_encode($entry['documents']) }}"
                        class="queue-item block px-4 py-3 border-b border-[#1A1C1E]/[0.12] last:border-b-0 no-underline hover:bg-[#1A1C1E]/[0.03] {{ $index === 0 ? 'bg-[#1A1C1E]/[0.04]' : '' }}">
                        <p class="m-0 text-[0.92rem] font-bold text-[#1A1C1E]">{{ $entry['student'] }}</p>
                        <p class="mt-1 text-[0.8rem] text-[#6C7278]">{{ $entry['preview'] }}</p>
                    </a>
                @empty
                    <div class="px-4 py-10 text-center text-[0.9rem] text-[#6C7278]">
                        Tidak ada logbook yang menunggu review.
                    </div>
                @endforelse
            </div>

            {{-- Panel Detail + Keputusan --}}
            <div class="border border-[#1A1C1E]/[0.12] bg-white p-5 md:p-6">
                @if ($currentEntry)
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <div id="detailDate"
                            class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278]">
                            {{ $currentEntry['date'] }}
                        </div>
                        <span id="detailHolidayBadge"
                            class="px-2 py-0.5 text-[0.68rem] font-['Space_Grotesk'] uppercase rounded-[2px] bg-orange-100 text-orange-800 {{ $currentEntry['is_holiday'] ? '' : 'hidden' }}">
                            <span id="detailHolidayName">{{ $currentEntry['holiday_name'] }}</span>
                        </span>
                    </div>
                    <h2 id="detailStudent" class="text-[1.15rem] font-bold text-[#1A1C1E] mb-0.5">
                        {{ $currentEntry['student'] }}</h2>
                    <p id="detailTitle" class="text-[0.95rem] text-[#6C7278] mb-5">{{ $currentEntry['title'] }}</p>

                    <div class="mb-5">
                        <div
                            class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Detail Aktivitas
                        </div>
                        <p id="detailText" class="text-[0.92rem] text-[#1A1C1E]">{{ $currentEntry['detail'] }}</p>
                    </div>

                    <div class="mb-5">
                        <div
                            class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Kendala
                        </div>
                        <p id="detailChallenge" class="text-[0.92rem] text-[#1A1C1E]">{{ $currentEntry['challenge'] }}</p>
                    </div>

                    <div class="mb-5">
                        <div
                            class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Foto Bukti Kegiatan
                        </div>
                        <div id="detailImages" class="flex flex-wrap gap-2"></div>
                    </div>

                    <div class="mb-5">
                        <div
                            class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Dokumen Lampiran
                        </div>
                        <div id="detailDocuments" class="grid gap-1.5"></div>
                    </div>

                    <div>
                        <div
                            class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Catatan Mentor
                        </div>
                        <div class="grid gap-2">
                            <label for="mentorNote" class="text-[0.8rem] text-[#6C7278]">Opsional untuk setuju, wajib
                                untuk tolak</label>
                            <textarea id="mentorNote" name="mentor_note" placeholder="Tulis catatan untuk mahasiswa..."
                                class="w-full min-h-[80px] border border-[#1A1C1E]/[0.16] px-3 py-2.5 text-[0.92rem] text-[#1A1C1E] resize-y focus:outline-none focus:border-[#1A1C1E]"></textarea>
                            <div id="rejectHint" class="hidden text-[0.8rem] text-[#B8422E]">
                                Catatan mentor diperlukan saat menolak logbook.
                            </div>
                            <div class="flex gap-2.5 mt-1">
                                <button type="button" id="approveBtn"
                                    class="border-0 bg-[#1A1C1E] text-[#F7F5F2] px-4 py-2.5 font-semibold cursor-pointer text-[0.9rem] hover:opacity-90">
                                    Setujui
                                </button>
                                <button type="button" id="rejectButton"
                                    class="border border-[#B8422E] text-[#B8422E] bg-white px-4 py-2.5 font-semibold cursor-pointer text-[0.9rem] hover:bg-[#B8422E]/[0.05]">
                                    Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="py-16 text-center text-[0.9rem] text-[#6C7278]">
                        Tidak ada logbook untuk direview.
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const items = document.querySelectorAll('.queue-item');
        const detailDate = document.getElementById('detailDate');
        const detailStudent = document.getElementById('detailStudent');
        const detailTitle = document.getElementById('detailTitle');
        const detailText = document.getElementById('detailText');
        const detailChallenge = document.getElementById('detailChallenge');
        const detailImages = document.getElementById('detailImages');
        const detailDocuments = document.getElementById('detailDocuments');
        const detailHolidayBadge = document.getElementById('detailHolidayBadge');
        const detailHolidayName = document.getElementById('detailHolidayName');
        const mentorNote = document.getElementById('mentorNote');
        const rejectHint = document.getElementById('rejectHint');
        const rejectButton = document.getElementById('rejectButton');
        const approveBtn = document.getElementById('approveBtn');
        let currentLogbookId = items.length ? items[0].dataset.id : null;

        const approveUrlTemplate = '{{ route('mentor.antrian.setujui', ['logbook' => 'ID']) }}';
        const rejectUrlTemplate = '{{ route('mentor.antrian.tolak', ['logbook' => 'ID']) }}';
        const csrfToken = '{{ csrf_token() }}';

        function renderAttachments(item) {
            const images = JSON.parse(item.dataset.images || '[]');
            const documents = JSON.parse(item.dataset.documents || '[]');

            detailImages.innerHTML = '';
            if (images.length === 0) {
                detailImages.innerHTML = '<p class="text-[0.85rem] text-[#6C7278]">Tidak ada foto.</p>';
            } else {
                images.forEach((img) => {
                    const link = document.createElement('a');
                    link.href = img.url;
                    link.target = '_blank';
                    link.rel = 'noopener';
                    link.className = 'block w-20 h-20 border border-[#1A1C1E]/[0.12] overflow-hidden';

                    const thumb = document.createElement('img');
                    thumb.src = img.url;
                    thumb.alt = img.name;
                    thumb.className = 'w-full h-full object-cover';

                    link.appendChild(thumb);
                    detailImages.appendChild(link);
                });
            }

            detailDocuments.innerHTML = '';
            if (documents.length === 0) {
                detailDocuments.innerHTML = '<p class="text-[0.85rem] text-[#6C7278]">Tidak ada dokumen.</p>';
            } else {
                documents.forEach((doc) => {
                    const link = document.createElement('a');
                    link.href = doc.url;
                    link.target = '_blank';
                    link.rel = 'noopener';
                    link.className =
                        'flex items-center justify-between px-3 py-2 border border-[#1A1C1E]/[0.12] text-[0.85rem] text-[#1A1C1E] no-underline hover:bg-[#1A1C1E]/[0.03]';
                    link.textContent = doc.name;

                    const openLabel = document.createElement('span');
                    openLabel.className = 'text-[0.75rem] text-[#6C7278]';
                    openLabel.textContent = 'Buka →';
                    link.appendChild(openLabel);

                    detailDocuments.appendChild(link);
                });
            }
        }

        items.forEach((item) => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                items.forEach((entry) => entry.classList.remove('bg-[#1A1C1E]/[0.04]'));
                this.classList.add('bg-[#1A1C1E]/[0.04]');

                currentLogbookId = this.dataset.id;
                detailDate.textContent = this.dataset.date;
                detailStudent.textContent = this.dataset.student;
                detailTitle.textContent = this.dataset.title;
                detailText.textContent = this.dataset.detail;
                detailChallenge.textContent = this.dataset.challenge;

                if (this.dataset.isHoliday === '1') {
                    detailHolidayBadge.classList.remove('hidden');
                    detailHolidayName.textContent = this.dataset.holidayName;
                } else {
                    detailHolidayBadge.classList.add('hidden');
                }

                renderAttachments(this);

                mentorNote.value = '';
                rejectHint.classList.add('hidden');
            });
        });

        if (items.length) {
            renderAttachments(items[0]);
        }

        function submitReview(urlTemplate, note) {
            if (!currentLogbookId) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = urlTemplate.replace('ID', currentLogbookId);
            form.style.display = 'none';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const noteInput = document.createElement('input');
            noteInput.type = 'hidden';
            noteInput.name = 'mentor_note';
            noteInput.value = note;
            form.appendChild(noteInput);

            document.body.appendChild(form);
            form.submit();
        }

        approveBtn.addEventListener('click', function(event) {
            event.preventDefault();
            rejectHint.classList.add('hidden');
            submitReview(approveUrlTemplate, mentorNote.value);
        });

        rejectButton.addEventListener('click', function(event) {
            event.preventDefault();
            if (!mentorNote.value.trim()) {
                rejectHint.classList.remove('hidden');
                return;
            }
            rejectHint.classList.add('hidden');
            submitReview(rejectUrlTemplate, mentorNote.value);
        });
    </script>
@endpush
