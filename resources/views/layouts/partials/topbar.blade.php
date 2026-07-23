@php
    $mentorName ??= auth()->user()->name ?? 'User';
    $mentorInitials ??= 'U';
    $mentorNotifications ??= [];
@endphp

<header
    class="flex items-center justify-between px-5 py-[18px] md:px-7 md:py-[22px] border-b border-[#1A1C1E]/[0.12] bg-[#F7F5F2]">
    <p class="text-[1rem] font-bold m-0">{{ $mentorName }}</p>

    <div class="relative flex items-center gap-3">
        <button class="relative border-0 bg-transparent cursor-pointer text-[1.05rem] p-1" id="notificationToggle"
            type="button" aria-haspopup="true" aria-expanded="false" aria-label="Notifikasi">
            🔔
            @if (count($mentorNotifications) > 0)
                <span
                    class="absolute -top-1 -right-1 w-[17px] h-[17px] rounded-full bg-[#B8422E] text-[#F7F5F2] text-[0.65rem] font-['Space_Grotesk'] inline-flex items-center justify-center">
                    {{ count($mentorNotifications) }}
                </span>
            @endif
        </button>

        <div class="hidden absolute top-[calc(100%+10px)] right-0 w-[260px] bg-[#FCFAF7] border border-[#1A1C1E]/[0.12] z-20"
            id="notificationPanel">
            <div class="px-3.5 py-2.5 font-bold border-b border-[#1A1C1E]/[0.12]">Notifikasi</div>

            @forelse ($mentorNotifications as $item)
                <div class="px-3.5 py-2.5 border-b border-[#1A1C1E]/[0.12] last:border-b-0">
                    <p class="m-0">{{ $item['message'] }}</p>
                    <div class="mt-1 text-[0.75rem] text-[#6C7278]">{{ $item['time'] }}</div>
                </div>
            @empty
                <div class="px-3.5 py-2.5">
                    <p class="m-0">Tidak ada notifikasi baru.</p>
                </div>
            @endforelse
        </div>

        <button
            class="w-[34px] h-[34px] rounded-full bg-[#1A1C1E] text-[#F7F5F2] inline-flex items-center justify-center text-[0.72rem] font-['Space_Grotesk'] tracking-[0.08em] border-0 p-0 cursor-pointer focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#1A1C1E] focus-visible:outline-offset-2"
            id="userMenuToggle" type="button" aria-haspopup="true" aria-expanded="false"
            aria-label="Menu akun, {{ $mentorName }}">
            {{ $mentorInitials }}
        </button>

        <div class="hidden absolute top-[calc(100%+10px)] right-0 w-[260px] bg-[#FCFAF7] border border-[#1A1C1E]/[0.12] z-20"
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
