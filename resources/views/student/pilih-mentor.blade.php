@extends('layouts.app')

@section('title', 'Cari Mentor')
@section('activeMenu', 'pilih-mentor')

@section('content')
    <div class="px-5 py-6 md:px-7 md:py-8">
        <h1 class="font-['Space_Grotesk'] text-2xl font-bold text-[#1A1C1E]">Cari Mentor</h1>

        @if ($currentMentor)
            <div class="mt-4 px-4 py-3 bg-[#1A1C1E]/[0.05] border-l-2 border-[#1A1C1E] text-sm text-[#1A1C1E]">
                Anda sudah dibimbing oleh <strong>{{ $currentMentor->name }}</strong>.
            </div>
        @else
            <p class="mt-1 text-[0.92rem] text-[#6C7278]">Pilih mentor lalu ajukan permintaan bimbingan.</p>
        @endif

        @if (session('status'))
            <div class="mt-4 px-3 py-2.5 bg-[#1A1C1E]/[0.05] border-l-2 border-[#1A1C1E] text-sm text-[#1A1C1E]">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mt-4 px-3 py-2.5 bg-[#B8422E]/[0.08] border-l-2 border-[#B8422E] text-sm text-[#B8422E]">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" action="{{ route('pilih-mentor.index') }}" class="mt-6 max-w-sm">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama mentor..."
                class="w-full border border-[#1A1C1E]/[0.16] px-3 py-2.5 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E]">
        </form>

        <div class="mt-6 border-t border-[#1A1C1E]/[0.12]">
            @forelse ($mentors as $mentor)
                @php $status = $myRequests[$mentor->id] ?? null; @endphp
                <div class="flex items-center justify-between px-2 py-4 border-b border-[#1A1C1E]/[0.12]">
                    <div>
                        <p class="m-0 text-[0.95rem] font-bold text-[#1A1C1E]">{{ $mentor->name }}</p>
                        <p class="mt-[3px] text-[0.82rem] text-[#6C7278]">
                            {{ $mentor->students_count }} mahasiswa bimbingan
                        </p>
                    </div>

                    @if ($currentMentor)
                        <span class="text-[0.82rem] text-[#C2C6CA]">—</span>
                    @elseif ($status === 'pending')
                        <span
                            class="px-2 py-0.5 text-[0.72rem] uppercase border border-dashed border-[#9AA0A6] text-[#6C7278]">
                            Menunggu
                        </span>
                    @elseif ($status === 'rejected')
                        <form method="POST" action="{{ route('pilih-mentor.ajukan', $mentor->id) }}">
                            @csrf
                            <button type="submit"
                                class="px-3 py-1.5 text-[0.82rem] border border-[#B8422E] text-[#B8422E] hover:bg-[#B8422E]/[0.05]">
                                Ditolak · Ajukan Lagi
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('pilih-mentor.ajukan', $mentor->id) }}">
                            @csrf
                            <button type="submit"
                                class="px-3 py-1.5 text-[0.82rem] bg-[#1A1C1E] text-[#F7F5F2] hover:opacity-90">
                                Ajukan Bimbingan
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="px-2 py-16 text-center text-[0.92rem] text-[#6C7278]">
                    Tidak ada mentor ditemukan.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $mentors->links() }}</div>
    </div>
@endsection
