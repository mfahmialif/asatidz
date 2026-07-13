<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/check-username', [AuthController::class, 'checkUsername']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-email-code', [AuthController::class, 'verifyEmailCode']);
Route::get('/auth/google/config', [AuthController::class, 'googleConfig']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);

Route::get('/navigation/primary', [MenuController::class, 'primary']);
Route::get('/settings/general', [SettingController::class, 'general']);
Route::get('/settings/landing', [SettingController::class, 'landing']);
Route::get('/settings/login', [SettingController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', [AuthController::class, 'user']);
  Route::post('/logout', [AuthController::class, 'logout']);

  Route::apiResource('roles', RoleController::class);
  Route::apiResource('users', UserController::class);
  Route::get('/menus/primary/items', [MenuController::class, 'items']);
  Route::post('/menus/primary/items', [MenuController::class, 'storeItem']);
  Route::put('/menus/primary/items/{menuItem}', [MenuController::class, 'updateItem']);
  Route::delete('/menus/primary/items/{menuItem}', [MenuController::class, 'destroyItem']);
  Route::post('/menus/primary/reorder', [MenuController::class, 'reorder']);
  Route::get('/menus/primary/options', [MenuController::class, 'options']);
  Route::post('/settings/general', [SettingController::class, 'updateGeneral']);
  Route::get('/settings/email', [SettingController::class, 'email']);
  Route::put('/settings/email', [SettingController::class, 'updateEmail']);
  Route::get('/settings/google-login', [SettingController::class, 'googleLogin']);
  Route::put('/settings/google-login', [SettingController::class, 'updateGoogleLogin']);
  Route::post('/settings/landing', [SettingController::class, 'updateLanding']);
  Route::post('/settings/login', [SettingController::class, 'updateLogin']);
  Route::get('/activity-logs', [ActivityLogController::class, 'index']);
  Route::get('/activity-logs/notifications', [ActivityLogController::class, 'notifications']);
  Route::post('/activity-logs/mark-read', [ActivityLogController::class, 'markRead']);
});
