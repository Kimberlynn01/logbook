<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternLog | @yield('title', 'Masuk')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>

<body class="bg-[#F7F5F2] text-[#1A1C1E] font-['Public_Sans'] leading-relaxed pb-20 sm:pb-0">
    <div class="min-h-screen flex flex-col items-center justify-center px-5 py-10">
        <span class="mb-8 font-['Space_Grotesk'] text-[0.82rem] tracking-[0.24em] uppercase text-[#6C7278]">
            InternLog
        </span>

        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </div>

    {{-- ===================== Mobile bottom navigation (Flutter-style) =====================
         Sama seperti di layouts.auth & landing page, supaya konsisten di semua halaman. --}}
    <nav class="sm:hidden fixed inset-x-0 bottom-0 z-50 flex bg-white border-t border-[#1A1C1E]/[0.12] px-1.5 pt-1.5"
        style="padding-bottom: calc(0.375rem + env(safe-area-inset-bottom)); box-shadow: 0 -6px 18px rgba(26,28,30,0.06);"
        aria-label="Navigasi utama">

        <a href="{{ url('/') }}"
            class="flex-1 flex flex-col items-center justify-center gap-0.5 px-0.5 py-1.5 text-[#6C7278]">
            <svg viewBox="0 0 24 24" class="w-[21px] h-[21px] stroke-current fill-none" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 11.5 12 4l9 7.5M5 10v9a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1v-9" />
            </svg>
            <span class="font-['Space_Grotesk'] text-[0.62rem] uppercase tracking-wide">Beranda</span>
        </a>

        <a href="{{ url('/#features') }}"
            class="flex-1 flex flex-col items-center justify-center gap-0.5 px-0.5 py-1.5 text-[#6C7278]">
            <svg viewBox="0 0 24 24" class="w-[21px] h-[21px] stroke-current fill-none" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10" />
            </svg>
            <span class="font-['Space_Grotesk'] text-[0.62rem] uppercase tracking-wide">Fitur</span>
        </a>

        <a href="{{ url('/#how') }}"
            class="flex-1 flex flex-col items-center justify-center gap-0.5 px-0.5 py-1.5 text-[#6C7278]">
            <svg viewBox="0 0 24 24" class="w-[21px] h-[21px] stroke-current fill-none" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3v3m0 12v3M4.2 4.2l2.1 2.1m11.4 11.4 2.1 2.1M3 12h3m12 0h3M4.2 19.8l2.1-2.1m11.4-11.4 2.1-2.1" />
            </svg>
            <span class="font-['Space_Grotesk'] text-[0.62rem] uppercase tracking-wide">Cara Kerja</span>
        </a>

        <a href="{{ route('login') }}"
            class="flex-1 flex flex-col items-center justify-center gap-0.5 px-0.5 py-1.5 {{ request()->routeIs('login') ? 'text-[#B8422E]' : 'text-[#6C7278]' }}">
            <svg viewBox="0 0 24 24" class="w-[21px] h-[21px] stroke-current fill-none" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0" />
            </svg>
            <span class="font-['Space_Grotesk'] text-[0.62rem] uppercase tracking-wide">Masuk</span>
        </a>

        <a href="{{ route('register') }}"
            class="flex-1 flex flex-col items-center justify-center gap-0.5 px-0.5 py-1.5 {{ request()->routeIs('register') ? 'text-[#B8422E]' : 'text-[#6C7278]' }}">
            <svg viewBox="0 0 24 24" class="w-[21px] h-[21px] stroke-current fill-none" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
            </svg>
            <span class="font-['Space_Grotesk'] text-[0.62rem] uppercase tracking-wide">Daftar</span>
        </a>
    </nav>

    @stack('scripts')
</body>

</html>
