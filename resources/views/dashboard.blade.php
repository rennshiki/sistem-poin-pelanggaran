<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Anda login sebagai <span class="font-semibold uppercase text-indigo-600 dark:text-indigo-400">{{ Auth::user()->role }}</span>.
                    </p>
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card Guru -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">Manajemen Data Guru</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Kelola data tenaga pendidik, buat akun baru dengan NIP dan atur hak akses mereka.
                            </p>
                        </div>
                        <div class="mt-6 flex items-center space-x-3">
                            <a href="{{ route('admin.teachers.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                + Tambah Guru
                            </a>
                            <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Lihat Semua Guru
                            </a>
                        </div>
                    </div>

                    <!-- Card Siswa -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">Manajemen Data Siswa</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Kelola daftar siswa, kelas, jurusan, nomor HP orang tua, serta akun login berdasarkan NISN.
                            </p>
                        </div>
                        <div class="mt-6 flex items-center space-x-3">
                            <a href="{{ route('admin.students.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                + Tambah Siswa
                            </a>
                            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Lihat Semua Siswa
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-gray-700 dark:text-gray-300 text-sm">
                        Anda telah berhasil masuk ke sistem dengan akun {{ Auth::user()->name }} ({{ Auth::user()->role }}).
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
