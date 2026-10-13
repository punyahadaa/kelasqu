<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — KelasQu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/kelasqu.css') }}" rel="stylesheet">
</head>
<body class="dashboard-page">
    <div class="dashboard-shell">
        <aside class="sidebar" id="sidebar">
            <a class="brand brand-sidebar" href="{{ route('dashboard') }}">
                <span class="brand-mark"><i class="bi bi-layers-half"></i></span>
                <span>Kelas<span class="brand-accent">Qu</span></span>
            </a>
            <div class="sidebar-label">MENU UTAMA</div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.classes') }}" class="sidebar-link {{ request()->routeIs('admin.classes') ? 'active' : '' }}">
                        <i class="bi bi-easel2"></i><span>Kelas</span>
                    </a>
                    <a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i><span>Kategori</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="bi bi-people"></i><span>Pengguna</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i><span>Laporan</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i><span>Pengaturan</span>
                    </a>
                @else
                    <a href="{{ route('user.classes') }}" class="sidebar-link {{ request()->routeIs('user.classes') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark"></i><span>Kelas Saya</span>
                    </a>
                    <a href="{{ route('user.materials') }}" class="sidebar-link {{ request()->routeIs('user.materials') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i><span>Materi</span>
                    </a>
                    <a href="{{ route('user.certificates') }}" class="sidebar-link {{ request()->routeIs('user.certificates') ? 'active' : '' }}">
                        <i class="bi bi-award"></i><span>Sertifikat</span>
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i><span>Profil</span>
                </a>
            </nav>
            <div class="sidebar-bottom">
                <div class="sidebar-help">
                    <span class="help-icon"><i class="bi bi-stars"></i></span>
                    <strong>Terus bertumbuh</strong>
                    <p>Langkah kecil hari ini, kemampuan baru esok hari.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="sidebar-link logout-link" type="submit"><i class="bi bi-box-arrow-left"></i><span>Keluar</span></button>
                </form>
            </div>
        </aside>

        <main class="main-area">
            <header class="topbar">
                <button class="menu-toggle" type="button" aria-label="Buka menu" onclick="document.getElementById('sidebar').classList.toggle('sidebar-open')">
                    <i class="bi bi-list"></i>
                </button>
                <div class="topbar-context">
                    <span class="context-eyebrow">RUANG BELAJAR DIGITAL</span>
                    <span class="context-title">@yield('page-heading', 'Dashboard')</span>
                </div>
                <div class="topbar-user">
                    <div class="topbar-user-copy">
                        <strong>{{ auth()->user()->name }}</strong>
                        <span>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Siswa' }}</span>
                    </div>
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                </div>
            </header>
            <div class="page-content">
                @yield('content')
            </div>
            <footer class="dashboard-footer">© {{ date('Y') }} KelasQu <span>•</span> Belajar, bertumbuh, dan melangkah lebih jauh.</footer>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
