<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Guru &mdash; {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alert --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-lg text-emerald-700 dark:text-emerald-300 text-sm flex items-center">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Pelanggaran Dicatat</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $totalViolationsAdded }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Bulan Ini</p>
                    <p class="text-3xl font-bold text-amber-500 dark:text-amber-400 mt-1">{{ $thisMonthCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Siswa</p>
                    <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $students->count() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- ===================== FORM TAMBAH PELANGGARAN ===================== --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-6 sticky top-6">
                        <div class="mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                Catat Pelanggaran Siswa
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Isi form berikut untuk mencatat pelanggaran.</p>
                        </div>

                        <form method="POST" action="{{ route('guru.violations.store') }}" class="space-y-4">
                            @csrf

                            {{-- Pilih Siswa --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa</label>
                                <select name="student_id" required
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                            {{ $student->user?->name }} &mdash; {{ $student->class }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Jenis Pelanggaran --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Pelanggaran</label>
                                <select name="violation_type_id" id="violation_type_select" required
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                                    @foreach ($violationTypes->groupBy('category') as $cat => $types)
                                        <optgroup label="{{ ucfirst($cat) }}">
                                            @foreach ($types as $vt)
                                                <option value="{{ $vt->id }}" data-points="{{ $vt->points }}" @selected(old('violation_type_id') == $vt->id)>
                                                    {{ $vt->name }} ({{ $vt->points }} poin)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('violation_type_id')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror

                                {{-- Preview poin --}}
                                <div id="points_preview" class="hidden mt-2 px-3 py-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                                    <p class="text-xs text-orange-700 dark:text-orange-300 font-medium">
                                        Poin yang akan ditambahkan: <span id="points_value" class="text-lg font-bold">0</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Kejadian</label>
                                <input type="date" name="violation_date" required
                                    value="{{ old('violation_date', now()->format('Y-m-d')) }}"
                                    max="{{ now()->format('Y-m-d') }}"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                @error('violation_date')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan Tambahan <span class="text-gray-400">(opsional)</span></label>
                                <textarea name="notes" rows="2" placeholder="Deskripsi singkat kejadian..."
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm resize-none">{{ old('notes') }}</textarea>
                                @error('notes')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <button type="submit"
                                class="w-full px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-lg shadow transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Catatan Pelanggaran
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ===================== DAFTAR SISWA + POIN ===================== --}}
                <div class="lg:col-span-3 space-y-5">

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

    {{-- Script preview poin --}}
    <script>
        const sel = document.getElementById('violation_type_select');
        const preview = document.getElementById('points_preview');
        const val = document.getElementById('points_value');

        sel.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const pts = opt.dataset.points;
            if (pts) {
                val.textContent = pts;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>

