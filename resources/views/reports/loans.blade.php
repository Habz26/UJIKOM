@extends('layouts.library')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        <i class="bi bi-clipboard-data me-2 text-success"></i>
        Laporan Transaksi Peminjaman
    </h2>

    <div class="d-print-none mb-4">
        <button onclick="window.print()" class="btn btn-success me-2">
            <i class="bi bi-printer me-1"></i>Print / PDF
        </button>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Jatuh Tempo</th>
                        <th>Tgl Dikembalikan</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td><strong>#{{ $loan->id }}</strong></td>
                            <td>{{ $loan->user->name ?? $loan->borrower_name ?? 'N/A' }}</td>
                            <td>{{ $loan->book->title ?? 'N/A' }}</td>
                            <td class="text-nowrap">{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td class="text-nowrap">{{ $loan->return_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($loan->fine < 0)
                                    <span class="badge bg-danger fs-6"><i>Rp {{ number_format($loan->fine) }}</i></span>
                                @else
                                    <span class="badge bg-success fs-6"><i>Gratis</i></span>
                                @endif
                            </td>
                            <td>
                                <span class="badge fs-6 {{ $loan->status === 'dikembalikan' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>
                                @if($loan->condition === 'rusak')
                                    <span class="badge bg-danger fs-6">{{ ucfirst($loan->condition) }}</span>
                                    @if($loan->damage_note)
                                        <br><small class="text-muted">{{ $loan->damage_note }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-success">{{ ucfirst($loan->condition ?? 'Baik') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent">
            {{ $loans->appends(request()->query())->links() }}
        </div>
    </div>

    <style>
        @media print {
            .d-print-none { display: none !important; }
            .card { box-shadow: none !important; }
            table { font-size: 11px; }
            .table th, .table td { padding: 0.5rem; }
            .pagination, nav[role="navigation"] { display: none !important; }
        }
    </style>
</div>
@endsection

