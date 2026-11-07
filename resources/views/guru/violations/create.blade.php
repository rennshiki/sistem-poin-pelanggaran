<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('guru.dashboard') }}"
               class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Catat Pelanggaran Siswa
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-8 border border-gray-100 dark:border-gray-700">
                <div class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Form Pencatatan Pelanggaran</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Poin akan otomatis ditambahkan ke akumulasi poin siswa.
                    </p>
                </div>

                <form method="POST" action="{{ route('guru.violations.store') }}" class="space-y-6">
                    @csrf

                    {{-- Siswa --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Siswa</label>
                        <select name="student_id" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}"
                                    @selected(old('student_id', $selectedStudent) == $student->id)>
                                    {{ $student->user?->name }} &mdash; Kelas {{ $student->class }} ({{ $student->major }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Jenis Pelanggaran --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Pelanggaran</label>
                        <select name="violation_type_id" id="vt_select" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">-- Pilih Jenis Pelanggaran --</option>
                            @foreach ($violationTypes->groupBy('category') as $cat => $types)
                                <optgroup label="{{ ucfirst($cat) }}">
                                    @foreach ($types as $vt)
                                        <option value="{{ $vt->id }}" data-points="{{ $vt->points }}"
                                            @selected(old('violation_type_id') == $vt->id)>
                                            {{ $vt->name }} ({{ $vt->points }} poin)
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('violation_type_id')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror

                        <div id="pts_preview" class="hidden mt-2 px-4 py-2.5 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <span class="text-sm text-orange-700 dark:text-orange-300">
                                Poin ditambahkan: <strong id="pts_val" class="text-lg">0</strong>
                            </span>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Kejadian</label>
                        <input type="date" name="violation_date" required
                            value="{{ old('violation_date', now()->format('Y-m-d')) }}"
                            max="{{ now()->format('Y-m-d') }}"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        @error('violation_date')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Catatan Tambahan <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="notes" rows="3"
                            placeholder="Deskripsi singkat mengenai kejadian pelanggaran..."
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm resize-none">{{ old('notes') }}</textarea>
                        @error('notes')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('guru.dashboard') }}"
                           class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Simpan Pelanggaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const sel = document.getElementById('vt_select');
        const preview = document.getElementById('pts_preview');
        const val = document.getElementById('pts_val');
        sel.addEventListener('change', function () {
            const pts = this.options[this.selectedIndex].dataset.points;
            if (pts) { val.textContent = pts; preview.classList.remove('hidden'); }
            else preview.classList.add('hidden');
        });
    </script>
</x-app-layout>

