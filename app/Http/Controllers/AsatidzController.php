<?php

namespace App\Http\Controllers;

use App\Models\Asatidz;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AsatidzController extends Controller
{
    public function index(Request $request)
    {
        $query = Asatidz::with('user');
        
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('username', 'like', '%' . $request->search . '%');
                  });
        }

        $perPage = $request->input('per_page', 10);
        return response()->json($query->orderBy('created_at', 'desc')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'no_telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::where('name', 'Asatidz')->first();
            $user = User::create([
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role_id' => $role ? $role->id : null,
                'status' => $validated['status']
            ]);

            $asatidz = Asatidz::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'no_telepon' => $validated['no_telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            DB::commit();
            return response()->json(['message' => 'Asatidz berhasil ditambahkan', 'data' => $asatidz->load('user')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menambahkan Asatidz', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $asatidz = Asatidz::with('user')->findOrFail($id);
        return response()->json($asatidz);
    }

    public function update(Request $request, $id)
    {
        $asatidz = Asatidz::findOrFail($id);
        $user = $asatidz->user;

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'no_telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'status' => $validated['status']
            ];
            
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            $user->update($userData);

            $asatidz->update([
                'nama' => $validated['nama'],
                'no_telepon' => $validated['no_telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            DB::commit();
            return response()->json(['message' => 'Asatidz berhasil diperbarui', 'data' => $asatidz->load('user')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memperbarui Asatidz', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $asatidz = Asatidz::findOrFail($id);
            $userId = $asatidz->user_id;
            
            $asatidz->delete();
            User::find($userId)->delete();
            
            DB::commit();
            return response()->json(['message' => 'Asatidz berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus Asatidz', 'error' => $e->getMessage()], 500);
        }
    }

    public function resetPassword($id)
    {
        DB::beginTransaction();
        try {
            $asatidz = Asatidz::findOrFail($id);
            $user = $asatidz->user;
            
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }

            $user->update([
                'password' => Hash::make('password')
            ]);
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'module' => 'Asatidz',
                'action' => 'updated',
                'subject_type' => Asatidz::class,
                'subject_id' => $asatidz->id,
                'subject_title' => substr($asatidz->nama, 0, 255),
                'description' => 'Mereset password asatidz',
                'properties' => [],
            ]);
            
            DB::commit();
            return response()->json(['message' => 'Password berhasil direset menjadi "password"']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mereset password', 'error' => $e->getMessage()], 500);
        }
    }
}
