<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{
    public function index()
    {
        $query = Todo::where('user_id', auth()->id());

        if (request()->filled('date')) {
            $query->where('date', request('date'));
        }

        $todos = $query->orderBy('date', 'desc')->paginate(4)->appends(request()->only('date'));
        $todayTodo = Todo::where('user_id', auth()->id())->where('date', today())->first();
        $totalBpu = Todo::where('user_id', auth()->id())->sum('bpu_achievement');

        return view('todos.index', compact('todos', 'todayTodo', 'totalBpu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'summary'         => 'required|string',
            'bpu_achievement' => 'nullable|integer|min:0',
        ]);

        // Cek apakah sudah mengisi hari ini
        $exists = Todo::where('user_id', auth()->id())->where('date', today())->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah mengisi To-Do List hari ini.');
        }

        // Intern tidak diizinkan mengisi BPU — selalu disimpan sebagai 0
        $bpuValue = auth()->user()->isSuperAdmin() ? ($request->bpu_achievement ?? 0) : 0;

        // Simpan ke database terlebih dahulu
        Todo::create([
            'user_id'         => auth()->id(),
            'date'            => today(),
            'summary'         => $request->summary,
            'bpu_achievement' => $bpuValue,
        ]);

        // Coba kirim ke Google Sheets (jika gagal, data di DB tetap aman)
        try {
            $user = auth()->user();
            $sheetsService = new \App\Services\GoogleSheetsService();
            $sheetsService->appendTodoRow($user->name, $user->gdrive_link, $request->summary, today()->toDateString());
        } catch (\Exception $e) {
            Log::error('Google Sheets append error: ' . $e->getMessage());
            // Data sudah tersimpan di DB, beri tahu user bahwa Sheets gagal tapi tidak apa-apa
            return redirect()->back()->with('warning', 'To-Do List berhasil disimpan, namun gagal diteruskan ke Google Sheets. Hubungi admin jika masalah berlanjut.');
        }

        return redirect()->back()->with('success', 'To-Do List berhasil disimpan dan diteruskan ke Google Sheets.');
    }

    public function storeGdriveLink(Request $request)
    {
        $request->validate([
            'gdrive_link' => 'required|string',
        ]);

        $link = trim($request->gdrive_link);
        if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
            $link = 'https://' . $link;
        }

        auth()->user()->update(['gdrive_link' => $link]);

        return redirect()->back()->with('success', 'Link Google Drive berhasil disimpan. Silakan isi kegiatan harian Anda.');
    }

    public function updateBpu(Request $request, Todo $todo)
    {
        // Hanya super admin yang boleh mengubah BPU
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat mengubah pencapaian BPU.');
        }

        $request->validate([
            'bpu_achievement' => 'required|integer|min:0',
        ]);

        $todo->update(['bpu_achievement' => $request->bpu_achievement]);

        return redirect()->back()->with('success', 'Pencapaian BPU harian berhasil diperbarui menjadi ' . $request->bpu_achievement . ' orang.');
    }
}
