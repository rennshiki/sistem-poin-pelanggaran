<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Profil Akun') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-3xl">
                    <header class="border-b border-stone-100 pb-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Profil</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Informasi akun ditampilkan sebagai data tetap. Hubungi administrator jika ada informasi yang perlu diperbaiki.</p>
                    </header>

                    <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $user->name }}</dd>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Peran</dt>
                            <dd class="mt-1 font-semibold capitalize text-slate-900">{{ $user->role }}</dd>
                        </div>

                        @if ($user->nip)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">NIP</dt>
                                <dd class="mt-1 font-mono font-semibold text-slate-900">{{ $user->nip }}</dd>
                            </div>
                        @endif

                        @if ($user->nisn)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">NISN</dt>
                                <dd class="mt-1 font-mono font-semibold text-slate-900">{{ $user->nisn }}</dd>
                            </div>
                        @endif

                        @if ($user->student)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kelas</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $user->student->class }}</dd>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jurusan</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $user->student->major }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
