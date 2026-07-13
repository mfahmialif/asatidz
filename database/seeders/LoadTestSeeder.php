<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoadTestSeeder extends Seeder
{
    public function run()
    {
        // 1. Get or create Master Kegiatan
        $masterKegiatans = DB::table('master_kegiatan')->pluck('id')->toArray();
        if (empty($masterKegiatans)) {
            DB::table('master_kegiatan')->insert([
                ['nama' => 'Mengajar TPQ', 'poin' => 10, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
                ['nama' => 'Kajian Rutin', 'poin' => 20, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ]);
            $masterKegiatans = DB::table('master_kegiatan')->pluck('id')->toArray();
        }

        $asatidzCount = 200; // Let's use 200 Asatidz
        $kegiatanPerAsatidz = 500; // 500 kegiatan per asatidz = 100,000 kegiatan
        
        $password = Hash::make('password');
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $asatidzRoleId = DB::table('roles')->where('name', 'asatidz')->value('id');
        if (!$asatidzRoleId) {
            $asatidzRoleId = DB::table('roles')->insertGetId(['name' => 'asatidz', 'guard_name' => 'web']);
        }

        echo "Generating {$asatidzCount} Asatidz with {$kegiatanPerAsatidz} kegiatan each (Total: " . ($asatidzCount * $kegiatanPerAsatidz) . " kegiatan)...\n";

        for ($i = 0; $i < $asatidzCount; $i++) {
            $userId = DB::table('users')->insertGetId([
                'username' => 'loadtest_' . time() . '_' . $i,
                'name' => 'Ust. Load Test ' . $i,
                'email' => 'loadtest' . time() . '_' . $i . '@test.com',
                'password' => $password,
                'role_id' => $asatidzRoleId,
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $asatidzId = DB::table('asatidz')->insertGetId([
                'user_id' => $userId,
                'nama' => 'Ust. Load Test ' . $i,
                'no_telepon' => '0812' . rand(1000000, 9999999),
                'alamat' => 'Alamat Load Test ' . $i,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $detailsBatch = [];
            
            // To ensure unique dates, we'll start from today and step back 1 day at a time
            $currentDate = clone Carbon::now();
            
            for ($k = 0; $k < $kegiatanPerAsatidz; $k++) {
                $tanggal = $currentDate->subDays(1)->format('Y-m-d');
                $poin = rand(10, 50);

                $kegiatanId = DB::table('kegiatan_asatidz')->insertGetId([
                    'asatidz_id' => $asatidzId,
                    'tanggal_kegiatan' => $tanggal,
                    'total_poin' => $poin,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $detailsBatch[] = [
                    'kegiatan_asatidz_id' => $kegiatanId,
                    'kegiatan_id' => $masterKegiatans[array_rand($masterKegiatans)],
                    'poin' => $poin,
                    'keterangan' => 'Kegiatan bulk load ' . $k,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert details in chunks
            $chunks = array_chunk($detailsBatch, 1000);
            foreach ($chunks as $chunk) {
                DB::table('kegiatan_asatidz_detail')->insert($chunk);
            }

            if (($i + 1) % 10 == 0) {
                echo "Processed " . ($i + 1) . " asatidz...\n";
            }
        }

        echo "Done!\n";
    }
}
