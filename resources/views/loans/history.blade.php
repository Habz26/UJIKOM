@extends('layouts.library')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="container-fluid">
<h2 class="mb-4">
        <i class="bi bi-clock-history me-2 text-warning"></i> Riwayat Peminjaman
    </h2>
    <!-- History Table -->
    <div class="card shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th class="text-center">Pinjam</th>
                            <th class="text-center">Estimasi</th>
                            <th class="text-center">Kembali</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Kondisi</th>
                            <th class="text-center">Catatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($loans as $loan)

                        <tr>
                            <td>{{ Str::limit($loan->borrower_name, 30) }}</td>
                            <td>
                                <strong>{{ Str::limit($loan->book->title ?? 'N/A', 30) }}</strong><br>
                                <small class="text-muted">{{ $loan->book->author ?? '' }}</small>
                            </td>
                            <td class="text-center">{{ $loan->loan_date ? $loan->loan_date->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">{{ $loan->return_date?->format('d/m/Y') ?? '-' }}</td>
                            <td class="text-center">{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">
                                @php
                                    $dueDate = $loan->due_date ?? $loan->return_date ?? $loan->loan_date->copy()->addDays(7);
                                    $isOverdue = $loan->status === 'dipinjam' && now() > $dueDate;
                                @endphp
                                @if($loan->status === 'dikembalikan')
                                    <span class="badge bg-success">Dikembalikan</span>
                                @elseif($isOverdue)
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($loan->condition)
                                    <span class="badge bg-{{ $loan->condition === 'baik' ? 'success' : 'danger' }}">
                                        {{ ucfirst($loan->condition) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($loan->damage_note)
                                    <span class="badge bg-light text-dark border">
                                        {{ Str::limit($loan->damage_note, 20) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($loan->status === 'dipinjam')
                                    <form method="POST" action="{{ route('loans.return', $loan) }}" class="d-inline" onsubmit="return confirm('Tandai buku ini sebagai dikembalikan hari ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Kembalikan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-success fw-bold">✓ Selesai</span>
                                @endif
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                Belum ada riwayat peminjaman.
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
</div>
@endsection

