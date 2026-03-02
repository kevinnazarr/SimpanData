@php
    /** @var \App\Models\User $user */
    $user = Auth::user();
@endphp
<div class="sidebar" id="sidebar">
    <div class="logo-details">
        @if($user->isAdmin())
            <div class="logo_name" id="logo-link" data-href="{{ route('admin.dashboard') }}">SipanData</div>
        @elseif($user->isPeserta())
            <div class="logo_name" id="logo-link" data-href="{{ route('peserta.dashboard') }}">SipanData</div>
        @else
            <div class="logo_name" id="logo-link" data-href="#">SipanData</div>
        @endif
        <button type="button" id="btn">
            <i class='bx bx-menu'></i>
        </button>
    </div>

    <ul class="nav-list">
        @if($user->isAdmin())
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="{{ route('admin.peserta.index') }}" class="{{ request()->routeIs('admin.peserta.*') ? 'active' : '' }}">
                    <i class='bx bx-user'></i>
                    <span class="links_name">Data Peserta</span>
                </a>
                <span class="tooltip">Data Peserta</span>
            </li>
            <li>
                <a href="{{ route('admin.absensi.index') }}" class="{{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                    <i class='bx bx-building'></i>
                    <span class="links_name">Data Absensi</span>
                </a>
                <span class="tooltip">Data Absensi</span>
            </li>
            <li>
                <a href="{{ route('admin.user.index') }}" class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                    <i class='bx bx-user-check'></i>
                    <span class="links_name">Data User</span>
                </a>
                <span class="tooltip">Data User</span>
            </li>
            <li>
                <a href="{{ route('admin.partners.index') }}" class="{{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                    <i class='bx bx-buildings'></i>
                    <span class="links_name">Data Partner</span>
                </a>
                <span class="tooltip">Data Partner</span>
            </li>
            <li class="relative group dropdown-item">
                <a href="javascript:void(0)" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }} dropdown-toggle">
                    <i class='bx bx-file'></i>
                    <span class="links_name">Laporan</span>
                    <i class='bx bx-chevron-down dropdown-icon'></i>
                </a>
                <span class="tooltip">Laporan</span>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                            <span class="links_name">Laporan Harian</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.laporan.akhir.index') }}"
                            class="{{ request()->routeIs('admin.laporan.akhir.*') ? 'active' : '' }}">
                            <span class="links_name">Laporan Akhir</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('admin.penilaian.index') }}" class="{{ request()->routeIs('admin.penilaian.*') ? 'active' : '' }}">
                    <i class='bx bx-show-alt'></i>
                    <span class="links_name">Penilaian</span>
                </a>
                <span class="tooltip">Penilaian</span>
            </li>
            <li>
                <a href="{{ route('admin.arsip.index') }}" class="{{ request()->routeIs('admin.arsip.*') ? 'active' : '' }}">
                    <i class='bx bx-archive'></i>
                    <span class="links_name">Arsip</span>
                </a>
                <span class="tooltip">Arsip</span>
            </li>
        @elseif($user->isPeserta())
            <li>
                <a href="{{ route('peserta.dashboard') }}" class="{{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="{{ route('peserta.profil') }}" class="{{ request()->routeIs('peserta.profil') ? 'active' : '' }}">
                    <i class='bx bx-user'></i>
                    <span class="links_name">Data Diri</span>
                </a>
                <span class="tooltip">Data Diri</span>
            </li>
            <li>
                <a href="{{ route('peserta.absensi') }}" class="{{ request()->routeIs('peserta.absensi') ? 'active' : '' }}">
                    <i class='bx bx-building'></i>
                    <span class="links_name">Absensi</span>
                </a>
                <span class="tooltip">Absensi</span>
            </li>
            <li class="relative group dropdown-item">
                <a href="javascript:void(0)" class="{{ request()->routeIs('peserta.laporan.*') ? 'active' : '' }} dropdown-toggle">
                    <i class='bx bx-file'></i>
                    <span class="links_name">Laporan</span>
                    <i class='bx bx-chevron-down dropdown-icon'></i>
                </a>
                <span class="tooltip">Laporan</span>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('peserta.laporan.index') }}" class="{{ request()->routeIs('peserta.laporan.index') ? 'active' : '' }}">
                            <span class="links_name">Laporan Harian</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('peserta.laporan.akhir') }}" class="{{ request()->routeIs('peserta.laporan.akhir') ? 'active' : '' }}">
                            <span class="links_name">Laporan Akhir</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('peserta.penilaian') }}" class="{{ request()->routeIs('peserta.penilaian') ? 'active' : '' }}">
                    <i class='bx bx-show-alt'></i>
                    <span class="links_name">Penilaian</span>
                </a>
                <span class="tooltip">Penilaian</span>
            </li>
            <li>
                <a href="{{ route('peserta.feedback') }}" class="{{ request()->routeIs('peserta.feedback') ? 'active' : '' }}">
                    <i class='bx bx-message-square-detail'></i>
                    <span class="links_name">Feedback</span>
                </a>
                <span class="tooltip">Feedback</span>
            </li>
        @endif
    </ul>

    <div class="profile-section">
        <div class="profile-content">
            <div class="profile-details">
                <div class="profile-info">
                    <div class="name">{{ $user->username }}</div>
                    <div class="job">{{ $user->email }}</div>
                </div>
            </div>
            <button class="logout-btn" id="log_out">
                <i class='bx bx-log-out'></i>
                <span class="logout-tooltip">Logout</span>
            </button>
        </div>
    </div>
</div>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div id="logout-modal" class="logout-modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="icon-box">
                <i class='bx bx-log-out'></i>
            </div>
            <h3 class="modal-title">Konfirmasi Logout</h3>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin keluar dari sistem?</p>
        </div>
        <div class="modal-footer">
            <button type="button" id="cancel-logout" class="btn-cancel">Batal</button>
            <button type="button" id="confirm-logout" class="btn-confirm">Logout</button>
        </div>
    </div>
</div>

<form id="logout-form-hidden" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@vite(['resources/css/sidebar.css', 'resources/js/sidebar.js'])
