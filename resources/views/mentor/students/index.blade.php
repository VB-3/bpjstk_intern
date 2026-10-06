<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Anak Didik') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50">
                <div class="bg-white p-6 sm:px-8 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">Anak Didik</h3>
                        <p class="text-sm text-gray-500 mt-1">Daftar peserta magang yang menjadi tanggung jawab Anda.</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="bg-gray-50/50 text-gray-600 font-bold border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-4">Nama Peserta</th>
                                <th class="px-8 py-4">Link To-Do List (G-Drive)</th>
                                <th class="px-8 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $student->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        @if($student->gdrive_link)
                                            <a href="{{ $student->gdrive_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 font-medium text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Buka Folder Drive
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm italic">Belum menautkan link</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                            Aktif Magang
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-8 py-10 text-center text-gray-500">
                                        Belum ada anak didik yang ditugaskan kepada Anda.
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
    </div>
</x-app-layout>
