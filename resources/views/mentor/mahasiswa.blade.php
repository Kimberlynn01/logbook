@extends('layouts.app')

@section('title', 'Mahasiswa Bimbingan')
@section('activeMenu', 'mahasiswa')

@section('content')
    <div class="px-5 py-6 md:px-7 md:py-8">
        <h1 class="font-['Space_Grotesk'] text-2xl font-bold text-[#1A1C1E]">Mahasiswa Bimbingan</h1>
        <p class="mt-1 text-[0.92rem] text-[#6C7278]">{{ count($students) }} mahasiswa dalam bimbingan Anda.</p>

        <div class="mt-6 border-t border-[#1A1C1E]/[0.12]">
            @forelse ($students as $student)
                <a href="{{ route('mentor.riwayat', $student['id']) }}"
                    class="flex items-center justify-between px-2 py-4 border-b border-[#1A1C1E]/[0.12] no-underline hover:bg-[#1A1C1E]/[0.02]">
                    <div>
                        <p class="m-0 text-[0.95rem] font-bold text-[#1A1C1E]">{{ $student['name'] }}</p>
                        <p class="mt-[3px] text-[0.82rem] text-[#6C7278]">
                            {{ $student['total_logbook'] }} logbook · terakhir mengisi {{ $student['last_entry'] }}
                        </p>
                    </div>

                    @if ($student['pending_count'] > 0)
                        <span
                            class="px-2 py-0.5 text-[0.72rem] font-['Space_Grotesk'] uppercase rounded-[2px] border border-dashed border-[#9AA0A6] text-[#6C7278] whitespace-nowrap">
                            {{ $student['pending_count'] }} Pending
                        </span>
                    @else
                        <span class="text-[#C2C6CA]">—</span>
                    @endif
                </a>
            @empty
                <div class="px-2 py-16 text-center text-[0.92rem] text-[#6C7278]">
                    <p>Belum ada mahasiswa bimbingan.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
