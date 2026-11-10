<div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-stone-900/40 md:hidden" aria-hidden="true"></div>

<aside id="app-sidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-slate-200 bg-white text-slate-900 transition-transform duration-200 md:static md:min-h-screen md:shrink-0 md:translate-x-0">
    <div class="border-b border-slate-100 px-6 py-6">
        <a href="{{ match (Auth::user()->role) {
            'admin' => route('admin.dashboard'),
            'guru' => route('guru.dashboard'),
            'siswa' => route('siswa.dashboard'),
            default => route('login'),
        } }}" class="flex items-center gap-3 text-lg font-bold tracking-tight text-slate-900">
            <span class="grid h-9 w-9 grid-cols-2 gap-1 rounded-xl bg-blue-50 p-1.5">
                <i class="rounded-sm bg-blue-600"></i><i class="rounded-sm bg-sky-400"></i>
                <i class="rounded-sm bg-sky-500"></i><i class="rounded-sm bg-indigo-400"></i>
            </span>
            Poin Siswa
        </a>
    </div>

    <div class="border-b border-slate-100 px-6 py-6 text-center">
        @if (Auth::user()->profile_photo_path)
            <img src="{{ route('profile.photo', Auth::user()) }}" alt="Foto profil {{ Auth::user()->name }}" class="mx-auto h-14 w-14 rounded-2xl object-cover ring-2 ring-blue-100">
        @else
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-xl font-bold text-blue-700">
                {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
            </div>
        @endif
        <p class="mt-3 truncate text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
        <p class="mt-1 text-xs font-medium uppercase tracking-[0.14em] text-slate-500">{{ Auth::user()->role }}</p>
    </div>

    <div class="flex-1 px-3 py-6">
        <p class="px-3 pb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Menu utama</p>
        <nav class="space-y-1.5" aria-label="Navigasi utama">
            @php
                $dashboardRoute = match (Auth::user()->role) {
                    'admin' => route('admin.dashboard'),
                    'guru' => route('guru.dashboard'),
                    'siswa' => route('siswa.dashboard'),
                    default => route('login'),
                };
            @endphp
            <a href="{{ $dashboardRoute }}"
                class="flex items-center gap-3 rounded-lg px-3.5 py-3 text-sm transition {{ request()->routeIs('*.dashboard') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5 12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6" />
                </svg>
                Dashboard
            </a>

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 rounded-lg px-3.5 py-3 text-sm transition {{ request()->routeIs('admin.teachers.*') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="w-5 text-center text-blue-600" aria-hidden="true">♙</span> Data Guru
                </a>
                <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 rounded-lg px-3.5 py-3 text-sm transition {{ request()->routeIs('admin.students.*') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="w-5 text-center text-indigo-500" aria-hidden="true">♟</span> Data Siswa
                </a>
                <a href="{{ route('admin.violation-types.index') }}" class="flex items-center gap-3 rounded-lg px-3.5 py-3 text-sm transition {{ request()->routeIs('admin.violation-types.*') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="w-5 text-center text-blue-500" aria-hidden="true">⚑</span> Jenis Pelanggaran
                </a>
            @elseif (Auth::user()->role === 'guru')
                <a href="{{ route('guru.violations.students') }}" class="flex items-center gap-3 rounded-lg px-3.5 py-3 text-sm transition {{ request()->routeIs('guru.violations.students', 'guru.violations.create') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14" />
                    </svg>
                    Catat Pelanggaran
                </a>
            @endif
        </nav>
    </div>

    <div class="space-y-1 border-t border-slate-100 px-3 py-5">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
            <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 100-8 4 4 0 000 8zm7 9a7 7 0 10-14 0" />
            </svg>
            Profil
        </a>
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
        <button type="button" @click="logoutPromptOpen = true" class="flex w-full items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 012 2v14a2 2 0 01-2 2h-6" />
            </svg>
            Keluar
        </button>
    </div>
</aside>
