@extends('layouts.library')

@section('title', 'Kelola Anggota')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="bi bi-people me-2 text-primary"></i>
            Kelola Anggota ({{ $members->total() }})
        </h2>
        <a href="{{ route('members.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Anggota
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light border-0 pb-0">
            <h6 class="mb-0">Filter</h6>
        </div>
        <div class="card-body pt-0">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="role" class="form-select">
                            <option value="all" {{ request('role', 'all') == 'all' ? 'selected' : '' }}>Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Pinjaman Aktif</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $member->name }}</strong>
                                    @if ($member->trashed())
                                        <span class="badge bg-secondary ms-2">Dinonaktifkan</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>
                                <span class="badge {{ $member->role === 'admin' ? 'bg-danger' : 'bg-info' }}">
                                    {{ ucfirst($member->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $member->loans()->where('status', 'dipinjam')->count() > 0 ? 'warning' : 'success' }}">
                                    {{ $member->loans()->where('status', 'dipinjam')->count() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success">Email Terverifikasi</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if (!$member->trashed())
                                        <form method="POST" action="{{ route('members.destroy', $member) }}" class="d-inline" onsubmit="return confirm('Hapus anggota?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('members.restore', $member) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-3 opacity-50"></i>
                                Belum ada anggota.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent py-3">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

