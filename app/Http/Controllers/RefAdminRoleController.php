<?php

namespace App\Http\Controllers;
use App\Models\RefAdminRole;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RefAdminRoleController extends Controller
{
    // Menyimpan role baru
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_role' => 'required|string|max:255|unique:ref_admin_roles,nama_role',
        ]);

        RefAdminRole::create([
            'nama_role' => $request->nama_role,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Role berhasil ditambahkan.');
    }

    // Mengupdate role
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'nama_role' => 'required|string|max:255|unique:ref_admin_roles,nama_role,' . $id,
        ]);

        $role = RefAdminRole::findOrFail($id);
        $role->update([
            'nama_role' => $request->nama_role,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Role berhasil diperbarui.');
    }

    // Menghapus role
    public function destroy($id): RedirectResponse
    {
        $role = RefAdminRole::findOrFail($id);
        $role->delete();

        return redirect()->back()->with('success', 'Role berhasil dihapus.');
    }
}
