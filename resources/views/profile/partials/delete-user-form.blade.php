
<div class="text-center mb-4">
    <button type="button" class="btn btn-danger btn-lg px-5" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        <i class="bi bi-trash me-2"></i> Hapus Akun
    </button>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h1 class="modal-title fs-5" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Konfirmasi Hapus Akun
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body text-danger">
                    <p class="fs-6 mb-4">
                        Apakah Anda yakin ingin menghapus akun ini? Semua data akan hilang permanen.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="delete_password">Password</label>
                        <input id="delete_password" name="password" type="password" class="form-control form-control-lg @error('password', 'userDeletion') is-invalid @enderror" placeholder="Masukkan password untuk konfirmasi" required />
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger btn-lg px-5">
                        <i class="bi bi-trash-fill me-2"></i> Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

