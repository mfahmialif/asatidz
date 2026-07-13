<?php

namespace App\Http\Controllers;

use App\Models\MasterKegiatan;
use Illuminate\Http\Request;

class MasterKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterKegiatan::query();
        
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        
        return response()->json($query->orderBy('created_at', 'desc')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $kegiatan = MasterKegiatan::create($validated);
        
        return response()->json(['message' => 'Kegiatan berhasil ditambahkan', 'data' => $kegiatan], 201);
    }

    public function show($id)
    {
        $kegiatan = MasterKegiatan::findOrFail($id);
        return response()->json($kegiatan);
    }

    public function update(Request $request, $id)
    {
        $kegiatan = MasterKegiatan::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $kegiatan->update($validated);
        
        return response()->json(['message' => 'Kegiatan berhasil diperbarui', 'data' => $kegiatan]);
    }

    public function destroy($id)
    {
        $kegiatan = MasterKegiatan::findOrFail($id);
        $kegiatan->delete();
        
        return response()->json(['message' => 'Kegiatan berhasil dihapus']);
    }
}
