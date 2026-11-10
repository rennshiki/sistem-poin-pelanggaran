<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">Catat Pelanggaran</h2>
            <a href="{{ route('guru.violations.create') }}" class="inline-flex shrink-0 items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                + Catat Pelanggaran
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-6">
                    <h3 class="text-base font-bold text-slate-900">Cari Siswa untuk Dicatat</h3>
                    <p class="mt-1 text-sm text-slate-500">Pencarian hanya menampilkan siswa yang sudah pernah tercatat. Tanpa pencarian, tabel menampilkan riwayat catatan Anda.</p>
                    <form method="GET" action="{{ route('guru.violations.students') }}" class="mt-4 flex flex-col gap-3 sm:flex-row">
                        <div class="relative flex-1">
                            <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, NISN, kelas, atau jurusan siswa..."
                                class="w-full rounded-lg border border-slate-300 bg-slate-50 py-2 pl-10 pr-4 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Cari</button>
                        @if ($search)
                            <a href="{{ route('guru.violations.students') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Tampilkan Riwayat</a>
                        @endif
                    </form>
                </div>

                @if ($search)
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h3 class="text-sm font-bold text-slate-900">Hasil Pencarian Siswa yang Pernah Tercatat</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="border-b border-slate-100 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4">NISN</th>
                                    <th class="px-6 py-4">Kelas / Jurusan</th>
                                    <th class="px-6 py-4">Catatan / Poin</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($students as $index => $student)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-6 py-4 font-medium text-slate-500">{{ $students->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $student->user?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 font-mono text-xs">{{ $student->user?->nisn ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $student->class }} <span class="text-slate-400">·</span> {{ $student->major }}</td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-slate-900">{{ $student->violations_count ?? 0 }} catatan</span>
                                            <span class="text-slate-500">· {{ $student->total_points ?? 0 }} poin</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('guru.violations.create', ['student_id' => $student->id]) }}" class="inline-flex items-center rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">Catat Pelanggaran</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">Siswa tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($students->hasPages())
                        <div class="border-t border-slate-100 p-5">{{ $students->links() }}</div>
                    @endif
                @else
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Catatan Pelanggaran</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="border-b border-slate-100 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Siswa</th>
                                    <th class="px-6 py-4">Pelanggaran</th>
                                    <th class="px-6 py-4">Poin</th>
                                    <th class="px-6 py-4">Catatan / Foto</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($violations as $violation)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $violation->violation_date->format('d M Y') }}</td>
                                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $violation->student->user?->name ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-slate-900">{{ $violation->violationType->name }}</span>
                                            <span class="mt-1 block text-xs capitalize text-slate-500">{{ $violation->violationType->category }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-blue-700">{{ $violation->points }} poin</td>
                                        <td class="max-w-xs px-6 py-4 text-xs text-slate-500">
                                            <span class="line-clamp-2">{{ $violation->notes ?: '—' }}</span>
                                            @if ($violation->photo_path)
                                                <a href="{{ route('guru.violations.photo', $violation) }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-block font-semibold text-blue-600 hover:text-blue-800">Lihat foto</a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div x-data="{ deletePromptOpen: false }">
                                                <form id="delete-violation-{{ $violation->id }}" method="POST" action="{{ route('guru.violations.destroy', $violation) }}" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" @click="deletePromptOpen = true" class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">Hapus</button>
                                                <div x-cloak x-show="deletePromptOpen" x-transition.opacity
                                                    @keydown.escape.window="deletePromptOpen = false"
                                                    class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/45 p-4 text-left" role="presentation">
                                                    <div @click.outside="deletePromptOpen = false" @click.stop class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200" role="dialog" aria-modal="true" aria-labelledby="delete-violation-title-{{ $violation->id }}">
                                                        <h4 id="delete-violation-title-{{ $violation->id }}" class="text-lg font-bold text-slate-900">Hapus catatan pelanggaran?</h4>
                                                        <p class="mt-2 text-sm leading-6 text-slate-600">Catatan untuk {{ $violation->student->user?->name }} akan dihapus dan poinnya dikurangi dari akumulasi siswa.</p>
                                                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                                            <button type="button" @click="deletePromptOpen = false" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                                                            <button type="submit" form="delete-violation-{{ $violation->id }}" class="rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700">Ya, hapus</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">Belum ada catatan pelanggaran yang Anda buat.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($violations->hasPages())
                        <div class="border-t border-slate-100 p-5">{{ $violations->links() }}</div>
                    @endif
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
