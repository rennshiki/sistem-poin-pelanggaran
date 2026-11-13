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

                <form id="violation-form" method="POST" action="{{ route('guru.violations.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Siswa --}}
                    <div>
                        <label for="student_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Nama Siswa</label>
                        <input type="search" id="student_search" name="student_search" autocomplete="off"
                            value="{{ old('student_search', $selectedStudent?->user?->name ?? '') }}"
                            placeholder="Ketik nama atau NISN siswa..."
                            aria-controls="student_search_results" aria-expanded="false"
                            class="block w-full rounded-lg border-gray-300 bg-white text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="student_class" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                                <select id="student_class" name="student_class" class="block w-full rounded-lg border-gray-300 bg-white text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class }}" @selected(old('student_class', $selectedStudent?->class) === $class)>{{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="student_major" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Jurusan</label>
                                <select id="student_major" name="student_major" class="block w-full rounded-lg border-gray-300 bg-white text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Jurusan</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="student_id" id="student_id" value="{{ old('student_id', $selectedStudent?->id ?? '') }}">
                        <div id="student_search_results" class="mt-2 hidden max-h-56 overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-lg" role="listbox" aria-label="Hasil pencarian siswa"></div>
                        <p id="student_picker_status" class="mt-2 text-xs text-slate-500" aria-live="polite">
                            @if ($selectedStudent)
                                Siswa terpilih: {{ $selectedStudent->user?->name }} — Kelas {{ $selectedStudent->class }}, {{ $selectedStudent->major }}.
                            @else
                                Cari dan pilih nama siswa dari hasil pencarian.
                            @endif
                        </p>
                        <p id="student_picker_error" class="mt-1 hidden text-xs text-rose-600">Pilih siswa dari hasil pencarian sebelum menyimpan.</p>
                        @error('student_id')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Kategori dan Jenis Pelanggaran --}}
                    <div>
                        <label for="category_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Pelanggaran</label>
                        <select name="category" id="category_select" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="ringan" @selected(old('category') === 'ringan')>Ringan — 5 poin</option>
                            <option value="sedang" @selected(old('category') === 'sedang')>Sedang — 15 poin</option>
                            <option value="berat" @selected(old('category') === 'berat')>Berat — 50 poin</option>
                        </select>
                        @error('category')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="vt_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pelanggaran</label>
                        <select name="violation_type_id" id="vt_select" required disabled
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm disabled:cursor-not-allowed disabled:opacity-60">
                            <option value="">-- Pilih kategori terlebih dahulu --</option>
                            @foreach ($violationTypes as $vt)
                                <option value="{{ $vt->id }}" data-category="{{ $vt->category }}"
                                    @selected(old('violation_type_id') == $vt->id)>
                                    {{ $vt->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('violation_type_id')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror

                        <div id="pts_preview" class="hidden mt-3 px-4 py-2.5 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <span class="text-sm text-blue-700">
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

                    {{-- Foto Bukti --}}
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Foto Bukti <span class="text-gray-400 font-normal">(opsional, JPG/PNG/WEBP, maks. 5 MB)</span>
                        </label>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp"
                            class="block w-full rounded-md border border-gray-300 bg-white text-sm text-gray-700 shadow-sm file:mr-4 file:rounded-l-md file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm file:font-medium hover:file:bg-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:file:bg-gray-600 dark:file:text-gray-100">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Unggah foto sebagai bukti kejadian pelanggaran.</p>
                        @error('photo')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
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
        const studentSearch = document.getElementById('student_search');
        const studentClass = document.getElementById('student_class');
        const studentMajor = document.getElementById('student_major');
        const studentId = document.getElementById('student_id');
        const studentResults = document.getElementById('student_search_results');
        const studentStatus = document.getElementById('student_picker_status');
        const studentPickerError = document.getElementById('student_picker_error');
        const studentForm = document.getElementById('violation-form');
        const students = @js($students->map(fn ($student) => [
            'id' => (string) $student->id,
            'name' => $student->user?->name ?? '',
            'nisn' => $student->user?->nisn ?? '',
            'class' => $student->class ?? '',
            'major' => $student->major ?? '',
        ])->values());
        const majorsByClass = @js($majorsByClass);
        const initialMajor = @js(old('student_major', $selectedStudent?->major));
        let selectedStudentName = @js($selectedStudent?->user?->name ?? '');

        function updateMajors(preferredMajor = '') {
            const className = studentClass.value;
            const majors = className
                ? (majorsByClass[className] || [])
                : [...new Set(Object.values(majorsByClass).flat())].sort();
            const currentMajor = preferredMajor || studentMajor.value;
            studentMajor.replaceChildren(new Option('Semua Jurusan', ''));
            majors.forEach(function (major) {
                studentMajor.add(new Option(major, major));
            });
            studentMajor.value = majors.includes(currentMajor) ? currentMajor : '';
        }

        function renderStudentResults() {
            const query = studentSearch.value.trim().toLocaleLowerCase();
            const className = studentClass.value;
            const major = studentMajor.value;
            const selected = students.find(student => student.id === studentId.value);

            if (selected && (studentSearch.value.trim() !== selectedStudentName || (className && className !== selected.class) || (major && major !== selected.major))) {
                studentId.value = '';
                selectedStudentName = '';
            }

            const matches = students.filter(function (student) {
                const searchable = `${student.name} ${student.nisn} ${student.class} ${student.major}`.toLocaleLowerCase();
                return (!query || searchable.includes(query))
                    && (!className || student.class === className)
                    && (!major || student.major === major);
            });

            studentResults.replaceChildren();
            if (studentId.value) {
                studentResults.classList.add('hidden');
                const current = students.find(student => student.id === studentId.value);
                studentStatus.textContent = current ? `Siswa terpilih: ${current.name} — Kelas ${current.class}, ${current.major}.` : 'Cari dan pilih nama siswa dari hasil pencarian.';
                studentSearch.setAttribute('aria-expanded', 'false');
                return;
            }

            studentStatus.textContent = 'Cari dan pilih nama siswa dari hasil pencarian.';
            if (!query && !className && !major) {
                studentResults.classList.add('hidden');
                studentSearch.setAttribute('aria-expanded', 'false');
                return;
            }

            if (!matches.length) {
                const heading = document.createElement('div');
                heading.className = 'border-b border-slate-100 bg-slate-50 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-500';
                heading.textContent = 'Pilihan Siswa';
                studentResults.appendChild(heading);
                const empty = document.createElement('p');
                empty.className = 'px-3 py-3 text-sm text-slate-500';
                empty.textContent = 'Tidak ada siswa yang cocok dengan nama, kelas, dan jurusan yang dipilih.';
                studentResults.appendChild(empty);
            } else {
                const heading = document.createElement('div');
                heading.className = 'sticky top-0 border-b border-slate-100 bg-slate-50 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-500';
                heading.textContent = `Pilihan Siswa (${matches.length}${matches.length > 20 ? ', menampilkan 20 pertama' : ''})`;
                studentResults.appendChild(heading);

                matches.slice(0, 20).forEach(function (student) {
                    const result = document.createElement('button');
                    result.type = 'button';
                    result.role = 'option';
                    result.className = 'block w-full border-b border-slate-100 px-3 py-2.5 text-left text-sm text-slate-700 last:border-0 hover:bg-blue-50 focus:bg-blue-50 focus:outline-none';
                    result.textContent = `${student.name} — ${student.nisn} · Kelas ${student.class} · ${student.major}`;
                    result.addEventListener('click', function () {
                        studentId.value = student.id;
                        selectedStudentName = student.name;
                        studentSearch.value = student.name;
                        studentClass.value = student.class;
                        updateMajors(student.major);
                        studentPickerError.classList.add('hidden');
                        renderStudentResults();
                    });
                    studentResults.appendChild(result);
                });
            }

            studentResults.classList.remove('hidden');
            studentSearch.setAttribute('aria-expanded', 'true');
        }

        updateMajors(initialMajor || '');
        studentSearch.addEventListener('input', function () {
            if (studentId.value && this.value.trim() !== selectedStudentName) studentId.value = '';
            renderStudentResults();
        });
        studentClass.addEventListener('change', function () {
            if (studentId.value) {
                studentId.value = '';
                selectedStudentName = '';
            }
            updateMajors();
            renderStudentResults();
        });
        studentMajor.addEventListener('change', function () {
            if (studentId.value) {
                studentId.value = '';
                selectedStudentName = '';
            }
            renderStudentResults();
        });
        [studentClass, studentMajor].forEach(function (filter) {
            filter.addEventListener('click', renderStudentResults);
            filter.addEventListener('focus', renderStudentResults);
        });
        document.addEventListener('click', function (event) {
            const clickedStudentPicker = studentResults.contains(event.target)
                || event.target === studentSearch
                || studentClass.contains(event.target)
                || studentMajor.contains(event.target);

            if (!clickedStudentPicker) {
                studentResults.classList.add('hidden');
                studentSearch.setAttribute('aria-expanded', 'false');
            }
        });
        studentForm.addEventListener('submit', function (event) {
            if (!studentId.value) {
                event.preventDefault();
                studentPickerError.classList.remove('hidden');
                studentSearch.focus();
            }
        });
        renderStudentResults();

        const categorySelect = document.getElementById('category_select');
        const sel = document.getElementById('vt_select');
        const preview = document.getElementById('pts_preview');
        const val = document.getElementById('pts_val');
        const oldViolationType = @json(old('violation_type_id'));
        const categoryPoints = { ringan: 5, sedang: 15, berat: 50 };

        function filterViolationTypes() {
            const category = categorySelect.value;
            let selectedTypeIsVisible = false;

            Array.from(sel.options).slice(1).forEach(function (option) {
                const isVisible = category !== '' && option.dataset.category === category;
                option.hidden = !isVisible;
                option.disabled = !isVisible;
                if (isVisible && option.value === sel.value) selectedTypeIsVisible = true;
            });

            sel.disabled = category === '';
            if (!selectedTypeIsVisible) sel.value = '';
            sel.options[0].textContent = category ? '-- Pilih Nama Pelanggaran --' : '-- Pilih kategori terlebih dahulu --';
            val.textContent = categoryPoints[category] ?? '0';
            preview.classList.toggle('hidden', category === '');
        }

        categorySelect.addEventListener('change', filterViolationTypes);
        sel.addEventListener('change', filterViolationTypes);
        if (oldViolationType) sel.value = String(oldViolationType);
        filterViolationTypes();
    </script>
</x-app-layout>

