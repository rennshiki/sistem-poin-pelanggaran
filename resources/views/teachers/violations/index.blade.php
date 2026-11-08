<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('guru.dashboard') }}"
                   class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Riwayat Pelanggaran yang Saya Catat
                </h2>
            </div>
            <a href="{{ route('guru.violations.create') }}"
               class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium rounded-md shadow transition">
                + Catat Baru
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-lg text-emerald-700 dark:text-emerald-300 text-sm flex items-center">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                {{-- Search --}}
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <form method="GET" action="{{ route('guru.violations.index') }}" class="flex gap-4">
                        <div class="relative flex-1">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama siswa atau jenis pelanggaran..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="px-5 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                            Cari
                        </button>
                        @if($search)
                            <a href="{{ route('guru.violations.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Siswa</th>
                                <th class="px-6 py-4">Jenis Pelanggaran</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Poin</th>
                                <th class="px-6 py-4">Catatan</th>
                                <th class="px-6 py-4">Foto</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($violations as $i => $v)
                                @php
                                    $catColors = ['ringan' => 'emerald', 'sedang' => 'amber', 'berat' => 'rose'];
                                    $cc = $catColors[$v->violationType->category] ?? 'gray';
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200">{{ $violations->firstItem() + $i }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">{{ $v->violation_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $v->student->user?->name }}</td>
                                    <td class="px-6 py-4">{{ $v->violationType->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $cc }}-100 text-{{ $cc }}-700 dark:bg-{{ $cc }}-900/40 dark:text-{{ $cc }}-300">
                                            {{ ucfirst($v->violationType->category) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs font-bold rounded-md">
                                            {{ $v->points }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400 max-w-xs">
                                        <span class="line-clamp-2">{{ $v->notes ?: '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($v->photo_path)
                                            <a href="{{ route('guru.violations.photo', $v) }}" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Lihat foto</a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form method="POST" action="{{ route('guru.violations.destroy', $v) }}"
                                              onsubmit="return confirm('Hapus catatan pelanggaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-300 rounded-md transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <p class="font-medium">Belum ada catatan pelanggaran</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($violations->hasPages())
                    <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                        {{ $violations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

