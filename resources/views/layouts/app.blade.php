<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false, logoutPromptOpen: false }" class="min-h-screen bg-slate-50 p-0 text-slate-900">
            <div class="min-h-screen w-full overflow-hidden bg-white">
                <div class="flex min-h-screen flex-col">
                    <div class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 md:hidden">
                        <span class="text-lg font-bold tracking-tight text-slate-900">Poin Siswa</span>
                        <button type="button" @click="sidebarOpen = !sidebarOpen"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            aria-label="Buka navigasi" :aria-expanded="sidebarOpen.toString()" aria-controls="app-sidebar">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex min-h-0 flex-1">
                        @include('layouts.app-sidebar')

                        <div class="min-w-0 flex-1 bg-slate-50">
                            @isset($header)
                                <header class="border-b border-slate-200 bg-white">
                                    <div class="mx-auto max-w-[1500px] px-5 py-5 sm:px-8 lg:px-10">
                                        {{ $header }}
                                    </div>
                                </header>
                            @endisset

                            <main class="app-content min-h-[calc(100vh-8rem)]">
                                {{ $slot }}
                            </main>
                        </div>
                    </div>
                </div>

                <div x-cloak x-show="logoutPromptOpen" x-transition.opacity
                    @keydown.escape.window="logoutPromptOpen = false"
                    class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/45 p-4"
                    role="presentation">
                    <div @click.outside="logoutPromptOpen = false" @click.stop
                        class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 sm:p-7"
                        role="dialog" aria-modal="true" aria-labelledby="logout-dialog-title">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 012 2v14a2 2 0 01-2 2h-6" />
                            </svg>
                        </div>
                        <h2 id="logout-dialog-title" class="mt-4 text-xl font-bold text-slate-900">Keluar dari akun?</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Kamu perlu masuk kembali untuk menggunakan aplikasi.</p>
                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button type="button" @click="logoutPromptOpen = false"
                                class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Batal
                            </button>
                            <button type="submit" form="logout-form"
                                class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                Ya, keluar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
