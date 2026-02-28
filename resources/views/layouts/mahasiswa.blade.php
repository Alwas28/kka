<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Mahasiswa - Sistem KKA UM Kendari</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* NOTIFIKASI BELL */
        .notif-wrapper { position: relative; }
        .notif-bell { position: relative; }
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white;
            font-size: 10px; font-weight: 700;
            min-width: 18px; height: 18px;
            border-radius: 10px; display: flex;
            align-items: center; justify-content: center;
            padding: 0 4px; line-height: 1;
            border: 2px solid white;
        }
        .notif-dropdown {
            display: none; position: absolute;
            top: calc(100% + 10px); right: 0;
            width: 360px; max-height: 460px;
            background: white; border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,.18);
            border: 1px solid var(--gray-border);
            z-index: 9999; overflow: hidden;
            animation: notifIn .2s ease;
        }
        .notif-dropdown.active { display: block; }
        @keyframes notifIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:none; } }

        .notif-dropdown-header {
            display: flex; justify-content: space-between;
            align-items: center; padding: 14px 16px;
            border-bottom: 1px solid var(--gray-border);
        }
        .notif-dropdown-header strong { font-size: 14px; color: var(--text-primary); }
        .notif-read-all {
            background: none; border: none; cursor: pointer;
            font-size: 12px; color: var(--maroon-main);
            font-weight: 600; font-family: inherit;
        }
        .notif-read-all:hover { text-decoration: underline; }

        .notif-dropdown-body {
            overflow-y: auto; max-height: 380px;
        }
        .notif-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 12px 16px; text-decoration: none;
            border-bottom: 1px solid rgba(0,0,0,.04);
            transition: background .15s; cursor: pointer;
            position: relative;
        }
        .notif-item:hover { background: rgba(139,0,0,.03); }
        .notif-item.unread { background: rgba(59,130,246,.04); }
        .notif-icon {
            width: 34px; height: 34px; border-radius: 8px;
            background: rgba(107,114,128,.08);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0;
        }
        .notif-content { flex: 1; min-width: 0; }
        .notif-title { font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px; }
        .notif-msg   { font-size: 12px; color: var(--text-secondary); line-height: 1.4; margin-bottom: 4px; }
        .notif-time  { font-size: 11px; color: var(--text-secondary); opacity: .7; }
        .notif-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #3b82f6; flex-shrink: 0;
            margin-top: 6px;
        }
        .notif-empty {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 40px 20px; color: var(--text-secondary);
            gap: 8px;
        }
        .notif-empty i { font-size: 28px; color: var(--gray-border); }
        .notif-empty span { font-size: 13px; }

        @media (max-width: 480px) {
            .notif-dropdown { width: 300px; right: -60px; }
        }
    </style>
    @yield('css')
</head>
<body>

    <div class="container-main">
        <!-- SIDEBAR -->
        <div class="sidebar" id="sidebar">

            <div class="sidebar-header">
                <img src="{{ asset('img/logo.png') }}" alt="UM Kendari Logo" class="sidebar-logo-img">
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-title">Menu Utama</div>
                <a href="{{ route('mahasiswa.dashboard') }}" class="nav-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                @php $mhsLevel = Auth::guard('mahasiswa')->user()?->mahasiswa_level_id ?? 1; @endphp

                @if($mhsLevel >= 2)
                <a href="{{ route('mahasiswa.pendaftaran.form') }}" class="nav-item {{ request()->routeIs('mahasiswa.pendaftaran.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-file-pen"></i>
                    <span>Pendaftaran</span>
                </a>
                @endif

                @if($mhsLevel >= 6)
                <a href="{{ route('mahasiswa.pelaksanaan.index') }}" class="nav-item {{ request()->routeIs('mahasiswa.pelaksanaan.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-tasks"></i>
                    <span>Pelaksanaan</span>
                </a>
                @endif

                @yield('sidebar-menu')
            </nav>

            <div class="sidebar-footer">
                <form id="logout-form" action="{{ route('mahasiswa.logout') }}" method="post">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- HEADER -->
            <div class="header">
                <button class="hamburger-menu" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="header-left">
                    <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="header-brand">
                        <div class="header-brand-title">Portal Mahasiswa</div>
                        <div class="header-brand-subtitle">Kuliah Kerja Amaliah</div>
                    </div>
                </div>

                <div class="header-right">
                    @php
                        $mhs = Auth::guard('mahasiswa')->user();
                        $initials = collect(explode(' ', $mhs->nama))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                        $notifList = $mhs->notifikasi()->orderByDesc('created_at')->limit(20)->get();
                        $unreadCount = $mhs->unreadNotifikasi()->count();
                    @endphp

                    <div class="notif-wrapper">
                        <button class="icon-btn notif-bell" onclick="toggleNotifDropdown(event)">
                            <i class="fas fa-bell"></i>
                            @if($unreadCount > 0)
                                <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>
                        <div class="notif-dropdown" id="notifDropdown">
                            <div class="notif-dropdown-header">
                                <strong>Notifikasi</strong>
                                @if($unreadCount > 0)
                                    <form action="{{ route('mahasiswa.notifikasi.readAll') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="notif-read-all">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>
                            <div class="notif-dropdown-body">
                                @forelse($notifList as $notif)
                                <a href="{{ $notif->url ?? '#' }}"
                                   class="notif-item {{ $notif->isRead() ? '' : 'unread' }}"
                                   @if(!$notif->isRead())
                                   onclick="event.preventDefault(); document.getElementById('notif-read-{{ $notif->id }}').submit();"
                                   @endif
                                >
                                    <div class="notif-icon" style="color:{{ $notif->warna }};">
                                        <i class="fas {{ $notif->ikon }}"></i>
                                    </div>
                                    <div class="notif-content">
                                        <div class="notif-title">{{ $notif->judul }}</div>
                                        <div class="notif-msg">{{ Str::limit($notif->pesan, 80) }}</div>
                                        <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                    @if(!$notif->isRead())
                                        <div class="notif-dot"></div>
                                    @endif
                                </a>
                                @if(!$notif->isRead())
                                <form id="notif-read-{{ $notif->id }}" action="{{ route('mahasiswa.notifikasi.read', $notif->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ $notif->url ?? route('mahasiswa.dashboard') }}">
                                </form>
                                @endif
                                @empty
                                <div class="notif-empty">
                                    <i class="fas fa-bell-slash"></i>
                                    <span>Belum ada notifikasi</span>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="profile-wrapper">
                        <div class="user-profile" onclick="toggleProfileDropdown(event)">
                            <div class="profile-avatar">{{ $initials }}</div>
                            <div class="profile-info">
                                <div class="profile-name">{{ $mhs->nama }}</div>
                                <div class="profile-role">{{ $mhs->nim }}</div>
                            </div>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </div>
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="dropdown-header">
                                <div class="dropdown-avatar">{{ $initials }}</div>
                                <div class="dropdown-info">
                                    <div class="dropdown-name">{{ $mhs->nama }}</div>
                                    <div class="dropdown-email">{{ $mhs->email }}</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Keluar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @yield('konten')

            <!-- FOOTER -->
            <footer class="footer">
                <p>&copy; 2026 Sistem Informasi Manajemen KKA Universitas Muhammadiyah Kendari. All rights reserved.</p>
            </footer>
        </div>
    </div>

    @include('components.toast')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function toggleSubmenu(event, submenuId) {
            event.preventDefault();
            const submenu = document.getElementById(submenuId);
            const parentItem = event.currentTarget;

            document.querySelectorAll('.submenu.active').forEach(menu => {
                if (menu.id !== submenuId) {
                    menu.classList.remove('active');
                    menu.previousElementSibling.classList.remove('open');
                }
            });

            submenu.classList.toggle('active');
            parentItem.classList.toggle('open');
        }

        function closeSidebarMobile() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.remove('active');
            }
        }

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.querySelector('.hamburger-menu');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(event.target) &&
                !hamburger.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });

        function toggleProfileDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('profileDropdown');
            const profile = event.currentTarget;
            dropdown.classList.toggle('active');
            profile.classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profileWrapper = document.querySelector('.profile-wrapper');

            if (dropdown && !profileWrapper.contains(event.target)) {
                dropdown.classList.remove('active');
                document.querySelector('.user-profile').classList.remove('active');
            }
        });

        function openActiveSubmenu() {
            document.querySelectorAll('.submenu-item.active').forEach(item => {
                const parentSubmenu = item.closest('.submenu');
                if (parentSubmenu) {
                    parentSubmenu.classList.add('active');
                    const parentNav = parentSubmenu.previousElementSibling;
                    if (parentNav && parentNav.classList.contains('nav-item-has-children')) {
                        parentNav.classList.add('open');
                    }
                }
            });
        }

        // Notifikasi dropdown
        function toggleNotifDropdown(event) {
            event.stopPropagation();
            const dd = document.getElementById('notifDropdown');
            // Tutup profile dropdown jika terbuka
            document.getElementById('profileDropdown')?.classList.remove('active');
            document.querySelector('.user-profile')?.classList.remove('active');
            dd.classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            const notifWrapper = document.querySelector('.notif-wrapper');
            const dd = document.getElementById('notifDropdown');
            if (dd && notifWrapper && !notifWrapper.contains(event.target)) {
                dd.classList.remove('active');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            openActiveSubmenu();
        });
    </script>
    @yield('js')
</body>
</html>
