<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
// return type redirectResponse

// import model administrator
use App\Models\Administrator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // function index untuk get all data
    public function index()
    {
        return view('admin.kelola_user.administrator', [
            'admins' => Administrator::all(),
            'title' => 'Data Administrator',
        ]);
    }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.kelola_user.administrator', ['title' => 'Data Administrator',]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {

        DB::table('administrators')->insertOrIgnore([
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {
        // get admin by id
        return view('admin.kelola_user.detail_administrator', [
            'admin' => Administrator::findOrFail($id),
            'title' => 'Data Administrator',
        ]);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $admin = Administrator::findOrFail($id);
        $admin->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'updated_at' => now()
        ]);


        return redirect()->route('admin.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $admin = Administrator::findOrFail($id);

        //delete post
        $admin->delete();

        //redirect to index
        return redirect('admin')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
