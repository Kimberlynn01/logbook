@extends('layouts.app')

@section('title', 'Pengajuan Bimbingan')
@section('activeMenu', 'pengajuan')

@section('content')
    <div class="px-5 py-6 md:px-7 md:py-8">
        <h1 class="font-['Space_Grotesk'] text-2xl font-bold text-[#1A1C1E]">Pengajuan Bimbingan</h1>
        <p class="mt-1 text-[0.92rem] text-[#6C7278]">{{ count($pengajuanList) }} pengajuan menunggu keputusan Anda.</p>

        @if (session('status'))
            <div class="mt-4 px-3 py-2.5 bg-[#1A1C1E]/[0.05] border-l-2 border-[#1A1C1E] text-sm text-[#1A1C1E]">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 grid gap-4">
            @forelse ($pengajuanList as $item)
                <div class="border border-[#1A1C1E]/[0.12] p-5 bg-white">
                    <p class="text-[0.95rem] font-bold text-[#1A1C1E]">{{ $item->student->name }}</p>
                    <p class="mt-1 text-[0.82rem] text-[#6C7278]">{{ $item->student->email }}</p>
                    @if ($item->student->university || $item->student->major)
                        <p class="mt-1 text-[0.82rem] text-[#6C7278]">
                            {{ $item->student->university }}
                            @if ($item->student->major)
                                · {{ $item->student->major }}
                            @endif
                            @if ($item->student->degree_level)
                                · {{ $item->student->degree_level }}
                            @endif
                        </p>
                    @endif
                    @if ($item->message)
                        <p class="mt-2 text-[0.88rem] text-[#1A1C1E] italic">"{{ $item->message }}"</p>
                    @endif

                    <div class="mt-4 flex gap-2.5">
                        <form method="POST" action="{{ route('mentor.pengajuan.setujui', $item->id) }}">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 text-[0.85rem] font-semibold bg-[#1A1C1E] text-[#F7F5F2] hover:opacity-90">
                                Setujui
                            </button>
                        </form>

                        <details class="inline-block">
                            <summary
                                class="cursor-pointer px-4 py-2 text-[0.85rem] font-semibold border border-[#B8422E] text-[#B8422E] hover:bg-[#B8422E]/[0.05] list-none">
                                Tolak
                            </summary>
                            <form method="POST" action="{{ route('mentor.pengajuan.tolak', $item->id) }}"
                                class="mt-2 grid gap-2">
                                @csrf
                                <textarea name="mentor_note" required placeholder="Alasan penolakan..."
                                    class="w-full min-h-[70px] border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm resize-y"></textarea>
                                <button type="submit"
                                    class="justify-self-start px-4 py-2 text-[0.85rem] font-semibold bg-[#B8422E] text-white hover:opacity-90">
                                    Kirim Penolakan
                                </button>
                            </form>
                        </details>
                    </div>
                </div>
            @empty
                <div class="px-2 py-16 text-center text-[0.92rem] text-[#6C7278]">
                    Tidak ada pengajuan bimbingan yang menunggu.
                </div>
            @endforelse
        </div>
    </div>
@endsection
