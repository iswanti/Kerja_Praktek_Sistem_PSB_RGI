<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use App\Models\RoleMenuPermission;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $roles = Role::withCount('users')
            ->when($request->search, function ($query) use ($request) {
                $query->where('nama', 'ilike', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'ilike', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $menus = Menu::where('is_active', true)
            ->where(function ($query) {
                $query->whereNotNull('parent_id')
                    ->orWhereNotNull('route');
            })
            ->orderBy('order')
            ->get();

        return view('admin.roles.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:roles,nama',
            'deskripsi' => 'nullable|string',
        ]);

        $role = Role::create([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'is_active' => true,
        ]);

        foreach ($request->permissions ?? [] as $menuId => $permission) {
            RoleMenuPermission::create([
                'role_id' => $role->id,
                'menu_id' => $menuId,
                'can_read' => isset($permission['read']),
                'can_create' => isset($permission['create']),
                'can_update' => isset($permission['update']),
                'can_delete' => isset($permission['delete']),
                'can_download' => isset($permission['download']),
            ]);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Kewenangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        $menus = Menu::where('is_active', true)
            ->where(function ($query) {
                $query->whereNotNull('parent_id')
                    ->orWhereNotNull('route');
            })
            ->orderBy('order')
            ->get();

        return view('admin.roles.edit', compact('role', 'menus'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:roles,nama,' . $role->id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $role->update([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        RoleMenuPermission::where('role_id', $role->id)->delete();

        foreach ($request->permissions ?? [] as $menuId => $permission) {
            RoleMenuPermission::create([
                'role_id' => $role->id,
                'menu_id' => $menuId,
                'can_read' => isset($permission['read']),
                'can_create' => isset($permission['create']),
                'can_update' => isset($permission['update']),
                'can_delete' => isset($permission['delete']),
                'can_download' => isset($permission['download']),
            ]);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Kewenangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $role = Role::withCount('users')->findOrFail($id);

        if ($role->users_count > 0) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user.');
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Kewenangan berhasil dihapus.');
    }

    public function permission($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        $menus = Menu::where('is_active', true)
            ->where(function ($query) {
                $query->whereNotNull('parent_id')
                    ->orWhereNotNull('route');
            })
            ->orderBy('order')
            ->get();

        return view('admin.roles.permission', compact('role', 'menus'));
    }

    public function updatePermission(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        RoleMenuPermission::where('role_id', $role->id)->delete();

        foreach ($request->permissions ?? [] as $menuId => $permission) {
            RoleMenuPermission::create([
                'role_id' => $role->id,
                'menu_id' => $menuId,
                'can_read' => isset($permission['read']),
                'can_create' => isset($permission['create']),
                'can_update' => isset($permission['update']),
                'can_delete' => isset($permission['delete']),
                'can_download' => isset($permission['download']),
            ]);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Hak akses role berhasil diperbarui.');
    }
}