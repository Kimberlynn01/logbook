@php
    $mentorName ??= auth()->user()->name ?? 'User';
    $mentorInitials ??= 'U';
    $mentorNotifications ??= [];
@endphp

<header
    class="flex items-center justify-between px-5 py-[18px] md:px-7 md:py-[22px] border-b border-[#1A1C1E]/[0.12] bg-[#F7F5F2]">
    <p class="text-[1rem] font-bold m-0">{{ $mentorName }}</p>

    <div class="relative flex items-center gap-3">
        <!-- Button Lonceng Notifikasi -->
        <button
            class="relative border-0 bg-transparent cursor-pointer p-1.5 flex items-center justify-center text-[#1A1C1E]"
            id="notificationToggle" type="button" aria-haspopup="true" aria-expanded="false" aria-label="Notifikasi">

            <!-- SVG Lonceng dengan ukuran fixed w-5 h-5 (20px x 20px) -->
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14.391 18.015C14.8198 19.6154 13.8701 21.2604 12.2697 21.6893C10.6693 22.1181 9.02426 21.1683 8.59543 19.5679M10.8915 5.74109C11.1957 5.19379 11.2959 4.53229 11.1211 3.8797C10.7637 2.54603 9.39288 1.75457 8.05921 2.11193C6.72555 2.46928 5.93409 3.84013 6.29144 5.17379C6.46631 5.82639 6.88384 6.34916 7.42094 6.67103M16.2359 9.44553C15.879 8.1134 14.926 6.99944 13.5868 6.3487C12.2475 5.69797 10.6316 5.56377 9.09449 5.97563C7.55741 6.38749 6.22508 7.31167 5.3906 8.54486C4.55612 9.77805 4.28785 11.2192 4.64479 12.5514C5.23537 14.7554 5.12069 16.5138 4.74774 17.8499C4.32267 19.3728 4.11014 20.1342 4.16756 20.2871C4.23325 20.462 4.28076 20.5101 4.455 20.5776C4.60729 20.6366 5.24706 20.4651 6.5266 20.1223L18.3917 16.9431C19.6712 16.6002 20.311 16.4288 20.4134 16.3015C20.5305 16.1559 20.5476 16.0906 20.5171 15.9063C20.4904 15.7451 19.9256 15.192 18.796 14.0857C17.805 13.115 16.8265 11.6496 16.2359 9.44553Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            @if (count($mentorNotifications) > 0)
                <span
                    class="absolute top-0 right-0 w-[16px] h-[16px] rounded-full bg-[#B8422E] text-[#F7F5F2] text-[0.6rem] font-['Space_Grotesk'] inline-flex items-center justify-center">
                    {{ count($mentorNotifications) }}
                </span>
            @endif
        </button>

        <!-- Dropdown Panel Notifikasi -->
        <div class="hidden absolute top-[calc(100%+10px)] right-0 w-[260px] bg-[#FCFAF7] border border-[#1A1C1E]/[0.12] z-20 shadow-sm"
            id="notificationPanel">
            <div class="px-3.5 py-2.5 font-bold border-b border-[#1A1C1E]/[0.12]">Notifikasi</div>

            @forelse ($mentorNotifications as $item)
                <div class="px-3.5 py-2.5 border-b border-[#1A1C1E]/[0.12] last:border-b-0">
                    <p class="m-0 text-sm">{{ $item['message'] }}</p>
                    <div class="mt-1 text-[0.75rem] text-[#6C7278]">{{ $item['time'] }}</div>
                </div>
            @empty
                <div class="px-3.5 py-2.5">
                    <p class="m-0 text-sm">Tidak ada notifikasi baru.</p>
                </div>
            @endforelse
        </div>

        <!-- Avatar User Toggle -->
        <button
            class="w-[34px] h-[34px] rounded-full bg-[#1A1C1E] text-[#F7F5F2] inline-flex items-center justify-center text-[0.72rem] font-['Space_Grotesk'] tracking-[0.08em] border-0 p-0 cursor-pointer focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#1A1C1E] focus-visible:outline-offset-2"
            id="userMenuToggle" type="button" aria-haspopup="true" aria-expanded="false"
            aria-label="Menu akun, {{ $mentorName }}">
            {{ $mentorInitials }}
        </button>

        <!-- Dropdown Panel User Menu -->
        <div class="hidden absolute top-[calc(100%+10px)] right-0 w-[260px] bg-[#FCFAF7] border border-[#1A1C1E]/[0.12] z-20 shadow-sm"
            id="userMenuPanel">
            <div class="px-3.5 py-3 border-b border-[#1A1C1E]/[0.12]">
                <p class="m-0 text-[0.9rem] font-bold">{{ $mentorName }}</p>
                <p class="mt-[3px] text-[0.78rem] text-[#6C7278] break-all">{{ auth()->user()?->email }}</p>
            </div>

            <div class="p-1.5 grid gap-0.5">
                <a href="{{ Route::has('profil') ? route('profil') : '#' }}"
                    class="block w-full text-left border-0 bg-transparent px-2.5 py-2 text-[0.88rem] text-[#1A1C1E] no-underline cursor-pointer hover:bg-[#1A1C1E]/[0.05]">
                    Profil
                </a>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left border-0 bg-transparent px-2.5 py-2 text-[0.88rem] text-[#B8422E] cursor-pointer hover:bg-[#1A1C1E]/[0.05]">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        (function() {
            const notifToggle = document.getElementById('notificationToggle');
            const notifPanel = document.getElementById('notificationPanel');
            const userToggle = document.getElementById('userMenuToggle');
            const userPanel = document.getElementById('userMenuPanel');

            function closeAll(except) {
                [
                    [notifToggle, notifPanel],
                    [userToggle, userPanel],
                ].forEach(([btn, panel]) => {
                    if (panel !== except) {
                        panel.classList.add('hidden');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            function bindToggle(btn, panel) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const willOpen = panel.classList.contains('hidden');
                    closeAll(willOpen ? panel : null);
                    panel.classList.toggle('hidden', !willOpen);
                    btn.setAttribute('aria-expanded', String(willOpen));
                });
            }

            bindToggle(notifToggle, notifPanel);
            bindToggle(userToggle, userPanel);

            document.addEventListener('click', function(e) {
                if (!notifPanel.contains(e.target) && !userPanel.contains(e.target)) {
                    closeAll();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeAll();
            });
        })();
    </script>
@endpush
