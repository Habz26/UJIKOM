<section>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-tags me-2 text-primary"></i> Master Kategori
            </h3>
        </div>

        <!-- Search -->
        <div class="card mb-4 shadow-sm rounded-3">
            <div class="card-body">
                <form method="GET" action="{{ route('profile.edit') }}">
                    <input type="hidden" name="tab" value="categories">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari kategori..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                        @if (request('search'))
                            <a href="{{ route('profile.edit') }}?tab=categories" class="btn btn-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-transparent border-0 pb-0">
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Kategori
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Buku</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td><strong>{!! $category->highlightName(request('search')) !!}</strong></td>
                                    <td>{{ Str::limit($category->description ?? 'Tidak ada deskripsi', 50) }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $category->books()->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#categoryModal" data-category-id="{{ $category->id }}"
                                            data-category-name='@json($category->name)'
                                            data-category-description='@json($category->description)'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" action="{{ url('/categories/' . $category->id) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus kategori ini? Buku terkait akan kehilangan kategori.');">
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
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-tags fs-1 d-block mb-2"></i>
                                        Belum ada kategori.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-3">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- Category Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalTitle">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="categoryForm" method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="categoryMethod" value="POST">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="categoryId">
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name')is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control @error('description')is-invalid @enderror"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('categoryModal');

            modal.addEventListener('shown.bs.modal', function(event) {
                const button = event.relatedTarget;

                // MODE TAMBAH
                if (!button || !button.hasAttribute('data-category-id')) {
                    document.getElementById('categoryModalTitle').textContent = 'Tambah Kategori';
                    document.getElementById('categoryForm').action = '{{ route('categories.store') }}';
                    document.getElementById('categoryMethod').value = 'POST';
                    document.getElementById('categoryId').value = '';
                    document.getElementById('name').value = '';
                    document.getElementById('description').value = '';
                    return;
                }

                // MODE EDIT
                const id = button.getAttribute('data-category-id');
                const name = JSON.parse(button.getAttribute('data-category-name'));
                const description = JSON.parse(button.getAttribute('data-category-description'));

                document.getElementById('categoryModalTitle').textContent = 'Edit Kategori';
                document.getElementById('categoryForm').action = '/categories/' + id;
                document.getElementById('categoryMethod').value = 'PUT';
                document.getElementById('categoryId').value = id;
                document.getElementById('name').value = name;
                document.getElementById('description').value = description ?? '';

                console.log('Populating modal:', {
                    id,
                    name,
                    description
                });
            });
        });
    </script>
</section>
