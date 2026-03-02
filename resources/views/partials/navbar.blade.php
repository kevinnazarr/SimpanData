<nav
    class="sticky top-0 z-40 w-full px-4 py-3 transition-all duration-300 bg-white/80 border-b border-gray-200 supports-backdrop-blur:bg-white/60 backdrop-blur-md">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <button id="mobileSidebarToggle"
                class="p-2 text-slate-500 rounded-xl hover:bg-slate-100 active:bg-slate-200 lg:hidden transition-all duration-200">
                <i class='text-2xl bx bx-menu'></i>
            </button>

            <div class="flex flex-col">
                <h1 class="text-xl font-bold tracking-tight text-slate-800">
                    @yield('title', 'Dashboard')
                </h1>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                    <span class="hidden sm:inline">SimpanData</span>
                    <span class="hidden sm:inline">•</span>
                    <span id="currentDate" class="text-primary font-bold"></span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-4">
            @php
                $notifs = $navbarNotifications ?? ['harian' => collect(), 'akhir' => null, 'total_count' => 0];
            @endphp
            @if(Auth::user()->role === 'peserta')
            <div class="relative" id="notificationDropdownContainer">
                <button id="notificationDropdownBtn"
                    class="relative flex items-center justify-center w-10 h-10 transition-all rounded-xl hover:bg-slate-100 text-slate-600 hover:text-primary active:scale-95 group">
                    <i class='text-2xl bx bx-bell'></i>
                    @if($notifs['total_count'] > 0)
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full animate-pulse"></span>
                    @endif
                </button>

                <div id="notificationDropdown"
                    class="fixed sm:absolute left-4 sm:left-auto right-4 sm:right-0 w-auto sm:w-80 top-[4.5rem] sm:top-full mt-2 origin-top-right bg-white border border-gray-100 shadow-2xl rounded-2xl ring-1 ring-black ring-opacity-5 focus:outline-none opacity-0 invisible transform scale-95 transition-all duration-200 z-50">
                    <div class="p-4 border-b border-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                            @if($notifs['total_count'] > 0)
                                <span class="px-2 py-0.5 text-[10px] font-bold text-red-600 bg-red-50 rounded-full border border-red-100">
                                    {{ $notifs['total_count'] }} Perlu Revisi
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 p-2">
                        @if($notifs['total_count'] > 0)
                            <div class="space-y-1">
                                @if($notifs['akhir'])
                                    <a href="{{ route('peserta.laporan.akhir') }}" class="flex flex-col p-3 transition-colors rounded-xl hover:bg-red-50 group border border-transparent hover:border-red-100 bg-red-50/30">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="flex items-center justify-center w-8 h-8 text-red-600 bg-white rounded-lg shadow-sm border border-red-100">
                                                <i class='bx bxs-file-pdf'></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs font-bold text-slate-800 truncate">Revisi Laporan Akhir</p>
                                                <p class="text-[10px] text-slate-400">Baru saja</p>
                                            </div>
                                        </div>
                                        <div class="p-2 text-[11px] leading-relaxed text-red-700 bg-white/50 rounded-lg italic max-h-24 overflow-y-auto scrollbar-thin">
                                            {{ $notifs['akhir']->catatan_admin }}
                                        </div>
                                    </a>
                                @endif

                                @foreach($notifs['harian'] as $notif)
                                    <a href="{{ route('peserta.laporan.edit', $notif->id) }}" class="flex flex-col p-3 transition-colors rounded-xl hover:bg-amber-50 group border border-transparent hover:border-amber-100 bg-amber-50/30">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="flex items-center justify-center w-8 h-8 text-amber-600 bg-white rounded-lg shadow-sm border border-amber-100">
                                                <i class='bx bx-revision'></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs font-bold text-slate-800 truncate">Revisi: {{ $notif->judul }}</p>
                                                <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($notif->tanggal_laporan)->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="p-2 text-[11px] leading-relaxed text-amber-700 bg-white/50 rounded-lg italic max-h-24 overflow-y-auto scrollbar-thin">
                                            {{ $notif->catatan_admin }}
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 text-slate-300">
                                    <i class='text-3xl bx bx-bell-off'></i>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 mb-1">Sudah Beres!</h4>
                                <p class="text-[10px] text-slate-500">Tidak ada laporan yang perlu direvisi saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="relative" id="profileDropdownContainer">
                @php
                    /** @var \App\Models\User $user */
                    $user = Auth::user();
                    $peserta = $user->peserta ?? null;
                    $role = $user->role ?? 'User';
                    $profilePhoto = ($role === 'peserta' && $peserta) ? $peserta->foto : null;
                    $displayName = $peserta->nama ?? $user->name ?? $user->username ?? 'User';
                    $initial = strtoupper(substr($displayName, 0, 1));
                @endphp
                <button id="profileDropdownBtn"
                    class="flex items-center gap-3 pl-1 pr-1 sm:pr-4 py-1 rounded-full sm:rounded-xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-200 group">
                    <div class="relative flex-shrink-0 w-9 h-9">
                        @if ($profilePhoto)
                            <img src="{{ asset('storage/' . $profilePhoto) }}" alt="Profile"
                                class="object-cover w-full h-full shadow-md rounded-full ring-2 ring-white group-hover:shadow-lg transition-all">
                        @else
                            <div
                                class="flex items-center justify-center w-full h-full font-bold text-white rounded-full shadow-md bg-gradient-to-br from-primary to-blue-600 ring-2 ring-white group-hover:shadow-lg transition-all text-xs">
                                {{ $initial }}
                            </div>
                        @endif
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="hidden text-left sm:block">
                        <p class="text-sm font-bold leading-tight text-slate-700 group-hover:text-primary transition-colors">
                            {{ Str::limit($user->username ?? 'User', 15) }}
                        </p>
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                            {{ $role }}
                        </p>
                    </div>
                    <i class='hidden text-slate-400 bx bx-chevron-down sm:block group-hover:text-slate-600'></i>
                </button>

                <div id="profileDropdown"
                    class="absolute right-0 w-48 mt-2 origin-top-right bg-white border border-gray-100 shadow-xl rounded-2xl ring-1 ring-black ring-opacity-5 focus:outline-none opacity-0 invisible transform scale-95 transition-all duration-200">
                    <div class="p-1.5">
                        <div class="px-3 py-2 border-b border-gray-50 bg-gray-50/50 rounded-t-xl mb-1 sm:hidden">
                            <p class="text-sm font-bold text-slate-700 truncate">{{ $user->name ?? 'User' }}</p>
                            <p class="text-[10px] font-bold text-slate-500 uppercase">{{ $role }}</p>
                        </div>
                        <a href="{{ $role === 'admin' ? route('admin.profile.index') : route('peserta.profil') }}"
                            class="flex items-center w-full px-3 py-2 text-sm font-medium text-slate-600 rounded-xl hover:bg-blue-50 hover:text-primary group transition-colors">
                            <i class='mr-2 text-lg bx bx-user group-hover:text-primary'></i>
                            Profil Saya
                        </a>
                        <a href="{{ $role === 'admin' ? route('admin.settings.index') : route('peserta.settings.index') }}"
                            class="flex items-center w-full px-3 py-2 text-sm font-medium text-slate-600 rounded-xl hover:bg-blue-50 hover:text-primary group transition-colors">
                            <i class='mr-2 text-lg bx bx-cog group-hover:text-primary'></i>
                            Pengaturan
                        </a>
                        <div class="my-1 border-t border-gray-100"></div>
                        <button type="button" id="navbarLogoutBtn"
                            class="flex items-center w-full px-3 py-2 text-sm font-medium text-red-600 rounded-xl hover:bg-red-50 group transition-colors">
                            <i class='mr-2 text-lg bx bx-log-out group-hover:text-red-600'></i>
                            Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateElement = document.getElementById('currentDate');
        if (dateElement) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date();
            dateElement.textContent = today.toLocaleDateString('id-ID', options);
        }

        const notificationBtn = document.getElementById('notificationDropdownBtn');
        const notificationMenu = document.getElementById('notificationDropdown');

        if (notificationBtn && notificationMenu) {
            notificationBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                // Close profile dropdown if open
                if (typeof closeDropdown === 'function') closeDropdown();
                
                const isOpen = !notificationMenu.classList.contains('invisible');
                if (isOpen) {
                    closeNotificationDropdown();
                } else {
                    openNotificationDropdown();
                }
            });

            document.addEventListener('click', (e) => {
                if (!notificationMenu.contains(e.target) && !notificationBtn.contains(e.target)) {
                    closeNotificationDropdown();
                }
            });

            function openNotificationDropdown() {
                notificationMenu.classList.remove('invisible', 'opacity-0', 'scale-95');
                notificationMenu.classList.add('visible', 'opacity-100', 'scale-100');
            }

            function closeNotificationDropdown() {
                notificationMenu.classList.add('invisible', 'opacity-0', 'scale-95');
                notificationMenu.classList.remove('visible', 'opacity-100', 'scale-100');
            }
        }

        const dropdownBtn = document.getElementById('profileDropdownBtn');
        const dropdownMenu = document.getElementById('profileDropdown');

        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (typeof closeNotificationDropdown === 'function') closeNotificationDropdown();
                
                const isOpen = !dropdownMenu.classList.contains('invisible');

                if (isOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });

            document.addEventListener('click', (e) => {
                if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                    closeDropdown();
                }
            });

            function openDropdown() {
                dropdownMenu.classList.remove('invisible', 'opacity-0', 'scale-95');
                dropdownMenu.classList.add('visible', 'opacity-100', 'scale-100');
            }

            function closeDropdown() {
                dropdownMenu.classList.add('invisible', 'opacity-0', 'scale-95');
                dropdownMenu.classList.remove('visible', 'opacity-100', 'scale-100');
            }

            const navbarLogoutBtn = document.getElementById('navbarLogoutBtn');
            const logoutModal = document.getElementById('logout-modal');
            
            if (navbarLogoutBtn && logoutModal) {
                navbarLogoutBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    closeDropdown();
                    logoutModal.classList.add('show');
                });
            }
        }
    });
</script>
