<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Siswa &mdash; {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============= KARTU POIN UTAMA ============= --}}
            @php
                $alertConfig = match(true) {
                    $totalPoints >= 75 => ['bg' => 'rose',   'title' => '🚨 Poin Kritis!',    'msg' => 'Poin pelanggaranmu sudah sangat tinggi. Segera perbaiki perilaku untuk menghindari sanksi berat.'],
                    $totalPoints >= 50 => ['bg' => 'amber',  'title' => '⚡ Poin Tinggi',     'msg' => 'Poin pelanggaranmu sudah cukup tinggi. Harap lebih berhati-hati agar tidak bertambah.'],
                    $totalPoints >= 25 => ['bg' => 'orange', 'title' => '⚠️ Perhatian',       'msg' => 'Poin pelanggaranmu mulai bertambah. Jaga perilaku agar tetap dalam batas aman.'],
                    default            => ['bg' => 'emerald','title' => '✅ Poin Aman',        'msg' => 'Poin pelanggaranmu masih dalam batas aman. Pertahankan perilaku yang baik!'],
                };
            @endphp

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Akumulasi Poin Pelanggaran</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            Kelas {{ $student->class }} &bull; {{ $student->major }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-4xl font-black text-{{ $barColor }}-600 dark:text-{{ $barColor }}-400">{{ $totalPoints }}</span>
                        <span class="text-lg text-gray-400 font-normal"> / {{ $maxPoints }}</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">poin pelanggaran</p>
                    </div>
                </div>

                {{-- Progress Bar Besar --}}
                <div class="mb-3">
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                        <span>0</span>
                        <span class="font-medium text-{{ $barColor }}-600 dark:text-{{ $barColor }}-400">{{ number_format($percentage, 1) }}%</span>
                        <span>100</span>
                    </div>
                    <div class="h-5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden relative">
                        <div class="h-full rounded-full transition-all duration-700 ease-out flex items-center justify-end pr-2"
                             style="width: {{ max(2, $percentage) }}%; background-color: {{ match ($barColor) { 'rose' => '#f43f5e', 'amber' => '#f59e0b', 'orange' => '#f97316', default => '#10b981' } }}">
                            @if ($percentage > 15)
                                <span class="text-white text-xs font-bold">{{ $totalPoints }}</span>
                            @endif
                        </div>
                    </div>
                    {{-- Marker zona --}}
                    <div class="relative h-3 mt-1">
                        <div class="absolute left-[25%] h-3 w-px bg-orange-400 opacity-60"></div>
                        <div class="absolute left-[50%] h-3 w-px bg-amber-400 opacity-60"></div>
                        <div class="absolute left-[75%] h-3 w-px bg-rose-400 opacity-60"></div>
                        <span class="absolute left-[25%] -translate-x-1/2 text-[9px] text-orange-400 mt-3">25</span>
                        <span class="absolute left-[50%] -translate-x-1/2 text-[9px] text-amber-400 mt-3">50</span>
                        <span class="absolute left-[75%] -translate-x-1/2 text-[9px] text-rose-400 mt-3">75</span>
                    </div>
                </div>

                {{-- Status Alert --}}
                <div class="mt-5 p-4 bg-{{ $alertConfig['bg'] }}-50 dark:bg-{{ $alertConfig['bg'] }}-950/40 border border-{{ $alertConfig['bg'] }}-200 dark:border-{{ $alertConfig['bg'] }}-800 rounded-lg">
                    <p class="text-sm font-semibold text-{{ $alertConfig['bg'] }}-700 dark:text-{{ $alertConfig['bg'] }}-300">
                        {{ $alertConfig['title'] }}
                    </p>
                    <p class="text-xs text-{{ $alertConfig['bg'] }}-600 dark:text-{{ $alertConfig['bg'] }}-400 mt-0.5">
                        {{ $alertConfig['msg'] }}
                    </p>
                </div>
            </div>

            {{-- ============= RINGKASAN PER KATEGORI ============= --}}
            <div class="grid grid-cols-3 gap-4">
                @php
                    $catMap = [
                        'ringan' => ['label' => 'Ringan',  'color' => 'emerald'],
                        'sedang' => ['label' => 'Sedang',  'color' => 'amber'],
                        'berat'  => ['label' => 'Berat',   'color' => 'rose'],
                    ];
                @endphp
                @foreach ($catMap as $catKey => $catInfo)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 text-center">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $catInfo['label'] }}</p>
                        <p class="text-2xl font-bold text-{{ $catInfo['color'] }}-600 dark:text-{{ $catInfo['color'] }}-400 mt-1">
                            {{ $pointsByCategory[$catKey] ?? 0 }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">poin</p>
                    </div>
                @endforeach
            </div>

            {{-- ============= RIWAYAT PELANGGARAN ============= --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Riwayat Pelanggaran</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">10 catatan terbaru</p>
                </div>

                @if ($recentViolations->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-200 dark:text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tidak ada catatan pelanggaran</p>
                        <p class="text-xs text-gray-400 mt-1">Pertahankan perilaku yang baik! 🎉</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($recentViolations as $v)
                            @php
                                $catColors = ['ringan' => 'emerald', 'sedang' => 'amber', 'berat' => 'rose'];
                                $cc = $catColors[$v->violationType->category] ?? 'gray';
                            @endphp
                            <div class="px-6 py-4 flex items-start gap-4">
                                {{-- Dot kategori --}}
                                <div class="mt-1 w-2.5 h-2.5 rounded-full bg-{{ $cc }}-500 shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $v->violationType->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Dicatat oleh: <span class="font-medium">{{ $v->teacher->name }}</span>
                                                &bull; {{ $v->violation_date->format('d M Y') }}
                                            </p>
                                            @if ($v->notes)
                                                <p class="text-xs text-gray-400 italic mt-0.5">{{ $v->notes }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="inline-block px-2.5 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs font-bold rounded-md">
                                                +{{ $v->points }} poin
                                            </span>
                                            <span class="block mt-1 text-xs px-2 py-0.5 rounded-full inline-block bg-{{ $cc }}-100 text-{{ $cc }}-700 dark:bg-{{ $cc }}-900/30 dark:text-{{ $cc }}-300">
                                                {{ ucfirst($v->violationType->category) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

