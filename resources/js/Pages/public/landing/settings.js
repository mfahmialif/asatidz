import {
 publishingFlow,
 missions,
 services,
 stats,
 strengths,
 supporters,
 testimonials,
} from './data'

export const defaultLandingSettings = {
 hero: {
  image_path: '',
  image_url: '',
  image_alt: 'Kegiatan Program Studi Doktor Pendidikan Bahasa Arab',
  eyebrow: 'PROGRAM STUDI',
  main_title: 'Asatidz',
  description: 'Website resmi Program Studi Doktor Pendidikan Bahasa Arab untuk informasi akademik, berita, kegiatan mahasiswa, dan layanan komunikasi prodi.',
  primary_label: 'Lihat Akademik',
  primary_href: '#services',
  secondary_label: 'Kegiatan Prodi',
  secondary_href: '#flow',
  label: '',
  title: '',
 },
 visionMission: {
  kicker: 'Visi Misi',
  title: 'Arah pengembangan Program Studi Doktor Pendidikan Bahasa Arab.',
  vision_title: 'Visi',
  vision_body: 'Menjadi program studi yang unggul dalam pengembangan bahasa, sastra, budaya, dan tradisi keilmuan Arab berbasis nilai keislaman dan kebutuhan masyarakat.',
  mission_title: 'Misi',
  items: missions,
 },
 services: {
  kicker: 'Akademik',
  title: 'Informasi dan layanan Program Studi Doktor Pendidikan Bahasa Arab.',
  items: services,
 },
 supporters: {
  kicker: 'Supporter',
  title: 'Ekosistem yang mendukung gerakan literasi kampus.',
  items: supporters,
 },

 about: {
  kicker: 'Tentang Kami',
  title: 'Mengembangkan kajian pendidikan bahasa Arab, linguistik Arab, dan pembelajaran doktoral.',
  body: 'Program Studi Doktor Pendidikan Bahasa Arab berfokus pada penguasaan bahasa Arab, analisis sastra, pemahaman budaya, dan penguatan tradisi keilmuan. Website ini menjadi kanal informasi akademik, berita, kegiatan, dan layanan prodi.',
  stats,
 },
 publishCta: {
  kicker: 'Asatidz',
  title: 'Temukan informasi akademik dan kegiatan terbaru Program Studi Doktor Pendidikan Bahasa Arab.',
  button_label: 'Hubungi Prodi',
  button_href: '#contact',
 },
 strengths: {
  kicker: 'Kenapa Asatidz?',
  title: 'Ruang akademik doktoral untuk pendidikan bahasa Arab, riset pembelajaran, dan tradisi keilmuan.',
  items: strengths,
 },
 flow: {
  kicker: 'Kegiatan Prodi',
  title: 'Pengalaman belajar yang terarah dan aktif.',
  items: publishingFlow,
 },
 testimonials: {
  kicker: 'Testimoni',
  title: 'Cerita sivitas akademika Asatidz.',
  items: testimonials,
 },
 contact: {
  kicker: 'Contact',
  title: 'Hubungi Program Studi Doktor Pendidikan Bahasa Arab.',
  body: 'Sampaikan kebutuhan informasi akademik, kegiatan mahasiswa, kerja sama, dan layanan komunikasi prodi.',
  email: 'dpba@asatidz.id',
  phone: '+62 812 0000 0000',
  address: 'Kompleks , Pasuruan',
  login_label: 'Admin Login',
 },
 footer: {
  brand_title: 'Asatidz',
  brand_subtitle: 'Program Studi',
  description: 'Website Program Studi Doktor Pendidikan Bahasa Arab untuk informasi akademik, berita, kegiatan, dan layanan komunikasi prodi.',
  primary_label: 'Kontak Prodi',
  primary_href: '/contact',
  navigation_title: 'Navigasi',
  navigation: [
   { label: 'Home', href: '/' },
   { label: 'Profil', href: '/#about' },
   { label: 'Akademik', href: '/#services' },
   { label: 'Contact', href: '/contact' },
  ],
  services_title: 'Layanan',
  services: [
   { label: 'Informasi Akademik', href: '/#services' },
   { label: 'Kegiatan Mahasiswa', href: '/#flow' },
   { label: 'Kontak Prodi', href: '/contact' },
  ],
  contact_title: 'Kontak',
  email: 'dpba@asatidz.id',
  phone: '+62 812 0000 0000',
  whatsapp: '6281200000000',
  address: 'Pasuruan, Jawa Timur',
  maps_href: 'https://maps.app.goo.gl/pNNU4dnCtuu7y4Qk6',
  copyright: 'Asatidz. All rights reserved.',
  admin_label: 'Admin Login',
  admin_href: '/login',
  maps_label: 'Maps',
 },
}

function clone(value) {
 return JSON.parse(JSON.stringify(value))
}

function mergeSection(defaultSection, section) {
 if (Array.isArray(defaultSection)) {
  return Array.isArray(section) ? section : clone(defaultSection)
 }

 if (!defaultSection || typeof defaultSection !== 'object') {
  return section ?? defaultSection
 }

 const merged = clone(defaultSection)
 Object.entries(section || {}).forEach(([key, value]) => {
  if (Array.isArray(merged[key])) {
   merged[key] = Array.isArray(value) ? value : merged[key]
  } else if (merged[key] && typeof merged[key] === 'object') {
   merged[key] = mergeSection(merged[key], value)
  } else {
   merged[key] = value ?? merged[key]
  }
 })

 return merged
}

export function normalizeLandingSettings(settings = {}) {
 const normalized = mergeSection(defaultLandingSettings, settings)
 normalized.supporters.items = (normalized.supporters.items || []).map((item) => ({
  display_type: ['icon', 'logo', 'text'].includes(item.display_type) ? item.display_type : 'icon',
  icon: item.icon || 'school',
  logo_path: item.logo_path || '',
  logo_url: item.logo_url || '',
  name: item.name || '',
 }))
 return normalized
}
