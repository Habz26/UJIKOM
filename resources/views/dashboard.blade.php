@extends('layouts.library')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 text-primary">
        <i class="bi bi-house-door me-2"></i> Dashboard
    </h2>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 text-center bg-primary text-white">
                <div class="card-body">
                    <i class="bi bi-book fs-1 mb-3 opacity-75"></i>
                    <h3 class="card-title">{{ $stats['total_books'] ?? 0 }}</h3>
                    <p class="card-text">Total Buku</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center bg-warning text-white">
                <div class="card-body">
                    <i class="bi bi-clipboard-check fs-1 mb-3 opacity-75"></i>
                    <h3 class="card-title">{{ $stats['active_loans'] ?? 0 }}</h3>
                    <p class="card-text">Buku Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center bg-success text-white">
                <div class="card-body">
                    <i class="bi bi-people fs-1 mb-3 opacity-75"></i>
                    <h3 class="card-title">{{ $stats['total_loans'] ?? 0 }}</h3>
                    <p class="card-text">Total Transaksi</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

