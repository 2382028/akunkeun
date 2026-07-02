<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\RefFasilitas;
// return type redirectResponse

// import model administrator
use App\Models\Administrator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

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
        $request->validate([
            'email' => 'required|email|unique:administrators',
        ]);

        DB::beginTransaction();
        try {
            $admin = Administrator::create([
                'email' => $request->email,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($request->has('roles')) {
                $admin->roles()->sync($request->roles); // relasi many-to-many
            }

            DB::commit();
            return redirect()->route('admin.index')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data!');
        }
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

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);

        if ($request->has('roles')) {
            $admin->roles()->sync($request->roles);
        } else {
            $admin->roles()->sync([]); // Kosongkan jika tidak ada role tambahan
        }

        return redirect()->route('admin.index')->with('success', 'Data berhasil diperbarui!');
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

    public function getFasilitas()
    {
        // Ambil data dengan status "aktif" dan hanya kolom yang diperlukan
        $fasilitas = RefFasilitas::where('status', 'aktif')
            ->select('nama_fasilitas', 'satuan')
            ->get();
        return response()->json($fasilitas);
    }
        public function notifAdmin(): JsonResponse
    {
        $admin = auth('administrator')->user(); // Ambil admin yang login

        $query = DB::table('notifications')
            ->where('notifications.is_read', 0);

        // Filter berdasarkan role yang dimiliki admin
        $query->where(function ($q) use ($admin) {
            $q->whereNull('role'); // Notifikasi tanpa role bisa dilihat semua
            $adminRoles = $admin->roles->pluck('nama_role')->toArray();
            if (!empty($adminRoles)) {
                $q->orWhereIn('role', $adminRoles);
            }
        });

        $notif = $query->count();

        $notifData = $query->orderBy('notifications.created_at', 'desc')->get();

        return response()->json([
            'notif' => $notif,
            'notifData' => $notifData,
            'total' => $notif > 0 ? 1 : 0
        ]);
    }
    function markAsReadAdmin($id)
    {
        // Mengubah status is_read menjadi 1
        DB::table('notifications')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    public function markAllAdmin(Request $request)
    {
        $roles = $request->roles; // array role admin

        if (!empty($roles)) {
            DB::table('notifications')
                ->whereIn('role', $roles) // tandai notifikasi yang sesuai role admin
                ->update(['is_read' => 1]);
        }

        return response()->json(['message' => 'Notifikasi berhasil diperbarui.']);
    }
}
