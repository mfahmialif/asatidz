<template>
 <nav class="horiz-nav flex items-center flex-wrap gap-1 px-4 lg:px-8 py-2 transition-colors duration-500">
  <template v-for="group in navGroups" :key="group.label">
   <span v-if="group.label" class="group-label px-2 text-[11px] font-black uppercase tracking-wider">{{ group.label }}</span>
   <Link v-for="item in group.items" :key="item.label"
          :href="item.route"
          :class="['horiz-link flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all whitespace-nowrap',
           isActiveRoute(item.route) ? 'active' : '']">
    <span class="material-symbols-outlined text-[18px]">{{ item.icon }}</span>
    <span>{{ item.label }}</span>
   </Link>
  </template>
 </nav>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const navGroups = [
 {
  label: '',
  items: [
   { icon: 'dashboard', label: 'Dashboard', route: '/administrator/dashboard' },
  ],
 },

 {
  label: 'Manajemen',
  items: [
   { icon: 'group', label: 'User', route: '/administrator/manajemen-user' },
   { icon: 'admin_panel_settings', label: 'Role', route: '/administrator/manajemen-role' },
  ],
 },
 {
  label: 'Website',
  items: [
   { icon: 'menu', label: 'Menu', route: '/administrator/menus' },
   { icon: 'person', label: 'Profile', route: '/administrator/profile' },
   { icon: 'history', label: 'Log', route: '/administrator/logs' },
   { icon: 'settings', label: 'Pengaturan', route: '/administrator/pengaturan' },
  ],
 },
]

function isActiveRoute(r) {
 const path = page.url.split('?')[0];
 return path === r
  || (r === '/administrator/menus' && path.startsWith('/administrator/menus'))
  || (r === '/administrator/logs' && path.startsWith('/administrator/logs'))
}
</script>

<style scoped>
.horiz-nav {
 background: var(--bg-card);
 border-bottom: 1px solid var(--border);
}
.horiz-link { color: var(--text-muted); }
.group-label { color: var(--text-muted); opacity: 0.75; }
.horiz-link:hover { color: var(--text-heading); background: var(--hover-nav-bg); }
.horiz-link.active {
 color: var(--text-btn);
 background: var(--color-accent);
 font-weight: 700;
 box-shadow: 0 0 12px rgba(37, 99, 235, 0.25);
}
</style>
