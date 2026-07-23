<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternLog | @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>

<body class="bg-[#F7F5F2] text-[#1A1C1E] font-['Public_Sans'] leading-relaxed">
    <div class="min-h-screen flex flex-col bg-[#F7F5F2]">
        @include('layouts.partials.topbar')

        <div class="flex flex-1 flex-col md:flex-row">
            @include('layouts.partials.sidebar')

            <main class="flex-1 flex flex-col">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Script dropdown notifikasi & menu akun ada di layouts/partials/topbar.blade.php --}}
    @stack('scripts')
</body>

</html>
