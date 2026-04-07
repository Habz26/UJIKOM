<form method="post" action="{{ route('profile.update') }}" class="space-y-4">
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
</form>
