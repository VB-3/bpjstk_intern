<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        if (request()->filled('role')) {
            $query->where('role', request('role'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->only(['search', 'role']));
        
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $mentors = User::where('role', 'mentor')->orderBy('name')->get();
        return view('superadmin.users.create', compact('mentors'));
    }

    public function store(Request $request)
    {
        $isIntern = $request->input('role') === 'intern';

        $rules = [
            'name'     => 'required|string|max:255',
            'role'     => 'required|in:intern,mentor,super_admin',
            'password' => ['required', 'confirmed', 'min:6'],
        ];

        $messages = [
            'name.required'      => 'Nama wajib diisi.',
            'role.required'      => 'Peran (Role) wajib dipilih.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok. Harap masukkan kata sandi yang sama di kedua kolom.',
        ];

        if ($isIntern) {
            // Intern: NIM wajib
            $rules['nim']       = 'required|string|max:30|unique:users,nim';
            $rules['mentor_id'] = 'nullable|exists:users,id';
            $messages['nim.required']     = 'NIM wajib diisi.';
            $messages['nim.unique']       = 'NIM sudah terdaftar.';
            $messages['mentor_id.exists'] = 'Mentor yang dipilih tidak valid.';
        } else {
            // Mentor / Super Admin: email wajib
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
            $messages['email.required'] = 'Email wajib diisi.';
            $messages['email.email']    = 'Format email tidak valid.';
            $messages['email.unique']   = 'Email sudah terdaftar.';
        }

        $request->validate($rules, $messages);

        $data = [
            'name'     => mb_strtoupper(trim($request->name), 'UTF-8'),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ];

        if ($isIntern) {
            $data['nim']       = trim($request->nim);
            $data['mentor_id'] = $request->filled('mentor_id') ? $request->mentor_id : null;
            // Gunakan email jika diisi, jika kosong gunakan default dari NIM agar database aman
            $data['email']     = $request->filled('email') ? trim($request->email) : (trim($request->nim) . '@intern.bpjs');
        } else {
            $data['email'] = trim($request->email);
        }

        User::create($data);

        return redirect()->route('superadmin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $mentors = User::where('role', 'mentor')->orderBy('name')->get();
        return view('superadmin.users.edit', compact('user', 'mentors'));
    }

    public function update(Request $request, User $user)
    {
        $isIntern = $request->input('role') === 'intern';

        $rules = [
            'name'     => 'required|string|max:255',
            'role'     => 'required|in:intern,mentor,super_admin',
            'password' => ['nullable', 'confirmed', 'min:6'],
        ];

        $messages = [
            'name.required'      => 'Nama wajib diisi.',
            'role.required'      => 'Peran (Role) wajib dipilih.',
            'password.min'       => 'Kata sandi minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok. Harap masukkan kata sandi yang sama di kedua kolom.',
        ];

        if ($isIntern) {
            $rules['nim']       = 'required|string|max:30|unique:users,nim,' . $user->id;
            $rules['mentor_id'] = 'nullable|exists:users,id';
            $messages['nim.required']     = 'NIM wajib diisi.';
            $messages['nim.unique']       = 'NIM sudah terdaftar untuk pengguna lain.';
            $messages['mentor_id.exists'] = 'Mentor yang dipilih tidak valid.';
        } else {
            $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $user->id;
            $messages['email.required'] = 'Email wajib diisi.';
            $messages['email.email']    = 'Format email tidak valid.';
            $messages['email.unique']   = 'Email sudah terdaftar untuk pengguna lain.';
        }

        $request->validate($rules, $messages);

        $data = [
            'name' => mb_strtoupper(trim($request->name), 'UTF-8'),
            'role' => $request->role,
        ];

        if ($isIntern) {
            $data['nim']       = trim($request->nim);
            $data['mentor_id'] = $request->filled('mentor_id') ? $request->mentor_id : null;
            
            // JANGAN hapus email! Pertahankan email yang sudah ada jika tidak diisi,
            // atau gunakan email baru jika diinputkan, atau fallback ke NIM
            if ($request->filled('email')) {
                $data['email'] = trim($request->email);
            } elseif (empty($user->email)) {
                $data['email'] = trim($request->nim) . '@intern.bpjs';
            }
        } else {
            $data['email']     = trim($request->email);
            $data['nim']       = null;
            $data['mentor_id'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:6'],
        ], [
            'password.required'  => 'Kata sandi baru wajib diisi.',
            'password.min'       => 'Kata sandi minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok. Harap masukkan kata sandi yang sama di kedua kolom.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', "Kata sandi untuk {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

