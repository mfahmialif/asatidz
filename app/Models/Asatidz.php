<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asatidz extends Model
{
    /** @use HasFactory<\Database\Factories\AsatidzFactory> */
    use HasFactory, \App\Traits\LogsActivity;

    protected $table = 'asatidz';
    protected $fillable = ['user_id', 'nama', 'no_telepon', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->hasMany(KegiatanAsatidz::class);
    }
}
