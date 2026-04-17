@extends('layouts.library')

@section('title', 'Laporan Buku')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        <i class="bi bi-book me-2 text-primary"></i>
        Laporan Data Buku
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
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tahun</th>
                        <th>Penerbit</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Total Dipinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td>
                                <strong>{{ $book->title }}</strong>
                                @if($book->trashed())
                                    <br><small class="text-muted">Nonaktif</small>
                                @endif
                            </td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->year }}</td>
                            <td>{{ $book->publisher ?? '-' }}</td>
                            <td>{{ $book->kategori->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $book->stock > 5 ? 'bg-success' : ($book->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $book->stock }}
                                </span>
                            </td>
                            <td>{{ $book->loans->count() }}</td>
                            <td>
                                @if($book->trashed())
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @elseif($book->stock == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                Belum ada data buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            .d-print-none { display: none !important; }
            .card { box-shadow: none !important; }
            table { font-size: 12px; }
            .pagination, nav[role="navigation"] { display: none !important; }
        }
    </style>
</div>
@endsection

