<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Asatidz;
use App\Models\MasterKegiatan;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(['name' => 'Admin'], [
            'description' => 'Akses penuh ke seluruh sistem Asatidz.',
            'status' => 'Active',
        ]);

        $asatidzRole = Role::updateOrCreate(['name' => 'Asatidz'], [
            'description' => 'Akses panel Asatidz untuk input kegiatan.',
            'status' => 'Active',
        ]);

        $adminUser = User::updateOrCreate(['username' => 'admin'], [
            'name' => 'Administrator',
            'email' => 'admin@asatidz.local',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'status' => 'Active',
            'last_active_at' => now(),
        ]);

        $asatidzUser = User::updateOrCreate(['username' => 'asatidz'], [
            'name' => 'Fulan Asatidz',
            'email' => 'asatidz@asatidz.local',
            'password' => bcrypt('password'),
            'role_id' => $asatidzRole->id,
            'status' => 'Active',
            'last_active_at' => now(),
        ]);

        Asatidz::updateOrCreate(['user_id' => $asatidzUser->id], [
            'nama' => 'Fulan Asatidz',
            'no_telepon' => '081234567890',
            'alamat' => 'Asrama Putra',
        ]);

        // Seed Master Kegiatan
        $kegiatans = [
            ['nama' => 'Oprak-oprak tidur', 'poin' => 5],
            ['nama' => 'Membangunkan qiyamul lail', 'poin' => 15],
            ['nama' => 'Patroli malam', 'poin' => 10],
            ['nama' => 'Mengawasi tandzif pagi', 'poin' => 5],
            ['nama' => 'Oprak jamaah', 'poin' => 5],
        ];

        foreach ($kegiatans as $k) {
            MasterKegiatan::updateOrCreate(['nama' => $k['nama']], [
                'poin' => $k['poin'],
                'status' => 'Aktif',
            ]);
        }

        $this->command?->newLine();
        $this->command?->info('Login seed accounts:');
        $this->command?->line('Admin   : username=admin   password=password');
        $this->command?->line('Asatidz : username=asatidz password=password');
        $this->command?->newLine();
    }
}
