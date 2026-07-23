@extends('layouts.app')

@section('title', 'Dashboard')
@section('activeMenu', 'dashboard')

@section('content')
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
            <div class="border border-[#1A1C1E]/[0.12] p-5 bg-[#FCFAF7]">
                <div class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-3">
                    Isi Logbook Hari Ini
                </div>

                <form method="POST" action="{{ route('logbook.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label
                            class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Judul Aktivitas
                        </label>
                        <input type="text" name="title" required
                            class="w-full border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem] focus:outline-none focus:border-[#1A1C1E]"
                            placeholder="Judul singkat aktivitas Anda">
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Detail Aktivitas
                        </label>
                        <textarea name="detail" required
                            class="w-full min-h-[92px] border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem] resize-y"
                            placeholder="Deskripsikan aktivitas Anda hari ini..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Kendala (Opsional)
                        </label>
                        <textarea name="challenges"
                            class="w-full min-h-[64px] border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem] resize-y"
                            placeholder="Tuliskan kendala yang dihadapi..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_holiday" id="isHolidayCheckbox" value="1" class="w-auto">
                            <span class="text-[0.92rem]">Hari Libur?</span>
                        </label>
                    </div>

                    <div class="mb-4 hidden" id="holidayNameField">
                        <label
                            class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                            Nama Libur
                        </label>
                        <input type="text" name="holiday_name"
                            class="w-full border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem]"
                            placeholder="Nama hari libur">
                    </div>

                    <div class="flex gap-2.5">
                        <button type="submit"
                            class="border-0 bg-[#1A1C1E] text-[#F7F5F2] px-3.5 py-2.5 font-semibold cursor-pointer text-[0.92rem] hover:opacity-90">
                            Simpan Logbook
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($recentLogs->count() > 0)
            @php
                $statusClasses = [
                    'approved' => 'border border-[#1A1C1E] text-[#1A1C1E]',
                    'pending' => 'border border-dashed border-[#9AA0A6] text-[#6C7278]',
                    'rejected' => 'border border-[#B8422E] text-[#B8422E]',
                    'holiday' => 'border border-[#B8422E] text-[#B8422E]',
                ];
            @endphp

            <div class="mt-7">
                <div class="text-[0.95rem] font-bold mb-4">Logbook Terbaru</div>
                <div class="border-t border-b border-[#1A1C1E]/[0.12]">
                    @foreach ($recentLogs as $log)
                        <a href="{{ route('riwayat') }}"
                            class="flex items-center justify-between px-2 py-4 border-b border-[#1A1C1E]/[0.12] last:border-b-0 hover:bg-[#1A1C1E]/[0.02]">
                            <div>
                                <p class="m-0 text-[0.95rem] font-bold">{{ $log['date'] }}</p>
                                <p class="mt-[3px] text-[0.82rem] text-[#6C7278]">{{ Str::limit($log['detail'], 80) }}
                                </p>
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
        const isHolidayCheckbox = document.getElementById('isHolidayCheckbox');
        const holidayNameField = document.getElementById('holidayNameField');

        if (isHolidayCheckbox && holidayNameField) {
            isHolidayCheckbox.addEventListener('change', function() {
                holidayNameField.classList.toggle('hidden', !this.checked);
            });
        }
    </script>
@endpush
