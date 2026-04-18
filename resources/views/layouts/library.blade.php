<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Buku')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
:root {
            --sidebar-width: 340px;
            --sidebar-bg: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            --sidebar-active: rgba(255, 255, 255, 0.2);
        }

        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
                font-size: 12px !important;
                line-height: 1.4 !important;
            }

            .sidebar,
            .sidebar-toggle,
            .sidebar-overlay {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                margin: 0 !important;
                padding: 1rem !important;
                width: 100% !important;
                min-height: auto !important;
                background: white !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            table {
                width: 100% !important;
                font-size: 11px !important;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
                margin-bottom: 1rem !important;
            }

            .d-print-none {
                display: none !important;
            }

            .pagination,
            nav[role="navigation"] {
                display: none !important;
            }
        }

        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1045;
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .nav-link {
            border-radius: 0.5rem;
            margin: 0 1rem 0 0;
            transition: all 0.3s ease;
        }

        .sidebar ul li {
            margin-bottom: 0.5rem;
        }
        .sidebar ul li:last-child {
            margin-bottom: 1rem;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--sidebar-active);
            transform: translateX(5px);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8f9fa;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .sidebar-toggle {
            position: fixed;
            top: 20px;
            left: 25px;
            z-index: 1050;
            display: none;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1041;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 991.98px) {
            .sidebar-toggle {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
            border-radius: 1rem;
        }

        /* Clean Sidebar Header Improvements */
        .header-icon {
            font-size: 1.8rem !important;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 0.6rem;
            transition: all 0.3s ease;
        }

        .card-header-brand {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            cursor: default;
        }

        .card-header-brand:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .header-icon:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .header-title {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .school-name {
            font-size: 0.85rem;
            font-weight: 500;
            opacity: 0.9;
            margin: 0 0 0.25rem 0;
        }

        /* Header container - minimal overrides */
        #sidebar > .d-flex {
            gap: 1rem;
            align-items: flex-start;
        }

        #sidebar > .d-flex > .flex-column {
            flex: 1;
            min-width: 0;
            flex-direction: column !important;
            align-items: center;
            text-align: center;
            gap: 0.25rem;
        }

        #sidebar > .d-flex .d-flex.align-items-center {
            flex-wrap: nowrap;
            gap: 0.5rem;
        }

        @media (max-width: 576px) {
            .header-icon {
                font-size: 1.5rem !important;
                margin-right: 0.5rem;
            }
        }

        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Toggle -->
    <button class="sidebar-toggle btn btn-primary shadow-lg" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar bg-primary text-white p-3" id="sidebar">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <div class="card-header-brand p-3 rounded-3 shadow-sm border-0 d-flex align-items-start gap-3">
                <i class="bi bi-book-half header-icon mt-1 flex-shrink-0"></i>
                <div>
                    <h4 class="mb-1 fw-bold header-title mb-0">Sistem Peminjaman Buku</h4>
                    <p class="mb-0 small text-white-50 school-name">SMK AL-FALAH 2 NAGREG</p>
                </div>
            </div>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white 
                    @if (auth()->user()->role === 'admin' && request()->routeIs('dashboard')) active @elseif(auth()->user()->role === 'siswa' && request()->routeIs('dashboard.siswa')) active @endif"
                    href="{{ auth()->user()->role === 'admin' ? route('dashboard') : route('dashboard.siswa') }}">
                    <i class="bi bi-house-door fs-5 me-3"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white @if (request()->routeIs('books.*')) active @endif"
                    href="{{ route('books.index') }}">
                    <i class="bi bi-book fs-5 me-3"></i> Data Buku
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white @if (request()->routeIs('loans.create')) active @endif"
                    href="{{ route('loans.create') }}">
                    <i class="bi bi-clipboard-check fs-5 me-3"></i> Peminjaman
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white @if (request()->routeIs('loans.active')) active @endif"
                    href="{{ route('loans.active') }}">
                    <i class="bi bi-clock fs-5 me-3"></i> Pinjaman Aktif
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white @if (request()->routeIs('loans.history')) active @endif"
                    href="{{ route('loans.history') }}">
                    <i class="bi bi-clock-history fs-5 me-3"></i> Riwayat Peminjaman
                </a>
            </li>

            @if (auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link text-white @if (request()->routeIs('reports.*')) active @endif"
                        href="{{ route('reports.index') }}">
                        <i class="bi bi-bar-chart fs-5 me-3"></i> Laporan
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link text-white @if (request()->routeIs('profile.edit')) active @endif"
                    href="{{ route('profile.edit') }}">
                    <i class="bi bi-gear fs-5 me-3"></i> Pengaturan
                </a>
            </li>

            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit"
                        class="nav-link text-white btn btn-link p-2 border-0 border-danger text-start w-100 text-decoration-none bg-danger rounded "
                        onclick="return confirm('Yakin ingin keluar?')"><i
                            class="bi bi-box-arrow-right fs-5 me-3"></i>Keluar
                    </button>
                </form>
            </li>

        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content p-4 p-md-5" id="mainContent">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Initialize sidebar state on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 992) {
                sidebar.classList.add('collapsed');
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            const body = document.body;

            const isCollapsed = sidebar.classList.contains('collapsed');

            if (isCollapsed) {
                // Open sidebar
                sidebar.classList.remove('collapsed');
                overlay.classList.add('show');
                body.style.overflow = 'hidden';
                // Icon to close
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('bi-list');
                    icon.classList.add('bi-x');
                }
            } else {
                // Close sidebar
                sidebar.classList.add('collapsed');
                overlay.classList.remove('show');
                body.style.overflow = '';
                // Icon to hamburger
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('bi-x');
                    icon.classList.add('bi-list');
                }
            }
        }

        // Close sidebar on overlay click (mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.querySelector('.sidebar-toggle');

            if (window.innerWidth < 992 && overlay.classList.contains('show') &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target)) {
                toggleSidebar();
            }
        });

        // Auto-adjust on resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const body = document.body;
            const toggleBtn = document.querySelector('.sidebar-toggle');

            if (window.innerWidth >= 992) {
                sidebar.classList.remove('collapsed');
                overlay.classList.remove('show');
                body.style.overflow = '';
                const icon = toggleBtn ? toggleBtn.querySelector('i') : null;
                if (icon) {
                    icon.classList.remove('bi-x');
                    icon.classList.add('bi-list');
                }
            } else {
                // On mobile resize, reset to closed
                sidebar.classList.add('collapsed');
                overlay.classList.remove('show');
                body.style.overflow = '';
            }
        });
    </script>
</body>

</html>
