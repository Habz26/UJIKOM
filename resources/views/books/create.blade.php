@extends('layouts.library')

@section('title', 'Tambah Buku')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-plus-circle text-success fs-1 d-block mb-2"></i>
                        Tambah Buku Baru
                    </h2>
                    
                    <form method="POST" action="{{ route('books.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Judul Buku</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control form-control-lg @error('title')is-invalid @enderror" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Penulis</label>
                            <input type="text" name="author" value="{{ old('author') }}" class="form-control form-control-lg @error('author')is-invalid @enderror" required>
                            @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tahun Terbit</label>
                                <input type="number" name="year" value="{{ old('year') }}" class="form-control form-control-lg @error('year')is-invalid @enderror" min="1900" max="{{ date('Y') }}" required>
                                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Stok Awal</label>
                                <input type="number" name="stock" value="{{ old('stock', 1) }}" class="form-control form-control-lg @error('stock')is-invalid @enderror" min="0" required>
                                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="{{ route('books.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="bi bi-check-circle me-2"></i> Simpan Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

