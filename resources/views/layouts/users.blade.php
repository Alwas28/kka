<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Sistem Akademik UM Kendari</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                <a href="{{ route('dashboard') }}" class="nav-item" data-page="index" onclick="closeSidebarMobile()">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Menu dengan Submenu -->
                <div class="nav-item nav-item-has-children" onclick="toggleSubmenu(event, 'submenu-akademik')">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-book" style="width: 20px; margin-right: 12px; font-size: 16px;"></i>
                        <span>Master Data</span>
                    </div>
                    <i class="fas fa-chevron-down toggle-arrow"></i>
                </div>
                <div class="submenu" id="submenu-akademik">
                    @if(auth()->user()->hasAccess('lihat.jenis-kka'))
                    <a href="{{ route('jenis-kka.index') }}" class="submenu-item {{ request()->routeIs('jenis-kka.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-list"></i>
                        <span>Jenis KKA</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.tahun'))
                    <a href="{{ route('tahun.index') }}" class="submenu-item {{ request()->routeIs('tahun.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Tahun</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.periode'))
                    <a href="{{ route('periode.index') }}" class="submenu-item {{ request()->routeIs('periode.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-calendar-check"></i>
                        <span>Periode</span>
                    </a>
                    @endif
                    
                    <a href="{{ route('fakultas.index') }}" class="submenu-item {{ request()->routeIs('fakultas.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-building"></i>
                        <span>Fakultas</span>
                    </a>
                    
                    <a href="{{ route('program-studi.index') }}" class="submenu-item {{ request()->routeIs('program-studi.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Program Studi</span>
                    </a>
                    
                    @if(auth()->user()->hasAccess('lihat.pegawai'))
                    <a href="{{ route('pegawai.index') }}" class="submenu-item {{ request()->routeIs('pegawai.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-user-tie"></i>
                        <span>Pegawai</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.provinsi'))
                    <a href="{{ route('provinsi.index') }}" class="submenu-item {{ request()->routeIs('provinsi.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-map"></i>
                        <span>Provinsi</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.kabupaten'))
                    <a href="{{ route('kabupaten.index') }}" class="submenu-item {{ request()->routeIs('kabupaten.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-city"></i>
                        <span>Kabupaten/Kota</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.kecamatan'))
                    <a href="{{ route('kecamatan.index') }}" class="submenu-item {{ request()->routeIs('kecamatan.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-map-signs"></i>
                        <span>Kecamatan</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.desa'))
                    <a href="{{ route('desa.index') }}" class="submenu-item {{ request()->routeIs('desa.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-home"></i>
                        <span>Desa/Kelurahan</span>
                    </a>
                    @endif
                </div>

                <!-- Menu dengan Submenu -->
                <div class="nav-item nav-item-has-children" onclick="toggleSubmenu(event, 'submenu-mahasiswa')">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-users" style="width: 20px; margin-right: 12px; font-size: 16px;"></i>
                        <span>Manajemen Users</span>
                    </div>
                    <i class="fas fa-chevron-down toggle-arrow"></i>
                </div>
                <div class="submenu" id="submenu-mahasiswa">
                    @if(auth()->user()->hasAccess('lihat.user'))
                    <a href="{{ route('users.index') }}" class="submenu-item {{ request()->routeIs('users.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-user"></i>
                        <span>Users</span>
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAccess('lihat.role'))
                        <a href="{{ route('role.index') }}" class="submenu-item {{ request()->routeIs('role.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                            <i class="fas fa-user-tag"></i>
                            <span>Role</span>
                        </a>
                    @endif
                    <a href="{{ route('access.index') }}" class="submenu-item {{ request()->routeIs('access.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-shield-alt"></i>
                        <span>Manajemen Access</span>
                    </a>
                    <a href="{{ route('user-role.index') }}" class="submenu-item {{ request()->routeIs('user-role.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-id-badge"></i>
                        <span>User Roles</span>
                    </a>
                </div>

                <!-- Menu dengan Submenu -->
                <div class="nav-item nav-item-has-children" onclick="toggleSubmenu(event, 'submenu-dosen')">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-cogs" style="width: 20px; margin-right: 12px; font-size: 16px;"></i>
                        <span>Setup Kegiatan</span>
                    </div>
                    <i class="fas fa-chevron-down toggle-arrow"></i>
                </div>
                <div class="submenu" id="submenu-dosen">
                    <a href="{{ route('kegiatan.create') }}" class="submenu-item {{ request()->routeIs('kegiatan.create') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Kegiatan</span>
                    </a>
                    @if(auth()->user()->hasAccess('lihat.kegiatan'))
                    <a href="{{ route('kegiatan.berlangsung') }}" class="submenu-item {{ request()->routeIs('kegiatan.berlangsung') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-clock"></i>
                        <span>Sedang Dilaksanakan</span>
                    </a>
                    <a href="{{ route('kegiatan.selesai') }}" class="submenu-item {{ request()->routeIs('kegiatan.selesai') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-check-circle"></i>
                        <span>Selesai Dilaksanakan</span>
                    </a>
                    @endif
                </div>

                
                <div class="nav-section-title">Survey</div>
                
                <!-- Menu Pendaftaran -->
                <div class="nav-item nav-item-has-children" onclick="toggleSubmenu(event, 'submenu-survey')">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-map-marker-alt" style="width: 20px; margin-right: 12px; font-size: 16px;"></i>
                        <span>Survey Lokasi</span>
                    </div>
                    <i class="fas fa-chevron-down toggle-arrow"></i>
                </div>
                <div class="submenu" id="submenu-survey">
                    @if(auth()->user()->hasAccess('lihat.survey'))
                    <a href="{{ route('survey.index') }}" class="submenu-item {{ request()->routeIs('survey.index') || request()->routeIs('survey.isi') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-users"></i>
                        <span>TIM Survey</span>
                    </a>
                    <a href="{{ route('survey.hasil') }}" class="submenu-item {{ request()->routeIs('survey.hasil') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Hasil Survey</span>
                    </a>
                </div>

                
                @if(auth()->user()->hasAccess('lihat.dosen-pembimbing'))
                <div class="nav-section-title">DPL</div>

                <a href="{{ route('dosen-pembimbing.index') }}" class="nav-item {{ request()->routeIs('dosen-pembimbing.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-user-graduate"></i>
                    <span>Dosen Pembimbing</span>
                </a>
                @endif

                <div class="nav-section-title">Akademik</div>
                
                <!-- Menu Pendaftaran -->
                <div class="nav-item nav-item-has-children" onclick="toggleSubmenu(event, 'submenu-nilai')">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-graduation-cap" style="width: 20px; margin-right: 12px; font-size: 16px;"></i>
                        <span>Pendaftaran</span>
                    </div>
                    <i class="fas fa-chevron-down toggle-arrow"></i>
                </div>
                <div class="submenu" id="submenu-nilai">
                    @if(auth()->user()->hasAccess('lihat.registrasi'))
                    <a href="{{ route('registrasi.index') }}" class="submenu-item {{ request()->routeIs('registrasi.index') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-user-check"></i>
                        <span>Registrasi</span>
                    </a>
                    <a href="{{ route('registrasi.disetujui') }}" class="submenu-item {{ request()->routeIs('registrasi.disetujui') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-check-circle"></i>
                        <span>Disetujui Prodi</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.dokumen-pembayaran'))
                    <a href="{{ route('dokumen.pembayaran') }}" class="submenu-item {{ request()->routeIs('dokumen.pembayaran*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Bukti Pembayaran</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.sertifikat'))
                    <a href="{{ route('dokumen.sertifikat') }}" class="submenu-item {{ request()->routeIs('dokumen.sertifikat*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-quran"></i>
                        <span>Sertifikat Baca Quran</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.dokumen-lainnya'))
                    <a href="{{ route('dokumen.lainnya') }}" class="submenu-item {{ request()->routeIs('dokumen.lainnya*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-file-alt"></i>
                        <span>Verifikasi Dokumen</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('lihat.terverifikasi'))
                    <a href="{{ route('dokumen.terverifikasi') }}" class="submenu-item {{ request()->routeIs('dokumen.terverifikasi*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-check-double"></i>
                        <span>Terverifikasi</span>
                    </a>
                    @endif
                </div>

                <!-- Menu Pelaksanaan -->
                <div class="nav-item nav-item-has-children" onclick="toggleSubmenu(event, 'submenu-pelaksanaan')">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-tasks" style="width: 20px; margin-right: 12px; font-size: 16px;"></i>
                        <span>Pelaksanaan</span>
                    </div>
                    <i class="fas fa-chevron-down toggle-arrow"></i>
                </div>
                <div class="submenu" id="submenu-pelaksanaan">
                    @endif
                    @if(auth()->user()->hasAccess('lihat.data-lokasi'))
                    <a href="{{ route('survey.data-lokasi') }}" class="submenu-item {{ request()->routeIs('survey.data-lokasi') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Data Lokasi</span>
                    </a>
                    @endif
                    <a href="{{ route('peserta.index') }}" class="submenu-item {{ request()->routeIs('peserta.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-user-graduate"></i>
                        <span>Peserta</span>
                    </a>
                    <a href="{{ route('dpl.index') }}" class="submenu-item {{ request()->routeIs('dpl.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Dosen Pembimbing</span>
                    </a>
                    <a href="#" class="submenu-item" onclick="closeSidebarMobile()">
                        <i class="fas fa-user-tie"></i>
                        <span>Supervisor</span>
                    </a>
                </div>


                <div class="nav-section-title">Data KKA</div>

                @if(auth()->user()->hasAccess('lihat.kegiatan'))
                <a href="{{ route('kegiatan.index') }}" class="nav-item {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Kegiatan</span>
                </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <form id="logout-form" action="{{ route('logout') }}" method="post">
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
                        <div class="header-brand-title">Sistem Informasi</div>
                        <div class="header-brand-subtitle">Kuliah Kerja Amaliah</div>
                    </div>
                </div>

                <div class="header-right">
                    <div class="header-icons">
                        <button class="icon-btn">
                            <i class="fas fa-bell"></i>
                        </button>
                    </div>

                    <div class="profile-wrapper">
                        <div class="user-profile" onclick="toggleProfileDropdown(event)">
                            <div class="profile-avatar">KKA</div>
                            <div class="profile-info">
                                <div class="profile-name">{{ Auth::user()->name }}</div>
                                <div class="profile-role">{{ Auth::user()->email }}</div>
                            </div>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </div>
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="dropdown-header">
                                <div class="dropdown-avatar">KKA</div>
                                <div class="dropdown-info">
                                    <div class="dropdown-name">{{ Auth::user()->name }}</div>
                                    <div class="dropdown-email">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-key"></i>
                                <span>Ubah Password</span>
                            </a>
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
            
            // Close other submenus
            document.querySelectorAll('.submenu.active').forEach(menu => {
                if (menu.id !== submenuId) {
                    menu.classList.remove('active');
                    menu.previousElementSibling.classList.remove('open');
                }
            });
            
            // Toggle current submenu
            submenu.classList.toggle('active');
            parentItem.classList.toggle('open');
        }

        function closeSidebarMobile() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.remove('active');
            }
        }

        function logout() {
            document.getElementById('logout-form').submit();
        }

        // Close sidebar when clicking outside
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

        // Close profile dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profileWrapper = document.querySelector('.profile-wrapper');

            if (dropdown && !profileWrapper.contains(event.target)) {
                dropdown.classList.remove('active');
                document.querySelector('.user-profile').classList.remove('active');
            }
        });

        // Set active menu based on current page
        function setActiveMenu() {
            const currentPage = window.location.pathname.split('/').pop().replace('.html', '') || 'index';

            // Remove all active classes
            document.querySelectorAll('.nav-item.active, .submenu-item.active').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelectorAll('.nav-item.open').forEach(item => {
                item.classList.remove('open');
            });
            document.querySelectorAll('.submenu.active').forEach(submenu => {
                submenu.classList.remove('active');
            });

            // Find and activate the current menu item
            const activeItem = document.querySelector(`[data-page="${currentPage}"]`);
            if (activeItem) {
                activeItem.classList.add('active');

                // If it's a submenu item, open the parent submenu
                const parentSubmenu = activeItem.closest('.submenu');
                if (parentSubmenu) {
                    parentSubmenu.classList.add('active');
                    const parentNav = parentSubmenu.previousElementSibling;
                    if (parentNav && parentNav.classList.contains('nav-item-has-children')) {
                        parentNav.classList.add('open');
                    }
                }
            }
        }

        // Open parent submenu of any .active submenu-item (set via Blade)
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

        // Run on page load
        document.addEventListener('DOMContentLoaded', function() {
            setActiveMenu();
            openActiveSubmenu();
        });
    </script>
    @yield('js')
</body>
</html>