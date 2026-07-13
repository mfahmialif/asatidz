<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
 public function run(): void
 {
  $settings = [
   ['system_name', 'Asatidz'],
   ['accent_color', '#111827'],
   ['favicon_path', ''],
   ['favicon_url', ''],

   ['navbar_brand_mode', 'text'],
   ['navbar_text_icon', 'school'],
   ['navbar_text_title', 'Asatidz'],
   ['navbar_text_subtitle', 'Prodi'],
   ['navbar_logo_path', ''],
   ['navbar_logo_url', ''],

   ['login_image_path', ''],
   ['login_image_url', ''],

   ['smtp_enabled', false, 'boolean'],
   ['smtp_host', ''],
   ['smtp_port', 587, 'integer'],
   ['smtp_username', ''],
   ['smtp_password', ''],
   ['smtp_encryption', 'tls'],
   ['smtp_from_address', 'asatidz@asatidz.id'],
   ['smtp_from_name', 'Asatidz'],

   ['google_login_enabled', false, 'boolean'],
   ['google_client_id', ''],
   ['google_client_secret', ''],
   ['google_hosted_domain', ''],
   ['google_auto_create_users', false, 'boolean'],

   ['hero_image_path', ''],
   ['hero_image_url', ''],
   ['hero_image_alt', 'HB segaf'],
   ['hero_label', 'Rektor '],
   ['hero_title', 'Dr. Segaf Baharun, M.H.I'],
   ['hero_badge_title', 'Akademik aktif'],
   ['hero_badge_description', 'Informasi prodi, kegiatan, dan layanan mahasiswa'],

   ['landing_content', json_encode($this->landingContent(), JSON_UNESCAPED_UNICODE)],
  ];

  foreach ($settings as $setting) {
   AppSetting::setValue($setting[0], $setting[1], $setting[2] ?? 'string');
  }
 }

 private function landingContent(): array
 {
  return [
   'hero' => [
    'image_path' => '',
    'image_url' => '',
    'image_alt' => 'HB segaf',
    'eyebrow' => 'FAKULTAS',
    'main_title' => 'Asatidz',
    'description' => 'Website resmi Asatidz untuk informasi akademik, berita, kegiatan mahasiswa, dan layanan komunikasi prodi.',
    'primary_label' => 'Lihat Akademik',
    'primary_href' => '#services',
    'secondary_label' => 'Kegiatan Asatidz',
    'secondary_href' => '#flow',
    'label' => 'Rektor ',
    'title' => 'Dr. Segaf Baharun, M.H.I',
   ],
   'visionMission' => [
    'kicker' => 'Visi Misi',
    'title' => 'Arah pengembangan Asatidz.',
    'vision_title' => 'Visi',
    'vision_body' => 'Menjadi prodi yang unggul dalam pengembangan keasatidzahan, riset pembelajaran, linguistik terapan, dan tradisi keilmuan berbasis nilai keislaman serta kebutuhan masyarakat.',
    'mission_title' => 'Misi',
    'items' => [
     ['icon' => 'menu_book', 'title' => 'Kajian Asatidz', 'body' => 'Menyelenggarakan pembelajaran keasatidzahan, kajian bahasa dan sastra, serta budaya dan keislaman yang kuat secara teori dan praktik.'],
     ['icon' => 'science', 'title' => 'Riset Asatidz', 'body' => 'Mengembangkan penelitian keasatidzahan, kajian bahasa dan sastra, budaya dan keislaman, dan turats yang relevan dengan kebutuhan akademik.'],
     ['icon' => 'groups', 'title' => 'Pengabdian dan Kolaborasi', 'body' => 'Membangun jejaring akademik serta menerapkan keasatidzahan dalam kegiatan masyarakat.'],
    ],
   ],
   'services' => [
    'kicker' => 'Akademik',
    'title' => 'Informasi dan layanan Asatidz.',
    'items' => [
     ['icon' => 'groups', 'title' => 'Informasi Akademik', 'body' => 'Akses informasi kurikulum, kegiatan kuliah, dan agenda akademik prodi.', 'href' => '#services'],
     ['icon' => 'translate', 'title' => 'Kajian Asatidz', 'body' => 'Pembelajaran keasatidzahan, kajian bahasa dan sastra, metodologi riset, dan tradisi keilmuan Islam.', 'href' => '#about'],
     ['icon' => 'groups', 'title' => 'Kegiatan Mahasiswa', 'body' => 'Ikuti kabar seminar, diskusi ilmiah, komunitas, dan aktivitas kemahasiswaan.', 'href' => '#flow'],
     ['icon' => 'support_agent', 'title' => 'Layanan Prodi', 'body' => 'Hubungi pengelola prodi untuk kebutuhan administrasi dan informasi studi.', 'href' => '#contact'],
    ],
   ],
   'supporters' => [
    'kicker' => 'Supporter',
    'title' => 'Ekosistem yang mendukung gerakan literasi kampus.',
    'items' => [
     ['display_type' => 'text', 'icon' => 'support_agent', 'logo_path' => '', 'logo_url' => '', 'name' => ''],
     ['display_type' => 'text', 'icon' => 'history_edu', 'logo_path' => '', 'logo_url' => '', 'name' => 'Asatidz'],
     ['display_type' => 'icon', 'icon' => 'history_edu', 'logo_path' => '', 'logo_url' => '', 'name' => 'Mahasiswa'],
     ['display_type' => 'icon', 'icon' => 'science', 'logo_path' => '', 'logo_url' => '', 'name' => 'Riset Prodi'],
     ['display_type' => 'icon', 'icon' => 'history_edu', 'logo_path' => '', 'logo_url' => '', 'name' => 'Kajian Turats'],
     ['display_type' => 'icon', 'icon' => 'public', 'logo_path' => '', 'logo_url' => '', 'name' => 'Budaya dan keislaman'],
     ['display_type' => 'icon', 'icon' => 'school', 'logo_path' => '', 'logo_url' => '', 'name' => 'Youtube'],
    ],
   ],
   'news' => [
    'kicker' => 'Asatidz Update',
    'title' => 'Berita, kegiatan, dan informasi prodi.',
    'description' => 'Kanal update dipakai untuk mengabarkan agenda akademik, kegiatan mahasiswa, informasi layanan, dan pengumuman prodi.',
    'button_label' => 'Semua News',
    'item_count' => 6,
    'fallback_items' => [
     ['day' => '12', 'month' => 'Mei', 'category' => 'Akademik', 'title' => 'Agenda perkuliahan dan kegiatan Asatidz semester ini', 'body' => 'Informasi akademik terbaru disiapkan untuk mahasiswa, dosen, dan calon mahasiswa.', 'image' => '/img/news/news1.jpg'],
     ['day' => '08', 'month' => 'Mei', 'category' => 'Mahasiswa', 'title' => 'Kegiatan diskusi keasatidzahan dibuka untuk mahasiswa', 'body' => 'Program ini menjadi ruang penguatan wawasan keasatidzahan, kajian bahasa dan sastra, serta budaya dan keislaman.', 'image' => '/img/news/news2.jpg'],
     ['day' => '01', 'month' => 'Mei', 'category' => 'Prodi', 'title' => 'Profil Asatidz diperbarui untuk akses publik', 'body' => 'Website ini menyajikan informasi prodi, berita, layanan, dan kontak resmi.', 'image' => '/img/news/news3.jpg'],
    ],
   ],
   'about' => [
    'kicker' => 'Tentang Kami',
    'title' => 'Mengembangkan kajian keasatidzahan, kajian bahasa dan sastra, serta budaya dan keislaman.',
    'body' => 'Asatidz berfokus pada kajian keasatidzahan, analisis sumber, pemahaman budaya, dan penguatan tradisi keilmuan. Website ini menjadi kanal informasi akademik, berita, kegiatan, dan layanan prodi.',
    'stats' => [
     ['value' => '4', 'label' => 'Fokus Kajian'],
     ['value' => '30+', 'label' => 'Kegiatan Akademik'],
     ['value' => '100+', 'label' => 'Mahasiswa Aktif'],
    ],
   ],
   'publishCta' => [
    'kicker' => 'Asatidz',
    'title' => 'Temukan informasi akademik dan kegiatan terbaru Asatidz.',
    'button_label' => 'Hubungi Asatidz',
    'button_href' => '#contact',
   ],
   'strengths' => [
    'kicker' => 'Kenapa Asatidz?',
    'title' => 'Ruang akademik untuk keasatidzahan, riset pembelajaran, dan tradisi keilmuan.',
    'items' => [
     ['icon' => 'history_edu', 'title' => 'Kajian Pendidikan', 'body' => 'Mahasiswa diarahkan mengembangkan riset keasatidzahan klasik, modern, dan kontemporer.'],
     ['icon' => 'record_voice_over', 'title' => 'Kompetensi Riset', 'body' => 'Pembelajaran menekankan metodologi riset, publikasi akademik, dan analisis pembelajaran Asatidz.'],
     ['icon' => 'history_edu', 'title' => 'Tradisi Keilmuan', 'body' => 'Kajian prodi terhubung dengan turats, budaya dan keislaman, dan khazanah keilmuan pesantren.'],
     ['icon' => 'forum', 'title' => 'Diskusi Akademik', 'body' => 'Kegiatan kelas dan forum ilmiah mendorong mahasiswa berpikir kritis dan komunikatif.'],
     ['icon' => 'public', 'title' => 'Wawasan Budaya', 'body' => 'Mahasiswa mempelajari konteks budaya dan keislaman, pedagogi, dan pemikiran keislaman secara luas.'],
     ['icon' => 'handshake', 'title' => 'Kolaborasi', 'body' => 'Prodi terbuka untuk kerja sama akademik, seminar, dan pengembangan kompetensi mahasiswa.'],
    ],
   ],
   'flow' => [
    'kicker' => 'Kegiatan Prodi',
    'title' => 'Pengalaman belajar yang terarah dan aktif.',
    'items' => [
     ['no' => '01', 'title' => 'Pembelajaran', 'body' => 'Mahasiswa mengikuti perkuliahan inti keasatidzahan, kajian bahasa dan sastra, serta budaya dan keislaman.'],
     ['no' => '02', 'title' => 'Diskusi Ilmiah', 'body' => 'Forum akademik memperkuat analisis teks, riset, dan komunikasi.'],
     ['no' => '03', 'title' => 'Praktik Riset', 'body' => 'Kegiatan praktik membantu mahasiswa mengolah sumber dan data penelitian keasatidzahan secara aktif.'],
     ['no' => '04', 'title' => 'Riset', 'body' => 'Mahasiswa diarahkan menyusun kajian dan karya ilmiah berbasis bidang prodi.'],
     ['no' => '05', 'title' => 'Pengabdian', 'body' => 'Kompetensi diterapkan dalam kegiatan masyarakat dan jejaring akademik.'],
    ],
   ],
   'testimonials' => [
    'kicker' => 'Testimoni',
    'title' => 'Cerita sivitas akademika Asatidz.',
    'items' => [
     ['quote' => 'Pembelajaran Asatidz membuka cara baru mengembangkan riset keasatidzahan, teks, dan pembelajaran secara mendalam.', 'name' => 'Dr. Ahmad Fikri', 'role' => 'Dosen'],
     ['quote' => 'Kegiatan diskusi dan praktik riset membantu mahasiswa lebih percaya diri mengembangkan kajian keasatidzahan.', 'name' => 'Ust. Zainal Abidin', 'role' => 'Pengajar'],
     ['quote' => 'Website prodi memudahkan akses informasi akademik, berita, dan layanan komunikasi.', 'name' => 'Laila Rahmah', 'role' => 'Mahasiswa'],
    ],
   ],
   'contact' => [
    'kicker' => 'Contact',
    'title' => 'Hubungi Asatidz.',
    'body' => 'Sampaikan kebutuhan informasi akademik, kegiatan mahasiswa, kerja sama, dan layanan komunikasi prodi.',
    'email' => 'asatidz@asatidz.id',
    'phone' => '+62 812 0000 0000',
    'address' => 'Kompleks , Pasuruan',
    'login_label' => 'Admin Login',
   ],
   'footer' => [
    'brand_title' => 'Asatidz',
    'brand_subtitle' => 'Prodi',
    'description' => 'Website Asatidz untuk informasi akademik, berita, kegiatan, dan layanan komunikasi prodi.',
    'primary_label' => 'Lihat News',
    'primary_href' => '/news',
    'secondary_label' => 'Kontak Prodi',
    'secondary_href' => '/contact',
    'navigation_title' => 'Navigasi',
    'navigation' => [
     ['label' => 'Home', 'href' => '/'],
     ['label' => 'Profil', 'href' => '/#about'],
     ['label' => 'Akademik', 'href' => '/#services'],
     ['label' => 'News', 'href' => '/news'],
     ['label' => 'Contact', 'href' => '/contact'],
    ],
    'services_title' => 'Layanan',
    'services' => [
     ['label' => 'Informasi Akademik', 'href' => '/#services'],
     ['label' => 'Kegiatan Mahasiswa', 'href' => '/#flow'],
     ['label' => 'Berita Prodi', 'href' => '/news'],
     ['label' => 'Kontak Prodi', 'href' => '/contact'],
    ],
    'contact_title' => 'Kontak',
    'email' => 'asatidz@asatidz.id',
    'phone' => '082229872328',
    'whatsapp' => '082229872328',
    'address' => 'Pasuruan, Jawa Timur',
    'maps_href' => 'https://maps.app.goo.gl/pNNU4dnCtuu7y4Qk6',
    'copyright' => 'Asatidz. All rights reserved.',
    'admin_label' => 'Admin Login',
    'admin_href' => '/login',
    'maps_label' => 'Maps',
   ],
  ];
 }
}
