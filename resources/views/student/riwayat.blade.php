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
        @forelse ($logbookHistory as $entry)
            <div class="flex items-center justify-between px-2 py-4 border-b border-[#1A1C1E]/[0.12] last:border-b-0">
                <div>
                    <p class="m-0 text-[0.95rem] font-bold">{{ $entry['date'] }}</p>
                    <p class="mt-[3px] text-[0.82rem] text-[#6C7278]">{{ Str::limit($entry['detail'], 100) }}</p>
                    @if ($entry['mentor'])
                        <p class="mt-[3px] text-[0.75rem] text-[#6C7278]">
                            Pembimbing: <strong>{{ $entry['mentor'] }}</strong>
                        </p>
                    @endif
                </div>
                <span
                    class="px-2 py-0.5 text-[0.72rem] font-['Space_Grotesk'] uppercase rounded-[2px] whitespace-nowrap {{ $statusClasses[$entry['status']] ?? 'text-[#C2C6CA]' }}">
                    {{ $entry['status_label'] ?? ucfirst($entry['status']) }}
                </span>
            </div>
        @empty
            <div class="py-10 px-2 text-center text-[#6C7278]">
                <p>Belum ada logbook yang diisi.</p>
            </div>
        @endforelse
    </div>
@endsection
