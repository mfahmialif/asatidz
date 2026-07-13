<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKegiatan extends Model
{
    /** @use HasFactory<\Database\Factories\MasterKegiatanFactory> */
    use HasFactory, \App\Traits\LogsActivity;

    protected $table = 'master_kegiatan';
    protected $fillable = ['nama', 'poin', 'status'];
}
