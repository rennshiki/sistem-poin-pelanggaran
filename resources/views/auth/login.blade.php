<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold text-blue-600">Selamat datang kembali</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Masuk ke akun</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Masukkan NIP atau NISN dan kata sandi untuk melanjutkan.</p>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username / NIP / NISN -->
        <div>
            <x-input-label for="login" :value="__('NIP / NISN')" />

            <x-text-input
                id="login"
                class="mt-2 block w-full"
                type="text"
                name="login"
                :value="old('login')"
                required
                autofocus
                autocomplete="username"
            />

            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata sandi" />

            <x-text-input
                id="password"
                class="mt-2 block w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500"
                    name="remember"
                >

                <span class="ms-2 text-sm text-slate-600">
                    Ingat saya
                </span>
            </label>
        </div>

        <div class="mt-7">
            <x-primary-button class="w-full justify-center py-3 text-sm normal-case tracking-normal">
                Masuk
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
