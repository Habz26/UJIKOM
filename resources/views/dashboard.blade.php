@extends('layouts.library')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4 text-primary">
            <i class="bi bi-house-door me-2"></i> Dashboard
        </h2>

        {{-- Loan Status Alerts --}}
@if ((($stats['due_today_count'] ?? 0) + ($stats['overdue_count'] ?? 0) + ($stats['active_loans'] ?? 0)) > 0)
                <div class="mb-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl shadow-xl">
                    <div class="flex items-center mb-3">
                        <i class="bi bi-clipboard-data text-2xl text-gray-800"></i>
                        <h4 class="ml-3 text-xl font-bold text-gray-900 mb-0">Status Pinjaman</h4>
                    </div>
                    @php
                        $activeText = (($stats['active_loans'] ?? 0) > 0) ? (string)(($stats['active_loans'] ?? 0) . ' peminjaman aktif') : '';
                        $dueText = (($stats['due_today_count'] ?? 0) > 0) ? (string)(($stats['due_today_count'] ?? 0) . ' jatuh tempo') : '';
                        $overdueText = (($stats['overdue_count'] ?? 0) > 0) ? (string)(($stats['overdue_count'] ?? 0) . ' terlambat') : '';
                        
                        $allText = trim($activeText . ($activeText && $dueText ? ', ' : '') . $dueText . ($dueText && $overdueText ? ', ' : '') . $overdueText, ',');
                    @endphp
                    
                    @if (!empty($allText))
                        <div class="text-lg">
                            <strong class="text-gray-900">{{ $allText }}</strong>
                            <a href="{{ route('loans.active') }}?status=dipinjam" class="ml-3 text-blue-600 hover:text-blue-800 font-semibold underline">
                                Lihat detail
                            </a>
                        </div>
                    @endif
                </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card h-100 text-center bg-primary text-white">
                    <div class="card-body">
                        <i class="bi bi-book fs-1 mb-3 opacity-75"></i>
                        <h3 class="card-title">{{ $stats['total_books'] ?? 0 }}</h3>
                        <p class="card-text">Total Buku</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center bg-danger text-white">
                    <div class="card-body">
                        <i class="bi bi-exclamation-triangle fs-1 mb-3 opacity-75"></i>
                        <h3 class="card-title">{{ $stats['books_loaned'] ?? 0 }}</h3>
                        <p class="card-text">Stok Rendah</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center bg-warning text-white">
                    <div class="card-body">
                        <i class="bi bi-clipboard-check fs-1 mb-3 opacity-75"></i>
                        <h3 class="card-title">{{ $stats['active_loans'] ?? 0 }}</h3>
                        <p class="card-text">Pinjaman Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center bg-success text-white">
                    <div class="card-body">
                        <i class="bi bi-people fs-1 mb-3 opacity-75"></i>
                        <h3 class="card-title">{{ $stats['total_loans'] ?? 0 }}</h3>
                        <p class="card-text">Total Transaksi</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Borrowed Books Chart --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Buku Paling Sering Dipinjam (Top 5)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="topBooksChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Loans Verification --}}
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Verifikasi Kondisi Buku Dipinjam</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Buku</th>
                                        <th>Peminjam</th>
                                        <th>Tgl Pinjam</th>
                                        <th>Tgl Jatuh Tempo</th>
                                        <th>Kondisi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activeLoans as $loan)
                                        <tr>
                                            <td>{{ $loan->book->title ?? 'N/A' }}</td>
                                            <td>{{ $loan->borrower_name }}</td>
                                            <td>{{ $loan->loan_date->format('Y-m-d') }}</td>
                                            <td>{{ $loan->return_date->format('Y-m-d') }}</td>
                                            <td>
                                                @if ($loan->condition ?? 'baik' === 'baik')
                                                    <span class="badge bg-success">Baik</span>
                                                @else
                                                    <span class="badge bg-danger">Rusak</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#damageModal-{{ $loan->id }}">Laporkan
                                                    Kerusakan</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada pinjaman aktif.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($activeLoans as $loan)
            {{-- Damage Report Modal for Loan {{ $loan->id }} --}}
            <div class="modal fade" id="damageModal-{{ $loan->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">Laporkan Kondisi - {{ $loan->book->title ?? 'Buku' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('loans.update-condition', $loan->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Kondisi Saat Dikembalikan</label>
                                    <select name="condition" class="form-select" required>
                                        <option value="baik"
                                            {{ ($loan->condition ?? 'baik') == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak" {{ ($loan->condition ?? '') == 'rusak' ? 'selected' : '' }}>
                                            Rusak</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catatan Kerusakan (opsional)</label>
                                    <textarea name="damage_note" class="form-control" rows="3" placeholder="Jika rusak, deskripsikan...">{{ $loan->damage_note ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">Update Kondisi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Real data from controller
            const labels = [
                @foreach ($topBooks as $book)
                    '{{ $book->title }}',
                @endforeach
            ];
            const data = [
                @foreach ($topBooks as $book)
                    {{ $book->loan_count }},
                @endforeach
            ];

            const ctx = document.getElementById('topBooksChart').getContext('2d');
            const topBooksChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Dipinjam',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
@endsection

