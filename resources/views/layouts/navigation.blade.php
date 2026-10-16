<nav 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
    class="fixed z-50 lg:static inset-y-0 left-0 w-64 text-white shadow-2xl transition-transform duration-300 ease-in-out flex flex-col h-full"
    style="background: linear-gradient(180deg, #14532d 0%, #166534 40%, #15803d 100%);"
>
    {{-- ── Logo & Brand ─────────────────────────────────────────── --}}
    <div class="flex items-center justify-center h-20 px-6 shrink-0 border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full h-full">
            <img src="{{ asset('images/logo bpjstk.png') }}" alt="BPJS" class="h-10 w-auto object-contain">
        </a>
    </div>

    {{-- ── Navigation Links ─────────────────────────────────────── --}}
    <div class="flex-1 overflow-y-auto py-5 px-3 space-y-1">
        @if(Auth::user()->isSuperAdmin())
            <p class="px-3 pt-2 pb-1 text-[10px] font-bold text-yellow-400 uppercase tracking-[0.12em]">Super Admin</p>

            <a href="{{ route('superadmin.dashboard') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('superadmin.dashboard') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('superadmin.users.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('superadmin.users.*') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Kelola Pengguna
            </a>

            <a href="{{ route('superadmin.attendances.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('superadmin.attendances.*') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Kelola Absensi
            </a>

            <a href="{{ route('superadmin.todos.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('superadmin.todos.*') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Kelola To-Do List
            </a>

            <a href="{{ route('superadmin.tasks.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('superadmin.tasks.*') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Kelola Tugas
            </a>

            <a href="{{ route('superadmin.students.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                       {{ request()->routeIs('superadmin.students.*') 
                           ? 'bg-white/20 text-white shadow-inner' 
                           : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Anak Didik
            </a>

        @elseif(Auth::user()->isMentor())
            <p class="px-3 pt-2 pb-1 text-[10px] font-bold text-green-400 uppercase tracking-[0.12em]">Menu Pembimbing</p>

            <a href="{{ route('mentor.dashboard') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('mentor.dashboard') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('mentor.tasks.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('mentor.tasks.*') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Kelola Tugas
            </a>

            <a href="{{ route('mentor.students.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                       {{ request()->routeIs('mentor.students.*') 
                           ? 'bg-white/20 text-white shadow-inner' 
                           : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Anak Didik
            </a>



        @else
            <p class="px-3 pt-2 pb-1 text-[10px] font-bold text-green-400 uppercase tracking-[0.12em]">Menu Peserta</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('dashboard') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('attendances.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('attendances.*') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Absensi
            </a>

            <a href="{{ route('todos.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('todos.*') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                To-Do List
            </a>

            <a href="{{ route('tasks.index') }}"
               class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm font-semibold gap-3
                      {{ request()->routeIs('tasks.*') 
                          ? 'bg-white/20 text-white shadow-inner' 
                          : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Pengumpulan Tugas
            </a>
        @endif
    </div>

    {{-- ── User Footer ──────────────────────────────────────────── --}}
    <div class="shrink-0 border-t border-white/10 p-4" style="background:rgba(0,0,0,0.15)">
        <div class="flex items-center gap-3 mb-3">
            @if(Auth::user()->photo)
                <img src="{{ Storage::url(Auth::user()->photo) }}" alt="Foto Profil" class="w-9 h-9 rounded-xl object-cover shrink-0 shadow-sm">
            @else
                <div class="w-9 h-9 rounded-xl bg-green-500 flex items-center justify-center text-white font-extrabold text-sm shrink-0 shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="truncate flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate leading-tight">{{ Auth::user()->name }}</p>
                <p class="text-[11px] text-green-300 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('profile.edit') }}"
               class="flex-1 text-center text-xs font-bold text-green-200 hover:text-white bg-white/10 hover:bg-white/20 py-2 rounded-lg transition-colors">
                Profil
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full text-xs font-bold text-red-300 hover:text-red-100 bg-red-900/30 hover:bg-red-900/50 py-2 rounded-lg transition-colors">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

