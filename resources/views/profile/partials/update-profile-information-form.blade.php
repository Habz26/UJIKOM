<form method="post" action="{{ route('profile.update') }}" class="space-y-4" enctype="multipart/form-data">
    @csrf
    @method('patch')


    <div class="mb-4">
        <label class="form-label fw-bold fs-5">Nama</label>
        <input id="name" name="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold fs-5">Foto Profil</label>
        <div class="text-center mb-3">
            <img src="{{ $user->photo_url }}" alt="Foto Profil Saat Ini" class="rounded-circle border p-1" style="width: 120px; height: 120px; object-fit: cover;">
        </div>
        <input id="photo" name="photo" type="file" class="form-control form-control-lg @error('photo') is-invalid @enderror" accept="image/*">
        @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Maksimal 2MB. Format JPG, PNG, GIF. Gunakan tombol Crop untuk mengedit.</small>
        
        <!-- Crop Modal -->
        <div class="modal fade" id="cropModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crop Foto Profil (1:1 Square)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <img id="crop-image" style="max-width:100%;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="crop-btn">Crop &amp; Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="photo-preview" class="mt-3 text-center" style="display: none;">
            <img id="preview-img" class="rounded-circle border p-1" style="width: 120px; height: 120px; object-fit: cover;">
            <div class="mt-2">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="openCropModal()">Edit Crop</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="resetPreview()">Reset</button>
            </div>
        </div>
        <!-- Hidden input for cropped photo -->
        <input type="hidden" id="cropped-photo" name="cropped_photo">
        <canvas id="crop-canvas" style="display:none;" width="120" height="120"></canvas>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold fs-5">Email</label>
        <input id="email" name="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username" />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 p-3 bg-warning bg-opacity-10 border border-warning rounded">
                <p class="mb-2 text-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Email Anda belum terverifikasi.
                </p>
        <button type="button" class="btn btn-outline-warning btn-sm" onclick="document.getElementById('send-verification').submit();">
                    <i class="bi bi-arrow-repeat me-1"></i>Kirim ulang verifikasi
                </button>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2 mb-0">
                        Link verifikasi baru telah dikirim ke email Anda.
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex gap-3 justify-content-end">
        <button type="submit" class="btn btn-primary btn-lg px-5">
            <i class="bi bi-check-circle me-2"></i> Simpan
        </button>
    </div>
    
@if (session('status') === 'profile-updated')
    <div class="alert alert-success mt-3">
        <i class="bi bi-check-circle-fill me-1"></i> Disimpan!
    </div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

<script>
let cropper = null;
let originalFile = null;

document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        originalFile = file;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('photo-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

function openCropModal() {
    if (originalFile) {
        const modal = new bootstrap.Modal(document.getElementById('cropModal'));
        const cropImage = document.getElementById('crop-image');
        cropImage.src = URL.createObjectURL(originalFile);
        
        document.getElementById('cropModal').addEventListener('shown.bs.modal', function handler() {
            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.8,
                dragMode: 'move',
                cropBoxResizable: true,
                cropBoxMovable: true,
                minCropBoxWidth: 100,
                minCropBoxHeight: 100,
                preview: '#preview-img'
            });
            document.getElementById('cropModal').removeEventListener('shown.bs.modal', handler);
        }, { once: true });
        
        modal.show();
    }
}

document.getElementById('crop-btn').addEventListener('click', function() {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas({width: 120, height: 120});
        canvas.toBlob(function(blob) {
            const croppedFile = new File([blob], 'profile.jpg', { type: 'image/jpeg' });
            const dt = new DataTransfer();
            dt.items.add(croppedFile);
            document.getElementById('photo').files = dt.files;
            
            // Update preview
            document.getElementById('preview-img').src = canvas.toDataURL();
            
            // Set hidden input
            document.getElementById('cropped-photo').value = canvas.toDataURL('image/jpeg', 0.8);
        }, 'image/jpeg', 0.8);
        
        cropper.destroy();
        cropper = null;
        bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
    }
});

function resetPreview() {
    document.getElementById('photo-preview').style.display = 'none';
    document.getElementById('photo').value = '';
    originalFile = null;
}
</script>
</form>
