@extends('layouts.library')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 text-primary">
        <i class="bi bi-house-door me-2"></i> Dashboard Siswa
    </h2>

    {{-- Personal Loan Status Alerts --}}
    @if (($stats['due_today_count'] ?? 0) + ($stats['overdue_count'] ?? 0) + ($stats['active_loans'] ?? 0) > 0)
        <div class="mb-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl shadow-xl">
            <div class="flex items-center mb-3">
                <i class="bi bi-clipboard-data text-2xl text-gray-800"></i>
                <h4 class="ml-3 text-xl font-bold text-gray-900 mb-0">Status Peminjaman Pribadi</h4>
            </div>
            @php
                $statusParts = [];

                if (($stats['active_loans'] ?? 0) > 0) {
                    $statusParts[] = ($stats['active_loans'] ?? 0) . ' peminjaman aktif';
                }

                if (($stats['due_today_count'] ?? 0) > 0) {
                    $statusParts[] = ($stats['due_today_count'] ?? 0) . ' jatuh tempo hari ini';
                }

                if (($stats['overdue_count'] ?? 0) > 0) {
                    $statusParts[] = '<strong class="text-red-600">(' . ($stats['overdue_count'] ?? 0) . ' terlambat)</strong>';
                }

                $allText = implode(', ', $statusParts) . '.';
            @endphp

            @if (!empty($allText))
                <div class="text-lg text-gray-700">
                    <strong class="text-gray-900">{{ $allText }}</strong>
                    <a href="{{ route('loans.active') }}" class="text-green-600 hover:text-green-800 font-semibold underline ms-2">
                        Lihat Semua Pinjaman
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- Personal Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100 text-center bg-primary text-white shadow-lg border-0">
                <div class="card-body py-5">
                    <i class="bi bi-book-half fs-1 mb-3 opacity-75"></i>
                    <h3 class="card-title display-4 font-bold mb-2">{{ $stats['total_loans'] ?? 0 }}</h3>
                    <p class="card-text fs-5 fw-semibold">Total Pinjaman Saya</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center bg-success text-white shadow-lg border-0">
                <div class="card-body py-5">
                    <i class="bi bi-inbox fs-1 mb-3 opacity-75"></i>
                    <h3 class="card-title display-4 font-bold mb-2">{{ $stats['active_loans'] ?? 0 }}</h3>
                    <p class="card-text fs-5 fw-semibold">Sedang Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center bg-warning text-white shadow-lg border-0">
                <div class="card-body py-5">
                    <i class="bi bi-clock-history fs-1 mb-3 opacity-75"></i>
                    <h3 class="card-title display-4 font-bold mb-2">{{ $stats['overdue_count'] ?? 0 }}</h3>
                    <p class="card-text fs-5 fw-semibold">Terlambat Kembali</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Action Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('books.index') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-shadow-xl transition-all">
                <div class="card-body text-center py-5 bg-gradient-primary text-dark">
                    <i class="bi bi-bookshelf fs-1 mb-3"></i>
                    <h4 class="card-title mb-2 text-dark">Katalog Buku</h4>
                    <p class="card-text mb-0">Jelajahi semua buku tersedia</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('loans.create') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-shadow-xl transition-all">
                <div class="card-body text-center py-5 bg-gradient-success text-dark">
                    <i class="bi bi-plus-circle fs-1 mb-3"></i>
                    <h4 class="card-title mb-2">Pinjam Buku Baru</h4>
                    <p class="card-text mb-0">Ajukan peminjaman buku favorit</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('loans.active') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-shadow-xl transition-all">
                <div class="card-body text-center py-5 bg-gradient-info text-dark">
                    <i class="bi bi-list-ul fs-1 mb-3"></i>
                    <h4 class="card-title mb-2">Pinjaman Aktif</h4>
                    <p class="card-text mb-0">Status pinjaman saat ini</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('loans.history') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-shadow-xl transition-all">
                <div class="card-body text-center py-5 bg-gradient-warning text-dark">
                    <i class="bi bi-clock-history fs-1 mb-3"></i>
                    <h4 class="card-title mb-2">Riwayat Pinjam</h4>
                    <p class="card-text mb-0">Riwayat lengkap peminjaman</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Active Loans Table --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-nested me-2"></i>Pinjaman Aktif Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Hari Tersisa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeLoans as $loan)
                                    <tr class="@if($loan->return_date < now()) table-danger @endif">
                                        <td>
                                            <strong>{{ Str::limit($loan->book->title ?? 'N/A', 30) }}</strong>
                                            <br><small class="text-muted">Kategori: {{ $loan->book->kategori?->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                        <td><strong>{{ $loan->return_date->format('d/m/Y') }}</strong></td>
                                        <td>
                                            @if ($loan->return_date < now())
                                                <span class="badge bg-danger">Terlambat</span>
                                            @elseif ($loan->return_date->diffInDays(now()) <= 2)
                                                <span class="badge bg-warning">Segera Jatuh Tempo</span>
                                            @else
                                                <span class="badge bg-success">Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($loan->return_date > now())
                                                <span class="text-success fw-bold">{{ $loan->return_date->diffInDays(now()) }} hari</span>
                                            @else
                                                <span class="text-danger fw-bold">{{ now()->diffInDays($loan->return_date) }} hari terlambat</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="#" class="btn btn-outline-primary" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                            <p class="lead text-muted">Belum ada pinjaman aktif</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow-xl { 
    transition: all 0.3s ease; 
}
.hover-shadow-xl:hover { 
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}
</style>
@endsection
