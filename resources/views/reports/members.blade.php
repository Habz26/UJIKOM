@extends('layouts.library')

@section('title', 'Laporan Anggota')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">
            <i class="bi bi-people me-2 text-info"></i>
            Laporan Anggota Perpustakaan
        </h2>
        <p class="text-muted mb-3">Total anggota: <strong>{{ number_format($members->total()) }}</strong></p>
        <div class="d-print-none mb-4">
            <button onclick="window.print()" class="btn btn-success me-2">
                <i class="bi bi-printer me-1"></i>Print / PDF
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        @php $search = request('search'); @endphp

        <form method="GET" action="{{ route('reports.members') }}" class="mb-4">
            <div class="input-group input-group-sm" style="max-width: 400px;">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..."
                    value="{{ $search }}">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                @if($search)
                    <a href="{{ route('reports.members') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        @php
            function highlight($text, $term) {
                if (!$term) {
                    return $text;
                }
                $pattern = '/(' . preg_quote($term, '/') . ')/i';
                return preg_replace($pattern, '<mark class="bg-warning text-dark fw-bold">$1</mark>', $text);
            }
        @endphp

        <style>
        @media print {
            .d-print-none { display: none !important; }
            .card { box-shadow: none !important; }
            table { font-size: 12px; }
            .avatar-img { display: none !important; }
            .pagination, nav[role="navigation"] { display: none !important; }
        }
        </style>

        <div class="card shadow">
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
                                                <strong>{!! highlight($member->name, $search ?? '') !!}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{!! highlight($member->email, $search ?? '') !!}</td>
                                    <td>
                                        <span class="badge {{ $member->role === 'admin' ? 'bg-danger' : 'bg-info' }}">
                                            {!! highlight(ucfirst($member->role), $search ?? '') !!}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $member->loans_count ?? 0 }}</span>
                                    </td>
                                    <td>Rp {{ number_format($member->total_outstanding ?? 0, 0, ',', '.') }}</td>
                                    <td>{!! highlight(($member->last_activity ? \Carbon\Carbon::parse($member->last_activity)->format('d/m/Y H:i') : '-'), $search ?? '') !!}</td>
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
                    <div class="card-footer bg-transparent">
                        {{ $members->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-4">{{ session('success') }}</div>
        @endif
    </div>
@endsection
