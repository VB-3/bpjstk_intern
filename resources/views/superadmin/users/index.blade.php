<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Kelola Pengguna
            </h2>
            <a href="{{ route('superadmin.users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm shadow-md shadow-green-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pengguna
            </a>
        </div>
    </x-slot>

    <div class="page-wrapper">

        {{-- ── Filter & Search Section ──────────────────────────────────── --}}
        <div class="glass-effect rounded-2xl p-5 shadow-sm">
            <form method="GET" action="{{ route('superadmin.users.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                {{-- Search text input --}}
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama atau email..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all outline-none">
                </div>

                {{-- Role dropdown --}}
                <div class="w-full md:w-56">
                    <select name="role"
                            class="w-full py-2.5 px-3.5 bg-gray-50/80 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all outline-none">
                        <option value="">Semua Role</option>
                        <option value="intern" {{ request('role') === 'intern' ? 'selected' : '' }}>Peserta Magang</option>
                        <option value="mentor" {{ request('role') === 'mentor' ? 'selected' : '' }}>Mentor</option>
                        <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    @if(request()->filled('search') || request()->filled('role'))
                        <a href="{{ route('superadmin.users.index') }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-sm transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Users Table ──────────────────────────────────────────────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-md">
            {{-- Header info --}}
            <div class="px-6 sm:px-8 py-5 border-b border-gray-100/70 bg-white/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-extrabold text-gray-900">Daftar Pengguna</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Kelola akun dan hak akses pengguna dalam sistem portal BPJS.</p>
                </div>
                <div class="text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-xl">
                    Total: <span class="font-extrabold text-gray-800">{{ $users->total() }}</span> pengguna
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500">
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider">Nama</th>
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider">Email</th>
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider text-center">Role</th>
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @php
                            $roleBadges = [
                                'intern' => ['label' => 'Peserta Magang', 'class' => 'bg-green-100 text-green-800'],
                                'mentor' => ['label' => 'Mentor', 'class' => 'bg-blue-100 text-blue-800'],
                                'super_admin' => ['label' => 'Super Admin', 'class' => 'bg-yellow-100 text-yellow-800'],
                            ];
                        @endphp

                        @forelse($users as $user)
                            @php
                                $badge = $roleBadges[$user->role] ?? ['label' => ucfirst($user->role), 'class' => 'bg-gray-100 text-gray-800'];
                            @endphp
                            <tr class="hover:bg-green-50/30 transition-colors group">
                                {{-- Nama (with avatar initial circle) --}}
                                <td class="px-6 sm:px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-extrabold text-sm shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm group-hover:text-green-700 transition-colors">{{ $user->name }}</p>
                                            @if($user->id === auth()->id())
                                                <span class="inline-flex items-center text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full mt-0.5 border border-green-200">Akun Anda</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-6 sm:px-8 py-4 text-gray-600 font-medium">
                                    {{ $user->email }}
                                </td>

                                {{-- Role badge --}}
                                <td class="px-6 sm:px-8 py-4 text-center">
                                    <span class="badge {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>

                                {{-- Dibuat --}}
                                <td class="px-6 sm:px-8 py-4 text-gray-500 whitespace-nowrap">
                                    <p class="font-semibold text-sm text-gray-800">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($user->created_at)->format('H:i') }} WIB</p>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 sm:px-8 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- Edit Link --}}
                                        <a href="{{ route('superadmin.users.edit', $user) }}"
                                           class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors border border-blue-100"
                                           title="Edit Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Delete Form --}}
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('superadmin.users.destroy', $user) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors border border-red-100"
                                                        title="Hapus Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="w-8 h-8 rounded-xl bg-gray-50 text-gray-300 flex items-center justify-center border border-gray-100 cursor-not-allowed"
                                                  title="Tidak dapat menghapus akun sendiri">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gray-100 text-gray-300 mb-3">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-base font-bold text-gray-700">Tidak ada pengguna ditemukan</p>
                                    <p class="text-sm text-gray-400 mt-1">
                                        @if(request()->filled('search') || request()->filled('role'))
                                            Coba ubah kata kunci pencarian atau reset filter.
                                        @else
                                            Belum ada data pengguna yang terdaftar di sistem.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination footer --}}
            <div class="px-6 sm:px-8 py-4 bg-white/90 border-t border-gray-100">
                {{ $users->links('vendor.pagination.custom') }}
            </div>
        </div>

    </div>
</x-app-layout>

