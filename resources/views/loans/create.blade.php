@extends('layouts.library')

@section('title', 'Peminjaman Buku')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient text-black rounded-top-4 py-4">
                    <h2 class="mb-0 text-center">
                        <i class="bi bi-clipboard-check-fill fs-1 d-block mb-2"></i>
                        Data Peminjaman
                    </h2>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('loans.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5">Nama Peminjam</label>
                            <input type="text" name="borrower_name" value="{{ old('borrower_name') }}" class="form-control form-control-lg @error('borrower_name')is-invalid @enderror" placeholder="Masukkan nama lengkap peminjam" required>
                            @error('borrower_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5">Pilih Buku</label>
                            <select name="book_id" class="form-select form-select-lg @error('book_id')is-invalid @enderror" required>
                                <option value="">Pilih buku...</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }} - {{ $book->author }} (Stok: {{ $book->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Tanggal Pinjam</label>
                                <input type="date" name="loan_date" value="{{ old('loan_date') }}" class="form-control form-control-lg @error('loan_date')is-invalid @enderror" max="{{ date('Y-m-d') }}" required>
                                @error('loan_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Tanggal Kembali (Estimasi)</label>
                                <input type="date" name="return_date" value="{{ old('return_date') }}" class="form-control form-control-lg @error('return_date')is-invalid @enderror" required>
                                @error('return_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-3 d-md-flex justify-content-md-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-house-door me-2"></i> Dashboard
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="bi bi-check-lg me-2"></i> Catat Peminjaman
                            </button>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

