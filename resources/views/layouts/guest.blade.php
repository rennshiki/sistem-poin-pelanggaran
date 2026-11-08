<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Poin Siswa') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        <main class="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-10 sm:px-6">
            <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-slate-200 lg:min-h-[620px] lg:grid-cols-[1fr_1.05fr]">
                <section class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 p-10 text-white lg:flex">
                    <div class="absolute -right-20 -top-16 h-72 w-72 rounded-full border-[40px] border-white/10"></div>
                    <div class="absolute -bottom-28 -left-20 h-80 w-80 rounded-full border-[50px] border-white/10"></div>
                    <a href="/" class="relative flex items-center gap-3 text-xl font-bold tracking-tight">
                        <span class="grid h-10 w-10 grid-cols-2 gap-1 rounded-xl bg-white/15 p-2">
                            <i class="rounded-sm bg-white"></i><i class="rounded-sm bg-sky-200"></i>
                            <i class="rounded-sm bg-blue-200"></i><i class="rounded-sm bg-white/80"></i>
                        </span>
                        Poin Siswa
                    </a>
                    <div class="relative max-w-md pb-4">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">Sistem Informasi Sekolah</p>
                        <h1 class="mt-4 text-4xl font-bold leading-tight tracking-tight">Pantau poin, dukung perubahan positif.</h1>
                        <p class="mt-4 text-base leading-7 text-blue-100">Kelola catatan pelanggaran siswa dengan rapi dan mudah diakses oleh pihak yang berwenang.</p>
                    </div>
                    <p class="relative text-xs text-blue-100/80">Sistem Poin Siswa &copy; {{ date('Y') }}</p>
                </section>

                <section class="flex items-center justify-center px-6 py-10 sm:px-12 lg:px-14">
                    <div class="w-full max-w-md">
                        <a href="/" class="mb-9 flex items-center gap-3 text-lg font-bold tracking-tight text-slate-900 lg:hidden">
                            <span class="grid h-9 w-9 grid-cols-2 gap-1 rounded-xl bg-blue-50 p-1.5">
                                <i class="rounded-sm bg-blue-600"></i><i class="rounded-sm bg-sky-400"></i>
                                <i class="rounded-sm bg-sky-500"></i><i class="rounded-sm bg-indigo-400"></i>
                            </span>
                            Poin Siswa
                        </a>
                        {{ $slot }}
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
