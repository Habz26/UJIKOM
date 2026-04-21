@extends('layouts.library')

@if($isAdmin)
    @section('title', 'Riwayat Semua Peminjaman')
@else
    @section('title', 'Riwayat Peminjaman Saya')
@endif

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        <i class="bi bi-clock-history me-2 text-warning"></i>
        @if($isAdmin)
            Riwayat Semua Peminjaman
            <small class="text-muted d-block fs-6 mt-1">({{ $loans->total() }} total | Rp {{ number_format($loans->sum('fine')) }} total denda)</small>
        @else
            Riwayat Peminjaman Saya
        @endif
    </h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($loans->count() > 0)
        <!-- History Loans Table -->
        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            @if($isAdmin)
                                Menampilkan semua peminjaman yang telah dikembalikan dari seluruh pengguna.
                            @else
                                Riwayat peminjaman pribadi Anda.
                            @endif
                        </small>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge bg-info fs-6">{{ $loans->count() }} ditampilkan</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                @if($isAdmin)
                                    <th>Peminjam</th>
                                @endif
                                <th>Buku</th>
<th>Jumlah Pinjam</th>
<th>Dikembalikan</th>
                                <th>Sisa</th>
                                <th>Status Verif</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Jatuh Tempo</th>
                                <th>Tgl Dikembalikan</th>
                                <th>Denda</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $loan)
                                <tr>
@if($isAdmin)
                                        <td>
                                            <strong>{{ $loan->user->name ?? $loan->borrower_name ?? 'N/A' }}</strong>
                                            @if($loan->user)
                                                <br><small class="text-muted">{{ $loan->user->email }}</small>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <strong>{{ Str::limit($loan->book->title ?? 'N/A', 40) }}</strong>
                                        @if($loan->book->kategori)
                                            <br><small class="text-muted">Kategori: {{ $loan->book->kategori->name }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $loan->borrowed_quantity }}</td>
                                    <td>{{ $loan->returned_quantity }}</td>
                                    <td>{{ $loan->quantity }}</td>
                                    <td>
                                        @if($loan->verification_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($loan->verification_status === 'verified_returned')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                    <td>{{ $loan->return_date->format('d/m/Y') }}</td>
                                    <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($loan->fine > 0)
                                            <span class="badge bg-danger fs-6">Rp {{ number_format($loan->fine) }}</span>
                                        @else
                                            <span class="badge bg-success fs-6">Gratis</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($loan->condition === 'rusak')
                                            <span class="badge bg-warning text-dark fs-6">Rusak</span>
                                            @if($loan->damage_note)
                                                <br><small class="text-muted">{{ Str::limit($loan->damage_note, 50) }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-success fs-6">Baik</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isAdmin ? 7 : 6 }}" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        @if($isAdmin)
                                            Belum ada peminjaman yang telah dikembalikan oleh semua pengguna.
                                        @else
                                            Belum ada riwayat peminjaman yang telah dikembalikan.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent py-3 border-0">
                {{ $loans->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-10">
            <i class="bi bi-clock-history display-1 text-muted mb-4"></i>
            <h4 class="text-muted mb-3">
                @if($isAdmin) Belum Ada Riwayat Sistem @else Belum Ada Riwayat @endif
            </h4>
            <p class="text-muted mb-4">
                @if($isAdmin)
                    Tidak ada peminjaman yang telah dikembalikan di sistem.
                @else
                    Semua peminjaman Anda masih aktif atau belum pernah meminjam.
                @endif
            </p>
            <a href="{{ route('loans.active') }}" class="btn btn-outline-primary">
                <i class="bi bi-list-ul me-2"></i> Lihat Pinjaman Aktif
            </a>
            <a href="{{ route('loans.create') }}" class="btn btn-primary ms-2">
                <i class="bi bi-plus-circle me-2"></i> @if($isAdmin) Buat Peminjaman @else Pinjam Buku Baru @endif
            </a>
        </div>
    @endif
</div>
@endsection
