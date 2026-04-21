@extends('layouts.library')

@section('title', 'Pinjaman Aktif')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">
            <i class="bi bi-clipboard-check me-2 text-primary"></i> Pinjaman Aktif
        </h2>

        @if ($overdueLoans > 0)
            <div class="alert alert-danger mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>{{ $overdueLoans }} peminjaman TERLAMBAT!</strong>
                Harap segera hubungi peminjam.
                <a href="?status=terlambat" class="alert-link">Filter Terlambat</a>
            </div>
        @endif

        <!-- Active Loans Table -->
        <div class="card shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Jumlah</th>
                                <th class="text-center">Tgl Pinjam</th>
                                <th class="text-center">Jatuh Tempo</th>
                                <th class="text-center">Hitung Mundur</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $loan)
                                <tr
                                    @if ($loan->verification_status === 'pending') class="table-warning" @elseif ($loan->loan_date <= now()->subDays(7)) class="table-danger" @endif>
                                    <td>{{ $isAdmin ? $loan->user->name ?? $loan->borrower_name : auth()->user()->name }}
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($loan->book->title ?? 'N/A', 30) }}</strong><br>
                                        <small>{{ $loan->book->author ?? '' }}</small>
                                    </td>
                                    <td>{{ $loan->quantity }}</td>
                                    <td class="text-center">{{ $loan->loan_date->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        {{ $loan->return_date ? $loan->return_date->format('d/m/Y') : $loan->loan_date->copy()->addDays(7)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $dueDate = $loan->return_date ?? $loan->loan_date->copy()->addDays(7);
                                            $today = now()->copy()->startOfDay();
                                            $daysDiff = $today->diffInDays($dueDate, false);
                                            $isOverdue = $daysDiff < 0;
                                            $daysOverdue = $isOverdue ? abs($daysDiff) : 0;
                                            $daysLeft = !$isOverdue ? abs($daysDiff) : 0;
                                        @endphp
                                        @if ($isOverdue)
                                            <span class="badge bg-danger fs-6">
                                                terlambat {{ $daysOverdue }} {{ $daysOverdue > 1 ? 'hari' : 'hari' }}
                                            </span>
                                        @elseif ($daysLeft == 0)
                                            <span class="badge bg-warning text-dark fs-6">Hari ini</span>
                                        @else
                                            <span class="badge bg-success fs-6">
                                                {{ $daysLeft }} {{ $daysLeft > 1 ? 'hari lagi' : 'hari lagi' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">

                                            @if (!$isAdmin)
                                                @if ($loan->status === 'dipinjam')
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#returnModal{{ $loan->id }}">
                                                        <i class="bi bi-check-circle"></i> Ajukan Kembali
                                                    </button>
                                                @elseif($loan->verification_status === 'pending')
                                                    <span class="badge bg-secondary text-white px-2 py-1">📋 Diajukan
                                                        {{ $loan->pending_return_quantity }}/{{ $loan->quantity }}</span>
                                                @endif
                                            @elseif($isAdmin && $loan->verification_status === 'pending')
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#verifyModal{{ $loan->id }}">
                                                    <i class="bi bi-clipboard-check"></i> Verifikasi
                                                    {{ $loan->pending_return_quantity }}
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Tidak ada pinjaman aktif.
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


        @foreach ($loans as $loan)
            <div class="modal fade" id="returnModal{{ $loan->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5>Kembalikan Buku - {{ $loan->book->title }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('loans.return', $loan) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Dikembalikan</label>
                                    <input type="number" name="return_quantity" value="{{ $loan->quantity }}"
                                        min="1" max="{{ max(1, $loan->quantity) }}" class="form-control" required>
                                    <small class="text-muted">Pinjam: {{ $loan->borrowed_quantity }} | Dikembalikan:
                                        {{ $loan->returned_quantity }} | Sisa: {{ $loan->quantity }}</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Kembalikan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="verifyModal{{ $loan->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5>Verifikasi Pengembalian - {{ $loan->book->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('loans.verify', $loan) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Diverifikasi</label>
                                    <input type="number" name="return_quantity"
                                        value="{{ $loan->pending_return_quantity }}" min="1"
                                        max="{{ $loan->pending_return_quantity }}" class="form-control" required>
                                    <small class="text-muted">Diajukan: {{ $loan->pending_return_quantity }}</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kondisi Buku</label>
                                    <select name="condition" class="form-select" required>
                                        <option value="baik">Baik (tanpa denda tambahan)</option>
                                        <option value="rusak">Rusak (denda Rp 100.000/eksemplar + denda telat)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catatan Kerusakan</label>
                                    <textarea name="damage_note" class="form-control" rows="3" placeholder="Opsional jika baik"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">Verifikasi & Proses</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
