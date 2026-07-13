<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatan_asatidz_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_asatidz_id')->constrained('kegiatan_asatidz')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained('master_kegiatan')->onDelete('cascade');
            $table->integer('poin');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_asatidz_detail');
    }
};
