<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div x-data="{ logoutPromptOpen: false }" class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form id="verification-logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>

        <button type="button" @click="logoutPromptOpen = true" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            {{ __('Log Out') }}
        </button>

        <div x-cloak x-show="logoutPromptOpen" x-transition.opacity
            @keydown.escape.window="logoutPromptOpen = false"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-stone-950/45 p-4"
            role="presentation">
            <div @click.outside="logoutPromptOpen = false" @click.stop
                class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl"
                role="dialog" aria-modal="true" aria-labelledby="verification-logout-title">
                <h2 id="verification-logout-title" class="text-xl font-bold text-stone-900">Keluar dari akun?</h2>
                <p class="mt-2 text-sm leading-6 text-stone-600">Kamu perlu masuk kembali untuk menggunakan aplikasi.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="logoutPromptOpen = false" class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:bg-stone-50">Batal</button>
                    <button type="submit" form="verification-logout-form" class="rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-600">Ya, keluar</button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
