<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <a href="{{ route('superadmin.students.index') }}" class="text-gray-400 hover:text-green-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                {{ __('Edit BPU Peserta: ') . $student->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            


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
    </div>
</x-app-layout>
