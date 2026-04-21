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
                                @if(auth()->user()->role === 'siswa')
                                    <input type="hidden" name="borrower_name" value="{{ auth()->user()->name }}">
                                    <input type="text" value="{{ auth()->user()->name }}" class="form-control form-control-lg bg-light" readonly style="cursor: not-allowed;">
                                    <small class="text-muted">Peminjaman atas nama Anda (siswa)</small>
                                @else
                                    <input type="text" name="borrower_name" value="{{ old('borrower_name') }}"
                                        class="form-control form-control-lg @error('borrower_name')is-invalid @enderror"
                                        placeholder="Masukkan nama lengkap peminjam" required>
                                    @error('borrower_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold fs-5">Pilih Buku</label>
                                <div class="input-group input-group-lg">
<input id="book_title"
                                        class="form-control form-control-lg @error('book_id')is-invalid @enderror"

                                        placeholder="Ketik judul buku atau nama penulis untuk mencari..."
                                        value="" required autocomplete="off">
                                    <input type="hidden" name="book_id" id="hidden_book_id" value="{{ old('book_id') }}">

                                    <button class="btn btn-outline-secondary" type="button"
                                        title="Semua buku tersedia di dropdown">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
<datalist id="books" style="display: none;">
@forelse($books as $book)
    <option value="{{ $book->title }} - {{ $book->author }}" data-book-id="{{ $book->id }}">
        {{ $book->title }} oleh {{ $book->author }}
        {{ $book->year ? ' ('. $book->year .')' : '' }}
        @if($book->publisher) - {{ $book->publisher }} @endif
    </option>
@empty
    <option value="" disabled>Tidak ada buku dengan stok tersedia</option>
@endforelse
</datalist>
<div id="book-dropdown" class="book-dropdown position-relative">

                                    <!-- Dynamic content -->
                                </div>
                                @error('book_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <style>
                                .book-dropdown {
                                    max-height: 300px;
                                }
                                .book-dropdown-list {
                                    position: absolute;
                                    top: 100%;
                                    left: 0;
                                    right: 0;
                                    background: white;
                                    border: 1px solid #dee2e6;
                                    border-radius: 0.375rem;
                                    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                                    z-index: 1050;
                                    max-height: 300px;
                                    overflow-y: auto;
                                    display: none;
                                }
                                .book-dropdown-item {
                                    padding: 0.75rem 1rem;
                                    cursor: pointer;
                                    border-bottom: 1px solid #f8f9fa;
                                    transition: background-color 0.15s ease;
                                }
                                .book-dropdown-item:hover {
                                    background-color: #f8f9fa;
                                }
                                .book-dropdown-item:last-child {
                                    border-bottom: none;
                                }
                                .no-results {
                                    padding: 1rem;
                                    color: #6c757d;
                                    font-style: italic;
                                }
                            </style>

                            <script>
                                const bookTitleInput = document.getElementById('book_title');
                                const hiddenBookIdInput = document.getElementById('hidden_book_id');
                                const dropdown = document.getElementById('book-dropdown');
                                const dropdownList = dropdown.querySelector('.book-dropdown-list') || createDropdownList();
                                
                                let allBooks = [];

                                // Store all books data on load
                                document.addEventListener('DOMContentLoaded', function() {
                                    allBooks = Array.from(document.querySelectorAll('#books option')).map(option => ({
                                        value: option.value,
                                        bookId: option.dataset.bookId,
                                        text: option.textContent
                                    }));
                                    updateDropdown('');
                                });

                                function createDropdownList() {
                                    const list = document.createElement('div');
                                    list.className = 'book-dropdown-list';
                                    dropdown.appendChild(list);
                                    return list;
                                }

                                function updateDropdown(filter = '') {
                                    const filterLower = filter.toLowerCase();
                                    let matching = allBooks.filter(book => 
                                        book.text.toLowerCase().includes(filterLower)
                                    );
                                    
                                    // Sort by relevance
                                    matching.sort((a, b) => {
                                        const aStarts = a.value.toLowerCase().startsWith(filterLower);
                                        const bStarts = b.value.toLowerCase().startsWith(filterLower);
                                        if (aStarts && !bStarts) return -1;
                                        if (bStarts && !aStarts) return 1;
                                        return 0;
                                    });
                                    
                                    const list = dropdown.querySelector('.book-dropdown-list');
                                    list.innerHTML = '';
                                    
                                    const top5 = matching.slice(0, 5);
                                    
                                    if (top5.length === 0) {
                                        list.innerHTML = '<div class="no-results">Tidak ada buku ditemukan</div>';
                                    } else {
                                        top5.forEach(book => {
                                            const item = document.createElement('div');
                                            item.className = 'book-dropdown-item';
                                            item.textContent = book.text;
                                            item.dataset.bookId = book.bookId;
                                            item.addEventListener('click', function() {
                                                bookTitleInput.value = book.value;
                                                hiddenBookIdInput.value = book.bookId;
                                                dropdownList.style.display = 'none';
                                            });
                                            list.appendChild(item);
                                        });
                                    }
                                    
                                    dropdownList.style.display = bookTitleInput.value.length > 0 || document.activeElement === bookTitleInput ? 'block' : 'none';
                                }

                                bookTitleInput.addEventListener('input', function() {
                                    updateDropdown(this.value);
                                });

                                bookTitleInput.addEventListener('focus', function() {
                                    updateDropdown(this.value);
                                });

                                // Hide dropdown on click outside
                                document.addEventListener('click', function(e) {
                                    if (!dropdown.contains(e.target) && e.target !== bookTitleInput) {
                                        dropdownList.style.display = 'none';
                                    }
                                });
                            </script>


        <div class="row g-4 mb-5">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold fs-5">Jumlah Eksemplar</label>
                                    <input type="number" name="quantity" value="1" min="1" max="10" class="form-control form-control-lg @error('quantity')is-invalid @enderror" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maksimal 10 eksemplar per peminjaman</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold fs-5">Tanggal Pinjam</label>
                                    <input type="date" name="loan_date" value="{{ old('loan_date') }}"
                                        class="form-control form-control-lg @error('loan_date')is-invalid @enderror"
                                        max="{{ date('Y-m-d') }}" required>
                                    @error('loan_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold fs-5">Tanggal Kembali (Estimasi)</label>
                                    <input type="date" name="return_date" value="{{ old('return_date') }}"
                                        class="form-control form-control-lg @error('return_date')is-invalid @enderror"
                                        required>
                                    @error('return_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
