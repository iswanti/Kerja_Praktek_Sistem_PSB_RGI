<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Role;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'cabang', 'jurusan']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $roles = Role::where('is_active', true)->get();
        $cabangs = Cabang::orderBy('nama_cabang')->get();

        return view('admin.users.index', compact('users', 'roles', 'cabangs'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        $cabangs = Cabang::orderBy('nama_cabang')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        return view('admin.users.create', compact('roles', 'cabangs', 'jurusans'));
    }

    public function store(Request $request)
    {
        $timWawancaraRole = Role::where('nama', 'Tim Wawancara')->first();

        $isTimWawancara = $timWawancaraRole
            && (int) $request->role_id === (int) $timWawancaraRole->id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'unsur_wawancara' => $isTimWawancara
                ? 'required|in:operator,manajemen,scc_asrama,instruktur'
                : 'nullable|in:operator,manajemen,scc_asrama,instruktur',
            'cabang_id' => $isTimWawancara
                ? 'required|exists:cabangs,id'
                : 'nullable|exists:cabangs,id',
            'jurusan_id' => ($isTimWawancara && $request->unsur_wawancara === 'instruktur')
                ? 'required|exists:jurusans,id'
                : 'nullable|exists:jurusans,id',
            'password' => 'required|min:6|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role_id' => $validated['role_id'],
            'unsur_wawancara' => $isTimWawancara
                ? $validated['unsur_wawancara']
                : null,
            'cabang_id' => $validated['cabang_id'] ?? null,
            'jurusan_id' => ($isTimWawancara && $validated['unsur_wawancara'] === 'instruktur')
                ? $validated['jurusan_id']
                : null,
            'is_active' => $request->has('is_active'),
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('is_active', true)->get();
        $cabangs = Cabang::orderBy('nama_cabang')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        return view('admin.users.edit', compact('user', 'roles', 'cabangs', 'jurusans'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $timWawancaraRole = Role::where('nama', 'Tim Wawancara')->first();

        $isTimWawancara = $timWawancaraRole
            && (int) $request->role_id === (int) $timWawancaraRole->id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'unsur_wawancara' => $isTimWawancara
                ? 'required|in:operator,manajemen,scc_asrama,instruktur'
                : 'nullable|in:operator,manajemen,scc_asrama,instruktur',
            'cabang_id' => $isTimWawancara
                ? 'required|exists:cabangs,id'
                : 'nullable|exists:cabangs,id',
            'jurusan_id' => ($isTimWawancara && $request->unsur_wawancara === 'instruktur')
                ? 'required|exists:jurusans,id'
                : 'nullable|exists:jurusans,id',
            'is_active' => 'nullable|boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role_id' => $validated['role_id'],
            'unsur_wawancara' => $isTimWawancara
                ? $validated['unsur_wawancara']
                : null,
            'cabang_id' => $validated['cabang_id'] ?? null,
            'jurusan_id' => ($isTimWawancara && $validated['unsur_wawancara'] === 'instruktur')
                ? $validated['jurusan_id']
                : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function editPassword($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.password', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $validated = $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Password user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'User yang sedang login tidak boleh dihapus.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}