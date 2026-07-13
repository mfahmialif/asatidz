<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth('sanctum')->check()) {
        $user = auth('sanctum')->user();
        if ($user && $user->role && $user->role->name === 'Asatidz') {
            return redirect('/asatidz/dashboard');
        }
        return redirect('/administrator/dashboard');
    }
    return redirect('/login');
})->name('landing');
Route::get('/contact', function () { return Inertia::render('public/contact/Index'); })->name('contact');
Route::get('/login', function () { return Inertia::render('public/login/Index'); })->name('login');
Route::get('/register', function () { return Inertia::render('public/register/Index'); })->name('register');
Route::get('/verify-email', function () { return Inertia::render('public/verify-email/Index'); })->name('verify-email');

Route::prefix('administrator')->middleware('auth:sanctum')->group(function () {
  Route::get('/', function () { return redirect('/administrator/dashboard'); });
  Route::get('/dashboard', function () { return Inertia::render('admin/dashboard/Index', ['pageTitle' => 'Dashboard']); })->name('admin.dashboard');
  Route::get('/logs', function () { return Inertia::render('admin/logs/Index', ['pageTitle' => 'Log Aktivitas']); })->name('admin.logs');
  Route::get('/manajemen-user', function () { return Inertia::render('admin/user/User', ['pageTitle' => 'Manajemen User']); })->name('admin.users');
  Route::get('/manajemen-role', function () { return Inertia::render('admin/user/Role', ['pageTitle' => 'Manajemen Role']); })->name('admin.roles');
  Route::get('/master-kegiatan', function () { return Inertia::render('admin/master-kegiatan/Index', ['pageTitle' => 'Master Kegiatan']); })->name('admin.master-kegiatan');
  Route::get('/laporan', function () { return Inertia::render('admin/laporan/Index', ['pageTitle' => 'Laporan & Rekapitulasi']); })->name('admin.laporan');
  Route::get('/asatidz', function () { return Inertia::render('admin/master-asatidz/Index', ['pageTitle' => 'Data Asatidz']); })->name('admin.asatidz');
  Route::get('/asatidz/{id}', function ($id) { return Inertia::render('admin/master-asatidz/Show', ['pageTitle' => 'Detail Asatidz', 'id' => $id]); })->name('admin.asatidz.show');
  Route::get('/kegiatan-asatidz', function () { return Inertia::render('admin/kegiatan-asatidz/Index', ['pageTitle' => 'Kegiatan Asatidz']); })->name('admin.kegiatan-asatidz');
  Route::get('/profile', function () { return Inertia::render('admin/profile/Index', ['pageTitle' => 'Profile']); })->name('admin.profile');
  Route::get('/pengaturan', function () { return Inertia::render('admin/pengaturan/Index', ['pageTitle' => 'Pengaturan']); })->name('admin.pengaturan');
});

Route::prefix('asatidz')->middleware('auth:sanctum')->group(function () {
  Route::get('/', function () { return redirect('/asatidz/dashboard'); });
  Route::get('/dashboard', function () { return Inertia::render('asatidz/dashboard/Index', ['pageTitle' => 'Dashboard']); })->name('asatidz.dashboard');
  Route::get('/kegiatan-asatidz', function () { return Inertia::render('asatidz/kegiatan-asatidz/Index', ['pageTitle' => 'Kegiatanku']); })->name('asatidz.kegiatan-asatidz');
});
