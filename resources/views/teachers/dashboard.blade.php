<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Guru &mdash; {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alert --}}
            @if (session('success'))
                <div class="flex items-center rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 text-slate-900 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Total Pelanggaran Dicatat</p>
                            <p class="mt-2 text-4xl font-black text-slate-900">{{ $totalViolationsAdded }}</p>
                            <p class="mt-1 text-sm text-slate-500">Sepanjang waktu</p>
                        </div>
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5h6m-7 3h8m-9 4h10m-9 4h8M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 text-slate-900 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Bulan Ini</p>
                            <p class="mt-2 text-4xl font-black text-slate-900">{{ $thisMonthCount }}</p>
                            <p class="mt-1 text-sm text-slate-500">Pelanggaran tercatat</p>
                        </div>
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3v3m8-3v3M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 text-slate-900 shadow-sm sm:col-span-2 xl:col-span-1">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Total Siswa</p>
                            <p class="mt-2 text-4xl font-black text-slate-900">{{ $students->count() }}</p>
                            <p class="mt-1 text-sm text-slate-500">Siswa terdaftar</p>
                        </div>
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 110-8 4 4 0 010 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- ===================== DAFTAR SISWA + POIN ===================== --}}
                <div class="lg:col-span-5 space-y-5">

                    {{-- Pelanggaran Terbaru --}}
                    @if ($recentViolations->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Catatan Terbaru Anda</h3>
                            <a href="{{ route('guru.violations.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat semua →</a>
                        </div>
                        <div class="space-y-2">
                            @foreach ($recentViolations as $v)
                                <div class="flex items-start justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 gap-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $v->student->user?->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $v->violationType->name }}</p>
                                        @if ($v->notes)
                                            <p class="text-xs text-gray-400 italic truncate">{{ $v->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="inline-block px-2 py-0.5 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs font-bold rounded">
                                            +{{ $v->points }} poin
                                        </span>
                                        <p class="text-xs text-gray-400 mt-1">{{ $v->violation_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Daftar Siswa + Bar Poin --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Akumulasi Poin Semua Siswa</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Klik "Catat" untuk mencatat pelanggaran siswa tersebut.</p>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($students as $student)
                                @php
                                    $pts = $student->total_pts;
                                    $pct = min(100, $pts);
                                    $color = match(true) {
                                        $pts >= 75 => 'rose',
                                        $pts >= 50 => 'amber',
                                        $pts >= 25 => 'orange',
                                        default    => 'emerald',
                                    };
                                @endphp
                                <div class="px-6 py-4 flex items-center gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->user?->name }}</span>
                                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">{{ $student->class }} &bull; {{ $student->major }}</span>
                                            </div>
                                            <span class="text-sm font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400 shrink-0 ml-2">
                                                {{ $pts }} <span class="text-xs font-normal text-gray-400">/ 100 poin</span>
                                            </span>
                                        </div>
                                        {{-- Progress Bar --}}
                                        <div class="h-2.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-{{ $color }}-500 rounded-full transition-all duration-500"
                                                 style="width: {{ $pct }}%"></div>
                                        </div>
                                        @if ($pts >= 75)
                                            <p class="text-xs text-rose-500 mt-1 font-medium">⚠️ Poin kritis! Perlu tindakan segera.</p>
                                        @elseif ($pts >= 50)
                                            <p class="text-xs text-amber-500 mt-1">⚡ Poin tinggi. Perlu perhatian.</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('guru.violations.create', ['student_id' => $student->id]) }}"
                                       class="shrink-0 inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-sm transition">
                                        + Catat
                                    </a>
                                </div>
                            @empty
                                <div class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada data siswa terdaftar.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

