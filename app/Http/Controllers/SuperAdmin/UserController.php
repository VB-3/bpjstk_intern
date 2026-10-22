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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $messages = [];

        if ($isIntern) {
            // Intern: NIM wajib, email tidak diperlukan
            $rules['nim']       = 'required|digits:9|unique:users';
            $rules['mentor_id'] = 'required|exists:users,id';
            $messages['nim.required'] = 'NIM wajib diisi.';
            $messages['nim.digits']   = 'NIM harus tepat 9 angka.';
            $messages['nim.unique']   = 'NIM sudah terdaftar.';
        } else {
            // Mentor / Super Admin: email wajib
            $rules['email'] = 'required|string|email|max:255|unique:users';
        }

        $request->validate($rules, $messages);

        $data = [
            'name'     => mb_strtoupper(trim($request->name), 'UTF-8'),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ];

        if ($isIntern) {
            $data['nim']       = $request->nim;
            $data['mentor_id'] = $request->mentor_id;
        } else {
            $data['email'] = $request->email;
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
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];

        $messages = [];

        if ($isIntern) {
            $rules['nim']       = 'required|digits:9|unique:users,nim,' . $user->id;
            $rules['mentor_id'] = 'required|exists:users,id';
            $messages['nim.required'] = 'NIM wajib diisi.';
            $messages['nim.digits']   = 'NIM harus tepat 9 angka.';
            $messages['nim.unique']   = 'NIM sudah terdaftar.';
        } else {
            $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $user->id;
        }

        $request->validate($rules, $messages);

        $data = [
            'name' => mb_strtoupper(trim($request->name), 'UTF-8'),
            'role' => $request->role,
        ];

        if ($isIntern) {
            $data['nim']       = $request->nim;
            $data['mentor_id'] = $request->mentor_id;
            $data['email']     = null; // hapus email jika diubah ke intern
        } else {
            $data['email'] = $request->email;
            $data['nim']   = null; // hapus NIM jika diubah ke non-intern
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
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

