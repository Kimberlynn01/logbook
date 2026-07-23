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

<body class="bg-[#F7F5F2] text-[#1A1C1E] font-['Public_Sans'] leading-relaxed">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-[380px] bg-[#FCFAF7] border border-[#1A1C1E]/[0.12] px-7 py-8 md:px-8 md:py-9">
            <span
                class="inline-block mb-7 font-['Space_Grotesk'] text-[0.82rem] tracking-[0.24em] uppercase text-[#6C7278]">
                InternLog
            </span>

            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>
