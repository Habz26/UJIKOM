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

        <div>
            <x-input-label for="role" :value="__('Role')" class="text-yellow-900" />
            <select id="role" name="role" class="block mt-1 w-full border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                <option value="user" {{ auth()->user()->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ auth()->user()->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-secondary-button>
                {{ __('Update Role') }}
            </x-secondary-button>

            @if (session('status') === 'Role updated successfully!')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-yellow-600"
                >{{ session('status') }}</p>
                
            @endif
        </div>
    </form>
</section>