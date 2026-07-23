@extends('layouts.app')

@section('title', 'Riwayat')
@section('activeMenu', 'riwayat')

@section('content')
    <div class="px-5 py-6 md:px-7 md:py-8">
        <a href="{{ route('mentor.mahasiswa') }}"
            class="inline-block mb-4 text-[0.88rem] text-[#6C7278] no-underline hover:text-[#1A1C1E]">
            &larr; Mahasiswa Bimbingan
        </a>
        <h1 class="font-['Space_Grotesk'] text-2xl font-bold text-[#1A1C1E]">{{ $selectedStudent['name'] }}</h1>
        <p class="mt-1 text-[0.92rem] text-[#6C7278]">{{ $selectedStudent['total_logbook'] }} logbook tercatat.</p>

        @php
            $statusClasses = [
                'approved' => 'border border-[#1A1C1E] text-[#1A1C1E]',
                'pending' => 'border border-dashed border-[#9AA0A6] text-[#6C7278]',
                'rejected' => 'border border-[#B8422E] text-[#B8422E]',
            ];
        @endphp

        <div class="mt-6 border-t border-[#1A1C1E]/[0.12]">
            @forelse ($logbookHistory as $entry)
                <details class="group border-b border-[#1A1C1E]/[0.12]">
                    <summary
                        class="flex items-center justify-between px-2 py-4 cursor-pointer list-none hover:bg-[#1A1C1E]/[0.02]">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="m-0 text-[0.95rem] font-bold text-[#1A1C1E]">{{ $entry['title'] }}</p>
                                @if ($entry['is_holiday'])
                                    <span
                                        class="px-2 py-0.5 text-[0.68rem] font-['Space_Grotesk'] uppercase rounded-[2px] bg-orange-100 text-orange-800 whitespace-nowrap">
                                        {{ $entry['holiday_name'] ?? 'Hari Libur' }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-[3px] text-[0.82rem] text-[#6C7278]">{{ $entry['date'] }} ·
                                {{ Str::limit($entry['detail'], 80) }}</p>
                            @if (($entry['image_count'] ?? 0) > 0 || ($entry['document_count'] ?? 0) > 0)
                                <p class="mt-[3px] text-[0.75rem] text-[#9AA0A6]">
                                    {{ $entry['image_count'] }} foto · {{ $entry['document_count'] }} dokumen ·
                                    <span class="underline group-open:hidden">Lihat detail</span>
                                    <span class="underline hidden group-open:inline">Tutup detail</span>
                                </p>
                            @endif
                        </div>
                        <span
                            class="px-2 py-0.5 text-[0.72rem] font-['Space_Grotesk'] uppercase rounded-[2px] whitespace-nowrap {{ $statusClasses[$entry['status']] ?? 'text-[#C2C6CA]' }}">
                            {{ $entry['status_label'] ?? ucfirst($entry['status']) }}
                        </span>
                    </summary>

                    <div class="px-2 pb-5 grid gap-4">
                        <div>
                            <div
                                class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                Detail Aktivitas
                            </div>
                            <p class="text-[0.9rem] text-[#1A1C1E]">{{ $entry['detail'] }}</p>
                        </div>

                        @if ($entry['challenges'])
                            <div>
                                <div
                                    class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                    Kendala
                                </div>
                                <p class="text-[0.9rem] text-[#1A1C1E]">{{ $entry['challenges'] }}</p>
                            </div>
                        @endif

                        @if ($entry['images']->count() > 0)
                            <div>
                                <div
                                    class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                    Foto Bukti Kegiatan
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($entry['images'] as $img)
                                        <a href="{{ $img['url'] }}" target="_blank" rel="noopener"
                                            class="block w-20 h-20 border border-[#1A1C1E]/[0.12] overflow-hidden">
                                            <img src="{{ $img['url'] }}" alt="{{ $img['name'] }}"
                                                class="w-full h-full object-cover">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($entry['documents']->count() > 0)
                            <div>
                                <div
                                    class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                    Dokumen Lampiran
                                </div>
                                <div class="grid gap-1.5">
                                    @foreach ($entry['documents'] as $doc)
                                        <a href="{{ $doc['url'] }}" target="_blank" rel="noopener"
                                            class="flex items-center justify-between px-3 py-2 border border-[#1A1C1E]/[0.12] text-[0.85rem] text-[#1A1C1E] no-underline hover:bg-[#1A1C1E]/[0.03]">
                                            {{ $doc['name'] }}
                                            <span class="text-[0.75rem] text-[#6C7278]">Buka →</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($entry['mentor_note'])
                            <div>
                                <div
                                    class="text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">
                                    Catatan Anda
                                </div>
                                <p class="text-[0.9rem] text-[#1A1C1E] italic">{{ $entry['mentor_note'] }}</p>
                            </div>
                        @endif
                    </div>
                </details>
            @empty
                <div class="px-2 py-16 text-center text-[0.92rem] text-[#6C7278]">
                    <p>Belum ada logbook dari mahasiswa ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
