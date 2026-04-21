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
                        <th class="text-center">ID</th>
                        <th class="text-center">Peminjam</th>
                        <th class="text-center">Buku</th>
                        <th class="text-center">Tgl Pinjam</th>
                        <th class="text-center">Tgl Jatuh Tempo</th>
                        <th class="text-center">Tgl Dikembalikan</th>
                        <th class="text-center">Denda</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                        <th class="text-center">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td><strong>#{{ $loan->id }}</strong></td>
                            <td class="text-center">{{ $loan->user->name ?? $loan->borrower_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $loan->book->title ?? 'N/A' }}</td>
                            <td class="text-center">{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $loan->return_date->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">
                                @if($loan->fine > 0)
                                    <div class="mb-1">
                                        <table>
                                            <tr>
                                                <td><small class="text-muted">Denda:</small></td>
                                                <td><span class="badge bg-warning text-dark fs-6">Rp {{ number_format($loan->fine) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><small class="text-muted">Sisa:</small></td>
                                                @php $outstanding = $loan->outstanding_fine ?? $loan->fine; @endphp
                                                <td style="min-width: 70px;">
                                                    @if($outstanding > 0)
                                                        <span class="badge bg-danger w-100 fs-6 p-2">Rp {{ number_format($outstanding) }}</span>
                                                    @else
                                                        <span class="badge bg-success w-100 fs-6 d-block text-center">LUNAS</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @else
                                    <div style="min-width:70px;">
                                        <span class="badge bg-success w-100 fs-6 d-block mx-auto" style="display:table;">TIDAK ADA</span>
                                    </div>
                                @endif
                            <td class="text-center">
                                <span class="badge fs-6 {{ $loan->status === 'dikembalikan' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="d-print-none text-center">
                                @if(isset($loan->outstanding_fine) && $loan->outstanding_fine > 0 && $loan->status === 'dikembalikan')
                                    <button class="btn btn-sm btn-success" onclick="openPayModal({{ $loan->id }}, {{ $loan->outstanding_fine }})">
                                        <i class="bi bi-cash-coin"></i> Bayar Denda
                                    </button>
                                @endif
                            </td>
                            <td class="text-center">
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

    {{-- Bayar Denda Modal --}}
    <!-- Bayar Denda Modal -->
    <div class="modal fade" id="payFineModal" tabindex="-1" aria-labelledby="payFineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="payFineForm" method="POST">
                    @csrf
                    <input type="hidden" name="amount" id="payAmount">
                    <div class="modal-header">
                        <h5 class="modal-title" id="payFineModalLabel">Bayar Denda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID Loan</label>
                            <div id="modalLoanId" class="form-control bg-light" readonly></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Bayar (Rp) <small class="text-danger">*</small></label>
                            <input type="number" class="form-control" id="amountInput" step="1000" min="1000" required>
                            <div class="form-text" id="maxAmountText"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Catat Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentMaxAmount = 0;
        let currentLoanId = 0;
        
        function openPayModal(loanId, maxAmount) {
            currentLoanId = loanId;
            currentMaxAmount = maxAmount;
            document.getElementById('modalLoanId').textContent = '#' + loanId;
            document.getElementById('maxAmountText').textContent = 'Maksimal: Rp ' + maxAmount.toLocaleString();
            document.getElementById('amountInput').max = maxAmount;
            document.getElementById('amountInput').value = maxAmount;
            document.getElementById('payAmount').value = maxAmount;
            document.getElementById('payFineForm').action = `/loans/${loanId}/pay-fine`;
            
            var modal = new bootstrap.Modal(document.getElementById('payFineModal'));
            modal.show();
        }
        
        function closePayModal() {
            var modal = bootstrap.Modal.getInstance(document.getElementById('payFineModal'));
            if (modal) modal.hide();
        }

        document.getElementById('payFineForm').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('amountInput').value);
            if (amount > currentMaxAmount || isNaN(amount)) {
                e.preventDefault();
                alert('Jumlah tidak boleh melebihi sisa denda!');
                return;
            }
            if (amount < 1000) {
                e.preventDefault();
                alert('Minimal Rp 1.000');
                return;
            }
            document.getElementById('payAmount').value = amount;
        });
    </script>

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

