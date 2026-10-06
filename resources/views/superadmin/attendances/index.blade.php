<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Kelola Absensi
            </h2>
            <div class="text-xs font-semibold text-gray-500 bg-gray-100/80 px-3 py-1.5 rounded-xl border border-gray-200">
                Total Absensi: <span class="font-extrabold text-green-700">{{ $attendances->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="page-wrapper">
        {{-- ── Filter Card ──────────────────────────────────────────────── --}}
        <div class="glass-effect rounded-3xl p-6 shadow-md border border-white/60">
            <form method="GET" action="{{ route('superadmin.attendances.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                {{-- Date Filter --}}
                <div class="sm:col-span-4">
                    <label for="date" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Tanggal Absensi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" id="date" name="date" value="{{ request('date') }}"
                            class="w-full pl-9 pr-4 py-2.5 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl text-sm font-medium transition-all outline-none">
                    </div>
                </div>

                {{-- Intern User Filter --}}
                <div class="sm:col-span-5">
                    <label for="user_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Peserta Magang</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <select id="user_id" name="user_id"
                            class="w-full pl-9 pr-4 py-2.5 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl text-sm font-medium transition-all outline-none">
                            <option value="">Semua Peserta</option>
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}" {{ request('user_id') == $intern->id ? 'selected' : '' }}>
                                    {{ $intern->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="sm:col-span-3 flex items-center gap-2">
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 hover:shadow-lg transition-all"
                        style="background: linear-gradient(135deg, #16a34a, #15803d);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->filled('date') || request()->filled('user_id'))
                        <a href="{{ route('superadmin.attendances.index') }}"
                            class="px-3.5 py-2.5 bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-500 rounded-xl text-sm font-semibold border border-gray-200 transition-colors"
                            title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Attendances Table Card ───────────────────────────────────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-md border border-white/60">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-white/80">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">Daftar Rekap Absensi</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Memantau catatan jam hadir dan pulang peserta magang</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-gray-50/75 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Nama Peserta</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4 text-center">Check-In</th>
                            <th class="px-6 py-4 text-center">Check-Out</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white/70">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-green-50/40 transition-colors">
                                {{-- Nama Peserta --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 text-white font-extrabold text-sm flex items-center justify-center shrink-0 shadow-sm">
                                            {{ strtoupper(substr($attendance->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ $attendance->user->name ?? 'User Dihapus' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $attendance->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-800">
                                        {{ $attendance->date ? $attendance->date->format('d/m/Y') : '-' }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">
                                        {{ $attendance->date ? \Carbon\Carbon::parse($attendance->date)->translatedFormat('l') : '' }}
                                    </div>
                                </td>

                                {{-- Tipe --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($attendance->type === 'harian')
                                        <span class="badge bg-green-100 text-green-800 border border-green-200">
                                            Harian
                                        </span>
                                    @else
                                        <span class="badge bg-blue-100 text-blue-800 border border-blue-200">
                                            Luar Harian
                                        </span>
                                    @endif
                                </td>

                                {{-- Check-In --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($attendance->check_in_time)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 font-bold">-</span>
                                    @endif
                                </td>

                                {{-- Check-Out --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($attendance->check_out_time)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-extrabold bg-red-50 text-red-700 border border-red-200">
                                            {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 font-bold">-</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('superadmin.attendances.edit', $attendance) }}"
                                            class="p-2 rounded-xl text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 border border-transparent hover:border-emerald-200 transition-colors"
                                            title="Edit Absensi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('superadmin.attendances.destroy', $attendance) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-xl text-red-500 hover:text-red-700 hover:bg-red-50 border border-transparent hover:border-red-200 transition-colors"
                                                title="Hapus Absensi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 text-gray-300 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-700 mb-1">Data Absensi Tidak Ditemukan</h3>
                                    <p class="text-sm text-gray-400">Tidak ada data absensi yang sesuai dengan kriteria filter saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-white/50 border-t border-gray-100/70">
                {{ $attendances->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</x-app-layout>
