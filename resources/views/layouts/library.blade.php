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
            --sidebar-width: 280px;
            --sidebar-bg: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            --sidebar-active: rgba(255,255,255,0.2);
        }
        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1040;
            transition: transform 0.3s ease;
        }
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        .nav-link {
            border-radius: 0.5rem;
            margin: 0.25rem 1rem;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
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
            left: 20px;
            z-index: 1050;
            display: none;
        }
        @media (max-width: 991.98px) {
            .sidebar-toggle {
                display: block;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            border: none;
            border-radius: 1rem;
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

    <!-- Sidebar -->
    <nav class="sidebar bg-primary text-white p-3" id="sidebar">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <div class="d-flex align-items-center">
                <i class="bi bi-book-half fs-3 me-3"></i>
                <h4 class="mb-0 fw-bold">Perpustakaan</h4>
            </div>
            <button class="btn-close btn-close-white d-md-none" onclick="toggleSidebar()"></button>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white @if(request()->routeIs('dashboard'))active @endif" href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door fs-5 me-3"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white @if(request()->routeIs('books.*'))active @endif" href="{{ route('books.index') }}">
                    <i class="bi bi-book fs-5 me-3"></i> Data Buku
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white @if(request()->routeIs('loans.create'))active @endif" href="{{ route('loans.create') }}">
                    <i class="bi bi-clipboard-check fs-5 me-3"></i> Peminjaman
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white @if(request()->routeIs('loans.history'))active @endif" href="{{ route('loans.history') }}">
                    <i class="bi bi-clock-history fs-5 me-3"></i> Riwayat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white @if(request()->routeIs('profile.edit'))active @endif" href="{{ route('profile.edit') }}">
                    <i class="bi bi-people fs-5 me-3"></i> Data Pengguna
                </a>
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
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Change toggle icon
            const icon = toggleBtn.querySelector('i');
            icon.classList.toggle('bi-list');
            icon.classList.toggle('bi-x');
        }
        
        // Close sidebar on overlay click (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target) &&
                sidebar.classList.contains('collapsed') === false) {
                toggleSidebar();
            }
        });
        
        // Auto-adjust on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                document.getElementById('sidebar').classList.remove('collapsed');
                document.getElementById('mainContent').classList.remove('expanded');
            }
        });
    </script>
</body>
</html>

