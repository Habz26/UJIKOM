<section>
    <header>
        <h2 class="text-lg font-medium text-yellow-900">
            {{ __('Update Role') }}
        </h2>

        <p class="mt-1 text-sm text-yellow-600">
            {{ __('Change your account role. This will affect your permissions and access to certain features.') }}
        </p>
    </header>

    <form method="post" action="{{ route('role.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')


    <form method="post" action="{{ route('role.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div class="mb-4">
            <label class="form-label fw-bold fs-5" for="role">Role</label>
            <select id="role" name="role" class="form-select form-select-lg @error('role') is-invalid @enderror">
                <option value="user" {{ auth()->user()->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ auth()->user()->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-3 justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-person-badge me-2"></i> Update Role
            </button>
            @if (session('status') === 'Role updated successfully!')
                <p class="align-self-end mb-0 text-success fw-bold" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                    <i class="bi bi-check-circle-fill me-1"></i> Role updated!
                </p>
            @endif
        </div>
    </form>

    </form>
</section>