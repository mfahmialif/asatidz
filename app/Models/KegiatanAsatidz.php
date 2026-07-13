<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanAsatidz extends Model
{
    /** @use HasFactory<\Database\Factories\KegiatanAsatidzFactory> */
    use HasFactory, \App\Traits\LogsActivity;

    public function getActivityLogTitle() {
        return 'Kegiatan tanggal ' . ($this->tanggal_kegiatan ? $this->tanggal_kegiatan->format('d/m/Y') : '-');
    }

    protected $table = 'kegiatan_asatidz';
    protected $fillable = ['asatidz_id', 'tanggal_kegiatan', 'total_poin'];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    public function asatidz()
    {
        return $this->belongsTo(Asatidz::class);
    }

    public function details()
    {
        return $this->hasMany(KegiatanAsatidzDetail::class);
    }
}
