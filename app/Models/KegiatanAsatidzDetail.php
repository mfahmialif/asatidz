<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanAsatidzDetail extends Model
{
    /** @use HasFactory<\Database\Factories\KegiatanAsatidzDetailFactory> */
    use HasFactory;

    protected $table = 'kegiatan_asatidz_detail';
    protected $fillable = ['kegiatan_asatidz_id', 'kegiatan_id', 'poin', 'keterangan'];

    public function kegiatanAsatidz()
    {
        return $this->belongsTo(KegiatanAsatidz::class);
    }

    public function masterKegiatan()
    {
        return $this->belongsTo(MasterKegiatan::class, 'kegiatan_id');
    }
}
