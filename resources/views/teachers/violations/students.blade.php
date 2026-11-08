<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pilih Siswa untuk Mencatat Pelanggaran
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Cari siswa terlebih dahulu, lalu pilih <strong>Catat</strong> di samping namanya.</p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <form method="GET" action="{{ route('guru.violations.students') }}" class="flex flex-col gap-3 sm:flex-row">
                        <div class="relative flex-1">
                            <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, NISN, kelas, atau jurusan siswa..."
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pl-10 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="rounded-lg bg-gray-800 px-5 py-2 text-sm font-medium text-white transition hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white">Cari</button>
                        @if ($search)
                            <a href="{{ route('guru.violations.students') }}" class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="border-b border-gray-100 bg-gray-50 text-xs font-semibold uppercase text-gray-500 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4">NISN</th>
                                <th class="px-6 py-4">Kelas</th>
                                <th class="px-6 py-4">Jurusan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($students as $index => $student)
                                <tr class="transition hover:bg-gray-50/50 dark:hover:bg-gray-750">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200">{{ $students->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $student->user?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 font-mono text-xs">{{ $student->user?->nisn ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $student->class }}</td>
                                    <td class="px-6 py-4">{{ $student->major }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('guru.violations.create', ['student_id' => $student->id]) }}" class="inline-flex items-center rounded-lg bg-rose-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                                            Catat Pelanggaran
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        {{ $search ? 'Siswa tidak ditemukan.' : 'Belum ada data siswa.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($students->hasPages())
                    <div class="border-t border-gray-100 p-6 dark:border-gray-700">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
