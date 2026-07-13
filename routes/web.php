<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () { return Inertia::render('public/landing/Index'); })->name('landing');
Route::get('/contact', function () { return Inertia::render('public/contact/Index'); })->name('contact');
Route::get('/login', function () { return Inertia::render('public/login/Index'); })->name('login');
Route::get('/register', function () { return Inertia::render('public/register/Index'); })->name('register');
Route::get('/verify-email', function () { return Inertia::render('public/verify-email/Index'); })->name('verify-email');

Route::prefix('administrator')->middleware('auth:sanctum')->group(function () {
  Route::get('/', function () { return redirect('/administrator/dashboard'); });
  Route::get('/dashboard', function () { return Inertia::render('admin/dashboard/Index', ['pageTitle' => 'Dashboard']); })->name('admin.dashboard');
  Route::get('/menus', function () { return Inertia::render('admin/menus/Index', ['pageTitle' => 'Manajemen Menu']); })->name('admin.menus');
  Route::get('/logs', function () { return Inertia::render('admin/logs/Index', ['pageTitle' => 'Log Aktivitas']); })->name('admin.logs');
  Route::get('/manajemen-user', function () { return Inertia::render('admin/user/User', ['pageTitle' => 'Manajemen User']); })->name('admin.users');
  Route::get('/manajemen-role', function () { return Inertia::render('admin/user/Role', ['pageTitle' => 'Manajemen Role']); })->name('admin.roles');
  Route::get('/profile', function () { return Inertia::render('admin/profile/Index', ['pageTitle' => 'Profile']); })->name('admin.profile');
  Route::get('/pengaturan', function () { return Inertia::render('admin/pengaturan/Index', ['pageTitle' => 'Pengaturan']); })->name('admin.pengaturan');
});
