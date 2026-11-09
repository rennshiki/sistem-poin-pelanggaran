<x-app-layout>
    @php($isEditing = isset($student))

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.students.index') }}" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $isEditing ? __('Edit Data Siswa') : __('Tambah Akun & Data Siswa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-8 border border-gray-100 dark:border-gray-700">
                <div class="mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $isEditing ? 'Perbarui Data Siswa' : 'Formulir Pendaftaran Siswa' }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $isEditing ? 'Perbarui data siswa. Isi password hanya jika ingin menggantinya.' : 'Siswa akan login menggunakan NISN dan Password yang ditentukan di bawah ini.' }}
                    </p>
                </div>

                <form method="POST" action="{{ $isEditing ? route('admin.students.update', $student) : route('admin.students.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @if ($isEditing)
                        @method('PUT')
                    @endif

                    <div>
                        <x-input-label for="profile_photo" value="Foto Profil Siswa (opsional)" />
                        @if ($isEditing && $student->user?->profile_photo_path)
                            <img src="{{ route('profile.photo', $student->user) }}" alt="Foto profil {{ $student->user?->name }}" class="mb-3 mt-2 h-20 w-20 rounded-xl object-cover ring-1 ring-slate-200">
                        @endif
                        <input id="profile_photo" type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:font-medium hover:file:bg-slate-200">
                        <p class="mt-1 text-xs text-slate-500">Format JPG, PNG, atau WEBP. Maksimal 5 MB. Saat edit, kosongkan jika foto tidak diganti.</p>
                        <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                    </div>

                    <!-- Nama Siswa -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap Siswa')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $student->user?->name ?? '')" required autofocus placeholder="Contoh: Muhammad Rizky Pratama" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- NISN -->
                    <div>
                        <x-input-label for="nisn" :value="__('NISN (Nomor Induk Siswa Nasional)')" />
                        <x-text-input id="nisn" class="block mt-1 w-full font-mono" type="text" name="nisn" :value="old('nisn', $student->user?->nisn ?? '')" required placeholder="Contoh: 0061234567" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">NISN digunakan siswa sebagai username saat login.</p>
                        <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Kelas -->
                        <div>
                            <x-input-label for="class" :value="__('Kelas')" />
                            <x-text-input id="class" class="block mt-1 w-full" type="text" name="class" :value="old('class', $student->class ?? '')" required placeholder="Contoh: X, XI, XII / X-A" />
                            <x-input-error :messages="$errors->get('class')" class="mt-2" />
                        </div>

                        <!-- Jurusan -->
                        <div>
                            <x-input-label for="major" :value="__('Jurusan')" />
                            <x-text-input id="major" class="block mt-1 w-full" type="text" name="major" :value="old('major', $student->major ?? '')" required placeholder="Contoh: Rekayasa Perangkat Lunak" />
                            <x-input-error :messages="$errors->get('major')" class="mt-2" />
                        </div>
                    </div>

                    <!-- No HP Orang Tua -->
                    <div>
                        <x-input-label for="parent_phone" :value="__('No. HP / WhatsApp Orang Tua')" />
                        <x-text-input id="parent_phone" class="block mt-1 w-full font-mono" type="text" name="parent_phone" :value="old('parent_phone', $student->parent_phone ?? '')" required placeholder="Contoh: 081234567890" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Digunakan untuk keperluan notifikasi / pemanggilan terkait pelanggaran siswa.</p>
                        <x-input-error :messages="$errors->get('parent_phone')" class="mt-2" />
                    </div>

                    <!-- Password Awal -->
                    <div>
                        <x-input-label for="password" :value="$isEditing ? __('Password Baru (opsional)') : __('Password Akun Siswa')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" :required="! $isEditing" autocomplete="new-password" placeholder="Minimal 6 karakter" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $isEditing ? 'Kosongkan jika password tidak ingin diubah.' : 'Password bawaan untuk login pertama siswa.' }}</p>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.students.index') }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                            Batal
                        </a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700">
                            {{ $isEditing ? __('Simpan Perubahan') : __('Simpan Siswa') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
