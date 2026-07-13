<template>
 <nav class="horiz-nav flex items-center gap-2 px-4 lg:px-8 py-2 transition-colors duration-500 overflow-visible z-[60]">
  <template v-for="group in filteredNavGroups" :key="group.label || 'main'">
   <!-- Single Items (no group label) -->
   <template v-if="!group.label">
    <Link v-for="item in group.items" :key="item.label" :href="item.route"
           :class="['horiz-link flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all whitespace-nowrap', isActiveRoute(item.route) ? 'active' : '']">
     <span class="material-symbols-outlined text-[18px]">{{ item.icon }}</span>
     <span>{{ item.label }}</span>
    </Link>
   </template>
   
   <!-- Dropdown Group (with label) -->
   <div v-else class="relative group/nav">
    <button :class="['horiz-link flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all whitespace-nowrap cursor-pointer', isGroupActive(group) ? 'group-active' : '']">
     <span>{{ group.label }}</span>
     <span class="material-symbols-outlined text-[16px] transition-transform duration-200 group-hover/nav:rotate-180">expand_more</span>
    </button>
    
    <!-- Dropdown menu -->
    <div class="absolute left-0 top-full mt-1 w-48 rounded-xl shadow-lg border dropdown-box opacity-0 invisible translate-y-2 group-hover/nav:opacity-100 group-hover/nav:visible group-hover/nav:translate-y-0 transition-all duration-200 z-[70]">
     <div class="py-1.5">
      <Link v-for="item in group.items" :key="item.label" :href="item.route"
             :class="['dropdown-item flex items-center gap-2 px-4 py-2 text-sm transition-colors', isActiveRoute(item.route) ? 'active' : '']">
       <span class="material-symbols-outlined text-[18px]">{{ item.icon }}</span>
       <span>{{ item.label }}</span>
      </Link>
     </div>
    </div>
   </div>
  </template>
 </nav>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useAuthStore } from '../../../stores/auth'

const page = usePage()
const authStore = useAuthStore()

const navSections = [
 {
  label: '',
  items: [
   { icon: 'dashboard', label: 'Dashboard', route: '/administrator/dashboard' },
  ],
 },
 {
  label: 'Master',
  items: [
   { icon: 'group', label: 'User', route: '/administrator/manajemen-user' },
   { icon: 'admin_panel_settings', label: 'Role', route: '/administrator/manajemen-role' },
   { icon: 'list_alt', label: 'Kegiatan', route: '/administrator/master-kegiatan' },
  ],
 },
 {
  label: 'Asatidz',
  items: [
   { icon: 'person_search', label: 'Data Asatidz', route: '/administrator/asatidz' },
   { icon: 'edit_document', label: 'Kegiatan Asatidz', route: '/administrator/kegiatan-asatidz' },
   { icon: 'summarize', label: 'Laporan', route: '/administrator/laporan' },
  ],
 },
 {
  label: 'Website',
  items: [
   { icon: 'person', label: 'Profile', route: '/administrator/profile' },
   { icon: 'history', label: 'Log', route: '/administrator/logs' },
   { icon: 'settings', label: 'Pengaturan', route: '/administrator/pengaturan' },
  ],
 },
]

const filteredNavGroups = computed(() => {
 const role = authStore.user?.role?.name
 
 if (role === 'Asatidz') {
  return [
   {
    label: '',
    items: [
     { icon: 'dashboard', label: 'Dashboard', route: '/administrator/dashboard' },
    ],
   },
   {
    label: 'Kegiatanku',
    items: [
     { icon: 'edit_document', label: 'Input Kegiatan', route: '/administrator/kegiatan-asatidz' },
    ],
   },
   {
    label: 'Akun',
    items: [
     { icon: 'person', label: 'Profile', route: '/administrator/profile' },
    ],
   }
  ]
 }
 
 return navSections
})

function isActiveRoute(r) {
 const path = page.url.split('?')[0];
 return path === r
  || (r === '/administrator/menus' && path.startsWith('/administrator/menus'))
  || (r === '/administrator/logs' && path.startsWith('/administrator/logs'))
}

function isGroupActive(group) {
 return group.items.some(item => isActiveRoute(item.route))
}
</script>

<style scoped>
.horiz-nav {
 background: var(--bg-card);
 border-bottom: 1px solid var(--border);
}
.horiz-link { color: var(--text-muted); }
.horiz-link:hover, .horiz-link.group-active { color: var(--text-heading); background: var(--hover-nav-bg); }
.horiz-link.active {
 color: var(--text-btn);
 background: var(--color-accent);
 font-weight: 700;
 box-shadow: 0 0 12px rgba(37, 99, 235, 0.25);
}

.dropdown-box {
 background: var(--bg-card);
 border-color: var(--border);
}
.dropdown-item {
 color: var(--text-body);
}
.dropdown-item:hover {
 background: var(--hover-nav-bg);
 color: var(--color-accent);
}
.dropdown-item.active {
 color: var(--color-accent);
 font-weight: bold;
 background: rgba(37, 99, 235, 0.08);
 border-left: 3px solid var(--color-accent);
}
</style>
