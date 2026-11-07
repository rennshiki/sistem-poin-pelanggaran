<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.violation-types.index') }}"
               class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tambah Jenis Pelanggaran') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-8 border border-gray-100 dark:border-gray-700">
                <div class="mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Formulir Jenis Pelanggaran</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Isi data jenis pelanggaran beserta jumlah poin yang akan dikenakan kepada siswa.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.violation-types.store') }}" class="space-y-6">
                    @csrf

                    {{-- Nama Pelanggaran --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Pelanggaran')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus placeholder="Contoh: Terlambat masuk sekolah" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                        <textarea id="description" name="description" rows="3"
                            placeholder="Penjelasan singkat mengenai pelanggaran ini..."
                            class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Poin --}}
                        <div>
                            <x-input-label for="points" :value="__('Jumlah Poin')" />
                            <x-text-input id="points" class="block mt-1 w-full" type="number" name="points"
                                :value="old('points', 1)" required min="1" max="100" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Poin yang diberikan saat pelanggaran ini dicatat (1–100).</p>
                            <x-input-error :messages="$errors->get('points')" class="mt-2" />
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <x-input-label for="category" :value="__('Kategori Pelanggaran')" />
                            <select id="category" name="category" required
                                class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="ringan" @selected(old('category') === 'ringan')>🟢 Ringan</option>
                                <option value="sedang" @selected(old('category') === 'sedang')>🟡 Sedang</option>
                                <option value="berat"  @selected(old('category') === 'berat')>🔴 Berat</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input id="is_active" type="checkbox" name="is_active" value="1"
                            @checked(old('is_active', true))
                            class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                        <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis pelanggaran ini aktif (dapat digunakan untuk mencatat pelanggaran)
                        </label>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.violation-types.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                            Batal
                        </a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700">
                            {{ __('Simpan Pelanggaran') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

