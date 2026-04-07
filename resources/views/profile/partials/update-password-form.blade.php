<section>
    <header>
        <h2 class="text-lg font-medium text-yellow-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-yellow-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div class="mb-4">
            <label class="form-label fw-bold fs-5" for="update_password_current_password">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control form-control-lg @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold fs-5" for="update_password_password">Password Baru</label>
            <input id="update_password_password" name="password" type="password" class="form-control form-control-lg @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password" />
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold fs-5" for="update_password_password_confirmation">Konfirmasi Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control form-control-lg @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-3 justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-check-circle me-2"></i> Simpan Password
            </button>
@if (session('status') === 'password-updated')
                <div class="alert alert-success mt-2 mb-0">
                    <i class="bi bi-check-circle-fill me-1"></i> Password disimpan!
                </div>
            @endif
        </div>
    </form>
</section>
