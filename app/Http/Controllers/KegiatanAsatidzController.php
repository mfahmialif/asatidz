<?php

namespace App\Http\Controllers;

use App\Models\KegiatanAsatidz;
use App\Models\KegiatanAsatidzDetail;
use App\Models\MasterKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KegiatanAsatidzController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = KegiatanAsatidz::with('details.masterKegiatan');
        
        if ($user->role->name === 'Asatidz') {
            $query->where('asatidz_id', $user->asatidz->id);
        } else {
            // If admin or other, they might view all and we can eager load asatidz
            $query->with('asatidz.user');
        }

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal_kegiatan', $request->tanggal);
        }
        
        // Sorting
        $query->orderBy('tanggal_kegiatan', 'desc');

        $perPage = $request->input('per_page', 10);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role->name !== 'Asatidz') {
            return response()->json(['message' => 'Hanya Asatidz yang dapat menginput kegiatan'], 403);
        }

        $request->validate([
            'tanggal_kegiatan' => [
                'required',
                'date',
                Rule::unique('kegiatan_asatidz')->where(function ($query) use ($user) {
                    return $query->where('asatidz_id', $user->asatidz->id);
                })
            ],
            'details' => 'required|array|min:1',
            'details.*.master_kegiatan_id' => 'required|exists:master_kegiatan,id',
            'details.*.keterangan' => 'nullable|string',
        ], [
            'tanggal_kegiatan.unique' => 'Anda sudah menginput kegiatan pada tanggal tersebut.',
            'details.required' => 'Detail kegiatan tidak boleh kosong.',
        ]);

        try {
            DB::beginTransaction();

            $totalPoin = 0;
            $detailsData = [];
            
            // Calculate total points and prepare details
            foreach ($request->details as $detail) {
                $masterKegiatan = MasterKegiatan::find($detail['master_kegiatan_id']);
                $poin = $masterKegiatan ? $masterKegiatan->poin : 0;
                
                $totalPoin += $poin;
                
                $detailsData[] = new KegiatanAsatidzDetail([
                    'kegiatan_id' => $detail['master_kegiatan_id'],
                    'poin' => $poin,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }

            $kegiatan = KegiatanAsatidz::create([
                'asatidz_id' => $user->asatidz->id,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'total_poin' => $totalPoin,
            ]);

            $kegiatan->details()->saveMany($detailsData);

            DB::commit();
            return response()->json([
                'message' => 'Kegiatan berhasil disimpan',
                'data' => $kegiatan->load('details.masterKegiatan')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $query = KegiatanAsatidz::with('details.masterKegiatan', 'asatidz.user');
        
        if ($user->role->name === 'Asatidz') {
            $query->where('asatidz_id', $user->asatidz->id);
        }
        
        $kegiatan = $query->findOrFail($id);
        
        return response()->json(['data' => $kegiatan]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $query = KegiatanAsatidz::query();
        if ($user->role->name === 'Asatidz') {
            $query->where('asatidz_id', $user->asatidz->id);
        }
        
        $kegiatan = $query->findOrFail($id);

        $request->validate([
            'tanggal_kegiatan' => [
                'required',
                'date',
                Rule::unique('kegiatan_asatidz')->where(function ($q) use ($kegiatan) {
                    return $q->where('asatidz_id', $kegiatan->asatidz_id);
                })->ignore($kegiatan->id)
            ],
            'details' => 'required|array|min:1',
            'details.*.master_kegiatan_id' => 'required|exists:master_kegiatan,id',
            'details.*.keterangan' => 'nullable|string',
        ], [
            'tanggal_kegiatan.unique' => 'Kegiatan pada tanggal tersebut sudah ada.',
            'details.required' => 'Detail kegiatan tidak boleh kosong.',
        ]);

        try {
            DB::beginTransaction();

            $totalPoin = 0;
            
            // Delete old details
            $kegiatan->details()->delete();
            
            $detailsData = [];
            foreach ($request->details as $detail) {
                $masterKegiatan = MasterKegiatan::find($detail['master_kegiatan_id']);
                $poin = $masterKegiatan ? $masterKegiatan->poin : 0;
                
                $totalPoin += $poin;
                
                $detailsData[] = new KegiatanAsatidzDetail([
                    'kegiatan_id' => $detail['master_kegiatan_id'],
                    'poin' => $poin,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }

            $kegiatan->update([
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'total_poin' => $totalPoin,
            ]);
            $kegiatan->touch();

            $kegiatan->details()->saveMany($detailsData);

            DB::commit();
            return response()->json([
                'message' => 'Kegiatan berhasil diupdate',
                'data' => $kegiatan->load('details.masterKegiatan')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        $query = KegiatanAsatidz::query();
        if ($user->role->name === 'Asatidz') {
            $query->where('asatidz_id', $user->asatidz->id);
        }
        
        $kegiatan = $query->findOrFail($id);
        
        $kegiatan->details()->delete();
        $kegiatan->delete();

        return response()->json(['message' => 'Kegiatan berhasil dihapus']);
    }
}
