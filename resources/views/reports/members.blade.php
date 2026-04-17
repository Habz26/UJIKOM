@extends('layouts.library')

@section('title', 'Laporan Anggota')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-3">
                    <i class="bi bi-people me-2 text-info"></i>
                    Laporan Anggota Perpustakaan
                </h2>
                <p class="text-muted">Total anggota: <strong>{{ number_format($members->count()) }}</strong></p>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-print-none mb-3">
                    <button onclick="window.print()" class="btn btn-success me-2">
                        <i class="bi bi-printer me-1"></i>Print / PDF
                    </button>
                </div>
                <form method="GET" action="{{ route('reports.members') }}" class="d-inline">
                    <div class="input-group input-group-sm" style="max-width: 300px;">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <style>
        @media print {
            .d-print-none { display: none !important; }
            .card { box-shadow: none !important; border: none !important; }
            table { font-size: 11px; }
            .avatar-img { display: none !important; }
            .btn { display: none !important; }
            .pagination, nav[role="navigation"] { display: none !important; }
            .alert { display: none !important; }
        }
        </style>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Total Peminjaman</th>
                                <th>Total Denda</th>
                                <th>Terakhir Login</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $index => $member)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&size=40&background=4f46e5&color=fff"
                                                    alt="{{ $member->name }}" class="avatar-img rounded-circle">
                                            </div>
                                            <div>
                                                <strong>{{ $member->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        <span class="badge {{ $member->role === 'admin' ? 'bg-danger' : 'bg-info' }}">
                                            {{ ucfirst($member->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $member->loans_count ?? 0 }}</span>
                                    </td>
                                    <td>Rp {{ number_format($member->total_fine ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $member->last_login ? $member->last_login->format('d/m/Y H:i') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('members.edit', $member) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bi bi-people display-4 opacity-50 mb-3 d-block"></i>
                                        <p class="text-muted mb-0">Belum ada data anggota</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($members->hasPages())
                    <nav class="bg-light p-3">
                        {{ $members->appends(request()->query())->links() }}
                    </nav>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-4">{{ session('success') }}</div>
        @endif
    </div>
@endsection
