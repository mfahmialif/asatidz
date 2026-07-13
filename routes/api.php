<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsatidzController;
use App\Http\Controllers\KegiatanAsatidzController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MasterKegiatanController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/check-username', [AuthController::class, 'checkUsername']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-email-code', [AuthController::class, 'verifyEmailCode']);
Route::get('/auth/google/config', [AuthController::class, 'googleConfig']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);


Route::get('/settings/general', [SettingController::class, 'general']);
Route::get('/settings/login', [SettingController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', [AuthController::class, 'user']);
  Route::post('/logout', [AuthController::class, 'logout']);

  Route::get('/dashboard/admin', [DashboardController::class, 'admin']);
  Route::get('/dashboard/asatidz', [DashboardController::class, 'asatidz']);

  Route::get('/laporan', [LaporanController::class, 'index']);
  Route::get('/laporan/export', [LaporanController::class, 'export']);
  Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf']);

  Route::apiResource('roles', RoleController::class);
  Route::apiResource('users', UserController::class);
  Route::apiResource('asatidz', AsatidzController::class);
  Route::post('/asatidz/{id}/reset-password', [AsatidzController::class, 'resetPassword']);
  Route::apiResource('master-kegiatan', MasterKegiatanController::class);
  Route::apiResource('kegiatan-asatidz', KegiatanAsatidzController::class);

  Route::post('/settings/general', [SettingController::class, 'updateGeneral']);
  Route::get('/settings/email', [SettingController::class, 'email']);
  Route::put('/settings/email', [SettingController::class, 'updateEmail']);
  Route::get('/settings/google-login', [SettingController::class, 'googleLogin']);
  Route::put('/settings/google-login', [SettingController::class, 'updateGoogleLogin']);
  Route::post('/settings/login', [SettingController::class, 'updateLogin']);
  Route::get('/activity-logs', [ActivityLogController::class, 'index']);
  Route::get('/activity-logs/notifications', [ActivityLogController::class, 'notifications']);
  Route::post('/activity-logs/mark-read', [ActivityLogController::class, 'markRead']);
});
