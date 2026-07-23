@php

    $isMentor = optional(auth()->user())->role === 'mentor' ?? request()->routeIs('mentor.*');

    $activeMenu = trim($__env->yieldContent('activeMenu'));
    $mentorNavItems = [
        'antrian' => ['label' => 'Antrian', 'route' => 'mentor.antrian'],
        'pengajuan' => ['label' => 'Pengajuan Bimbingan', 'route' => 'mentor.pengajuan'],
        'mahasiswa' => ['label' => 'Mahasiswa Bimbingan', 'route' => 'mentor.mahasiswa'],
        'riwayat' => ['label' => 'Riwayat', 'route' => 'mentor.mahasiswa'],
        'profil' => ['label' => 'Profil', 'route' => 'profil'],
    ];

    $studentNavItems = [
        'dashboard' => ['label' => 'Ringkasan', 'route' => 'dashboard'],
        'pilih-mentor' => ['label' => 'Cari Mentor', 'route' => 'pilih-mentor.index'],
        'riwayat' => ['label' => 'Riwayat', 'route' => 'riwayat'],
        'profil' => ['label' => 'Profil', 'route' => 'profil'],
    ];

    $navItems = $isMentor ? $mentorNavItems : $studentNavItems;
@endphp

<aside
    class="w-full md:w-[220px] border-b md:border-b-0 md:border-r border-[#1A1C1E]/[0.12] px-5 py-6 md:py-7 bg-[#F7F5F2]">
    <span
        class="inline-block mb-[30px] font-['Space_Grotesk'] text-[0.82rem] tracking-[0.24em] uppercase text-[#6C7278]">
        InternLog
    </span>

    <nav class="grid gap-1">
        @foreach ($navItems as $key => $item)
            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                class="block py-[9px] pl-3 border-l-2 text-[0.95rem] no-underline {{ $activeMenu === $key ? 'border-l-[#1A1C1E] text-[#1A1C1E] font-bold' : 'border-l-transparent text-[#6C7278]' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</aside>
