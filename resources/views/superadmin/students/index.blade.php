<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            {{ __('Daftar Peserta Magang (Anak Didik)') }}
        </h2>
    </x-slot>

    <div class="page-wrapper">
        <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50">
            <div class="bg-white p-6 sm:px-8 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">Peserta Magang</h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar seluruh peserta magang, folder Google Drive, dan rekap pencapaian BPU mereka.</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-gray-50/50 text-gray-600 font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-6 sm:px-8 py-4">Nama Peserta</th>
                            <th class="px-6 py-4">Mentor Pembimbing</th>
                            <th class="px-6 py-4">Google Drive</th>
                            <th class="px-6 py-4 text-center">Total BPU</th>
                            <th class="px-6 sm:px-8 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 sm:px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $student->name }}</p>
                                            <div class="flex items-center gap-2 mt-0.5 text-xs text-gray-500">
                                                @if($student->nim)
                                                    <span class="font-mono font-semibold text-gray-600 bg-gray-100 px-1.5 py-0.5 rounded">{{ $student->nim }}</span>
                                                    <span>•</span>
                                                @endif
                                                <span>{{ $student->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($student->mentor)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                            {{ $student->mentor->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs italic bg-gray-50 px-2.5 py-1 rounded-md border border-gray-200">Belum memilih</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($student->gdrive_link)
                                        <a href="{{ $student->gdrive_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-semibold text-xs bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-200 transition-colors group">
                                            <svg class="w-4 h-4 text-blue-500 group-hover:text-blue-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            Buka Folder Drive
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs italic bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-200">Belum ditautkan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                        {{ number_format($student->todos_sum_bpu_achievement ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 sm:px-8 py-4 text-center">
                                    <a href="{{ route('superadmin.students.edit', $student->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit BPU
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-10 text-center text-gray-500">
                                    Belum ada peserta magang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 pb-4 pt-2">
                {{ $students->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</x-app-layout>
