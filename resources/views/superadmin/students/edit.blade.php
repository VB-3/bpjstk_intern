<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <a href="{{ route('superadmin.students.index') }}" class="text-gray-400 hover:text-green-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                {{ __('Detail & Edit BPU Peserta: ') . $student->name }}
            </h2>
        </div>
    </x-slot>

    <div class="page-wrapper">
        {{-- Student Summary Card --}}
        <div class="glass-effect rounded-3xl p-6 sm:p-8 shadow-lg border border-white/50 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl shrink-0">
                    {{ strtoupper(substr($student->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $student->name }}</h3>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-gray-500">
                        @if($student->nim)
                            <span class="font-mono font-semibold text-gray-700 bg-gray-100 px-2 py-0.5 rounded">NIM: {{ $student->nim }}</span>
                        @endif
                        <span>{{ $student->email }}</span>
                        @if($student->mentor)
                            <span>•</span>
                            <span class="text-indigo-700 font-semibold">Mentor: {{ $student->mentor->name }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if($student->gdrive_link)
                    <a href="{{ $student->gdrive_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-colors shadow-sm">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Buka Folder Google Drive
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs text-gray-400 bg-gray-50 border border-gray-200 italic">
                        Link Drive Belum Ditautkan
                    </span>
                @endif

                <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-2 text-center">
                    <p class="text-[10px] uppercase font-bold text-green-600 tracking-wider">Total BPU (Real Time)</p>
                    <p class="text-base font-extrabold text-green-800">{{ number_format($realtimeBpu ?? $todos->sum('bpu_achievement'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Work History & BPU Table --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50">
            <div class="bg-white p-6 sm:px-8 border-b border-gray-100">
                <h3 class="text-xl font-extrabold text-gray-900">Riwayat Pekerjaan & BPU</h3>
                <p class="text-sm text-gray-500 mt-1">Sesuaikan nilai BPU jika ada kesalahan input oleh peserta.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-gray-50/50 text-gray-600 font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Pekerjaan</th>
                            <th class="px-6 py-4 text-center">BPU Saat Ini</th>
                            <th class="px-6 py-4">Edit Nilai BPU</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($todos as $todo)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-700">
                                    {{ \Carbon\Carbon::parse($todo->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 max-w-md truncate">
                                    {{ $todo->summary }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                        {{ number_format($todo->bpu_achievement, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('superadmin.students.updateBpu', $todo->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="bpu_achievement" value="{{ $todo->bpu_achievement }}" min="0" required class="w-24 text-sm border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <button type="submit" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    Peserta ini belum mengisi To-Do List sama sekali.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
