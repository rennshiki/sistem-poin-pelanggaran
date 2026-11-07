<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.teachers.index') }}" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tambah Akun & Data Guru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-8 border border-gray-100 dark:border-gray-700">
                <div class="mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Formulir Pendaftaran Guru</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Akun login akan dibuat otomatis menggunakan NIP dan Password yang Anda masukkan di bawah ini.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-6">
                    @csrf

                    <!-- Nama Guru -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap Guru')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Contoh: Dra. Hj. Siti Rahma, M.Pd." />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- NIP -->
                    <div>
                        <x-input-label for="nip" :value="__('NIP (Nomor Induk Pegawai)')" />
                        <x-text-input id="nip" class="block mt-1 w-full font-mono" type="text" name="nip" :value="old('nip')" required placeholder="Contoh: 198501152010012003" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">NIP ini akan digunakan guru saat login ke dalam sistem.</p>
                        <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                    </div>

                    <!-- Password Awal -->
                    <div>
                        <x-input-label for="password" :value="__('Password Akun')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 6 karakter" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Berikan password ini kepada guru terkait untuk login pertama kali.</p>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                            Batal
                        </a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700">
                            {{ __('Simpan Guru') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
