@extends('layouts.library')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        <i class="bi bi-file-earmark-text me-2 text-primary"></i>
        Laporan Perpustakaan
    </h2>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('reports.books') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-shadow-lg transition-all">
                <div class="card-body text-center py-5">
                    <i class="bi bi-book-half fs-1 text-primary mb-3"></i>
                    <h4 class="card-title text-primary mb-2">Laporan Buku</h4>
                    <p class="card-text text-muted mb-0">Data lengkap buku, stok, kategori</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('reports.loans') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-shadow-lg transition-all">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-data fs-1 text-success mb-3"></i>
                    <h4 class="card-title text-success mb-2">Laporan Transaksi</h4>
                    <p class="card-text text-muted mb-0">Riwayat peminjaman & denda</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('reports.members') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-shadow-lg transition-all">
                <div class="card-body text-center py-5">
                    <i class="bi bi-people fs-1 text-info mb-3"></i>
                    <h4 class="card-title text-info mb-2">Laporan Anggota</h4>
                    <p class="card-text text-muted mb-0">Statistik pengguna</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 hover-shadow-lg transition-all cursor-pointer" onclick="exportAll()">
                <div class="card-body text-center py-5 bg-gradient-primary">
                    <i class="bi bi-download fs-1 mb-3 text-success"></i>
                    <h4 class="card-title mb-2 text-success">Export Semua</h4>
                    <p class="card-text mb-0">PDF & Excel lengkap</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow-lg { transition: box-shadow 0.3s ease; }
        .hover-shadow-lg:hover { box-shadow: 0 0.5rem 2rem rgba(0,0,0,0.15) !important; }
        .cursor-pointer { cursor: pointer; }
    </style>

    <script>
        function exportAll() {
            alert('Fitur export lengkap akan segera tersedia!');
        }
    </script>
</div>
@endsection
