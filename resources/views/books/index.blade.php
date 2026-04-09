@extends('layouts.library')

@section('title', 'Data Buku')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-book me-2 text-primary"></i> Data Buku
        </h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookModal" onclick="prepareModal('create')">
            <i class="bi bi-plus-circle me-2"></i> Tambah Buku
        </button>
    </div>

    <!-- Search -->
    <div class="card mb-4 shadow-sm rounded-3">
        <div class="card-body">
            <form method="GET" action="{{ route('books.index') }}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari judul atau penulis..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
                    </div>
                    @if(request('search'))
                    <div class="col-md-2">
                        <a href="{{ route('books.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Books Table -->
    <div class="card shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Tahun</th>
                            <th>Stok</th>
                            <th>Penerbit</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            function highlight($text, $term) {
                                if (!$term) return $text;
                                $pattern = '/(' . preg_quote($term, '/') . ')/i';
                                return preg_replace($pattern, '<mark class="bg-warning text-dark fw-bold">$1</mark>', $text);
                            }
                        @endphp
                        @forelse($books as $book)
                        <tr>
                            <td>{!! highlight(Str::limit($book->title, 40), $search ?? '') !!}</td>
                            <td>{!! highlight(Str::limit($book->author, 30), $search ?? '') !!}</td>
                            <td>{{ $book->year }}</td>
                            <td>
                                <span class="badge {{ $book->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $book->stock }}
                                </span>
                            </td>
                            <td>{{ Str::limit($book->publisher ?? 'N/A', 20) }}</td>
                            <td><span class="badge @if($book->category == 'Fiksi') bg-primary @elseif($book->category == 'Non-Fiksi') bg-secondary @elseif($book->category == 'Komputer') bg-warning text-dark @elseif($book->category == 'Pengembangan Diri') bg-success @elseif($book->category == 'Perpustakaan') bg-info @elseif($book->category == 'Sains Fiksi') bg-danger @elseif($book->category == 'Sejarah') bg-dark @else bg-light text-dark @endif">{{ $book->category ?? 'Tidak dikategorikan' }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#bookModal" onclick="prepareModal('edit', {{ $book->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('books.destroy', $book) }}" class="d-inline" onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
<td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-book-x fs-1 d-block mb-2"></i>
                                Belum ada data buku.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3">
            {{ $books->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Book Modal -->
<div class="modal fade" id="bookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bookForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="bookId">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="title" id="title" class="form-control @error('title')is-invalid @enderror" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="author" id="author" class="form-control @error('author')is-invalid @enderror" required>
                        @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tahun Terbit</label>
                            <input type="number" name="year" id="year" class="form-control @error('year')is-invalid @enderror" min="1900" max="{{ date('Y') }}" required>
                            @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stock"  id="stock" class="form-control @error('stock')is-invalid @enderror" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="publisher" id="publisher" class="form-control @error('publisher')is-invalid @enderror" required>
                            @error('publisher') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category" id="category" class="form-select @error('category')is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Fiksi">Fiksi</option>
                                <option value="Non-Fiksi">Non-Fiksi</option>
                                <option value="Komputer">Komputer</option>
                                <option value="Pengembangan Diri">Pengembangan Diri</option>
                                <option value="Perpustakaan">Perpustakaan</option>
                                <option value="Sains Fiksi">Sains Fiksi</option>
                                <option value="Sejarah">Sejarah</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

function prepareModal(action, id = null) {
    const form = document.getElementById('bookForm');
    const title = document.getElementById('modalTitle');
    const bookId = document.getElementById('bookId');
    
    if (action === 'create') {
        form.action = '{{ route("books.store") }}';
        bookId.value = '';
        title.textContent = 'Tambah Buku';
        document.getElementById('title').value = '';
        document.getElementById('author').value = '';
        document.getElementById('year').value = '';
        document.getElementById('stock').value = '';
        document.getElementById('publisher').value = '';
        document.getElementById('category').value = '';
    } else {
        // Fetch book data via AJAX
        fetch(`/books/${id}`)
            .then(response => response.json())
            .then(book => {
                form.action = `{{ route('books.update', ['book' => ':id']) }}`.replace(':id', id);
                document.getElementById('formMethod').value = 'PUT';
                
                bookId.value = book.id;
                document.getElementById('title').value = book.title;
                document.getElementById('author').value = book.author;
                document.getElementById('year').value = book.year;
                document.getElementById('stock').value = book.stock;
                document.getElementById('publisher').value = book.publisher || '';
                document.getElementById('category').value = book.category || '';
                title.textContent = 'Edit Buku';
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

</script>
@endsection

