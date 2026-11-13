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

        <!-- / NIP / NISN -->
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

            <div class="relative mt-2">
                <x-text-input
                    id="password"
                    class="block w-full pe-12"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />
                <button
                    type="button"
                    id="toggle-password-visibility"
                    class="absolute inset-y-0 end-0 inline-flex items-center px-4 text-slate-500 transition hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-inset"
                    aria-label="Tampilkan kata sandi"
                    aria-controls="password"
                    aria-pressed="false"
                >
                    <svg id="password-eye-icon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="password-eye-off-icon" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.58 10.59a2 2 0 0 0 2.83 2.83M9.88 5.24A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a17.4 17.4 0 0 1-3.13 3.92M6.61 6.61C3.78 8.43 2.25 12 2.25 12s3.5 7 9.75 7a10.6 10.6 0 0 0 5.39-1.48" />
                    </svg>
                </button>
            </div>

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

    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('toggle-password-visibility');
        const passwordEyeIcon = document.getElementById('password-eye-icon');
        const passwordEyeOffIcon = document.getElementById('password-eye-off-icon');

        passwordToggle.addEventListener('click', function () {
            const isVisible = passwordInput.type === 'text';
            passwordInput.type = isVisible ? 'password' : 'text';
            passwordToggle.setAttribute('aria-pressed', String(!isVisible));
            passwordToggle.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
            passwordEyeIcon.classList.toggle('hidden', !isVisible);
            passwordEyeOffIcon.classList.toggle('hidden', isVisible);
        });
    </script>
</x-guest-layout>
