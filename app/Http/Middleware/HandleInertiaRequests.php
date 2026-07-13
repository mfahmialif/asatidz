<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\AppSetting;

class HandleInertiaRequests extends Middleware
{
 /**
  * The root template that is loaded on the first page visit.
  *
  * @var string
  */
 protected $rootView = 'app';

 /**
  * Determine the current asset version.
  */
 public function version(Request $request): ?string
 {
  return parent::version($request);
 }

 /**
  * Define the props that are shared by default.
  *
  * @return array<string, mixed>
  */
 public function share(Request $request): array
 {
  return [
   ...parent::share($request),
   'auth' => [
    'user' => $request->user(),
   ],
   'app_settings' => [
    'system_name' => AppSetting::getValue('system_name', 'Asatidz'),
    'navbar_brand_mode' => AppSetting::getValue('navbar_brand_mode', 'text'),
    'navbar_text_icon' => AppSetting::getValue('navbar_text_icon', 'auto_stories'),
    'navbar_text_title' => AppSetting::getValue('navbar_text_title', AppSetting::getValue('system_name', 'Asatidz')),
    'navbar_text_subtitle' => AppSetting::getValue('navbar_text_subtitle', 'Prodi'),
    'navbar_logo_url' => AppSetting::getValue('navbar_logo_url', ''),
    'favicon_url' => AppSetting::getValue('favicon_url', ''),
    'accent_color' => AppSetting::getValue('accent_color', '#111827'),
   ]
  ];
 }
}
