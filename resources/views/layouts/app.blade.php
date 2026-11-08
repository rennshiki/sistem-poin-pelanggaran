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
        <div x-data="{ sidebarOpen: false, logoutPromptOpen: false }" class="min-h-screen bg-[#e7a087] p-0 md:p-6">
            <div class="mx-auto min-h-screen max-w-[1680px] overflow-hidden bg-white shadow-2xl md:min-h-[calc(100vh-3rem)] md:rounded-[32px]">
                <div class="flex min-h-screen flex-col md:min-h-[calc(100vh-3rem)]">
                    <div class="flex h-16 items-center justify-between bg-[#f4ece2] px-4 md:hidden">
                        <span class="font-serif text-lg font-bold text-stone-900">Sistem Poin</span>
                        <button type="button" @click="sidebarOpen = !sidebarOpen"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-stone-700 hover:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500"
                            aria-label="Buka navigasi" :aria-expanded="sidebarOpen.toString()" aria-controls="app-sidebar">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex min-h-0 flex-1">
                        @include('layouts.app-sidebar')

                        <div class="min-w-0 flex-1 bg-white">
                            @isset($header)
                                <header class="border-b border-stone-100 bg-white">
                                    <div class="mx-auto max-w-7xl px-5 py-7 sm:px-8 lg:px-10">
                                        {{ $header }}
                                    </div>
                                </header>
                            @endisset

                            <main class="min-h-[calc(100vh-8rem)]">
                                {{ $slot }}
                            </main>
                        </div>
                    </div>
                </div>

                <div x-cloak x-show="logoutPromptOpen" x-transition.opacity
                    @keydown.escape.window="logoutPromptOpen = false"
                    class="fixed inset-0 z-[70] flex items-center justify-center bg-stone-950/45 p-4"
                    role="presentation">
                    <div @click.outside="logoutPromptOpen = false" @click.stop
                        class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl sm:p-7"
                        role="dialog" aria-modal="true" aria-labelledby="logout-dialog-title">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 012 2v14a2 2 0 01-2 2h-6" />
                            </svg>
                        </div>
                        <h2 id="logout-dialog-title" class="mt-4 text-xl font-bold text-stone-900">Keluar dari akun?</h2>
                        <p class="mt-2 text-sm leading-6 text-stone-600">Kamu perlu masuk kembali untuk menggunakan aplikasi.</p>
                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button type="button" @click="logoutPromptOpen = false"
                                class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                Batal
                            </button>
                            <button type="submit" form="logout-form"
                                class="rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2">
                                Ya, keluar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
