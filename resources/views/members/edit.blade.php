@extends('layouts.library')

@section('title', 'Edit Anggota')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-warning text-dark rounded-top-4">
                    <h3 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Data Anggota
                    </h3>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('members.update', $member) }}">
                        @csrf @method('PATCH')
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $member->name) }}" required>
                                    <label for="name">Nama Lengkap</label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $member->email) }}" required>
                                    <label for="email">Email</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role', $member->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="siswa" {{ old('role', $member->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                    </select>
                                    <label for="role">Role</label>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="kosongkan jika tidak diubah">
                                    <label for="password">Password Baru (kosongkan jika tidak diubah)</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="konfirmasi">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('members.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="bi bi-check-circle me-2"></i>Update Anggota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

