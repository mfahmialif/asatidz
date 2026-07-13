<template>
 <div class="flex flex-col gap-6">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
   <div v-for="stat in stats" :key="stat.label" class="stat-card rounded-xl p-6 border border-accent/20">
    <div class="flex items-center justify-between">
     <p class="text-sm font-bold uppercase tracking-wider" style="color: var(--text-muted)">{{ stat.label }}</p>
     <span class="material-symbols-outlined text-[28px]" style="color: var(--text-muted)">{{ stat.icon }}</span>
    </div>
    <p class="mt-4 text-3xl font-black" style="color: var(--text-heading)">{{ stat.value }}</p>
   </div>
  </div>

  <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
   <section class="table-wrapper rounded-xl p-6">
    <div class="flex items-center justify-between gap-4">
     <div>
      <h2 class="text-xl font-bold" style="color: var(--text-heading)">Ringkasan Asatidz </h2>
      <p class="mt-1 text-sm" style="color: var(--text-muted)">Kelola konten website prodi dan pengaturan akun admin.</p>
     </div>
    </div>

    <div class="mt-6 grid gap-3 md:grid-cols-3">
     <div v-for="item in modules" :key="item.title" class="rounded-lg border p-4 transition-colors hover:border-blue-500/50" style="border-color: var(--border); background: var(--bg-input)">
      <span class="material-symbols-outlined" style="color: var(--text-muted)">{{ item.icon }}</span>
      <h3 class="mt-3 font-bold" style="color: var(--text-heading)">{{ item.title }}</h3>
      <p class="mt-2 text-sm leading-6" style="color: var(--text-muted)">{{ item.body }}</p>
     </div>
    </div>
   </section>

   <aside class="network-card rounded-xl p-6">
    <h2 class="text-xl font-bold" style="color: var(--text-heading)">Modul Aktif</h2>
    <div class="mt-5 flex flex-col gap-3">
     <div v-for="item in activeModules" :key="item" class="flex items-center gap-3 rounded-lg p-3" style="background: var(--bg-input)">
      <span class="material-symbols-outlined text-green-400 text-[20px]">check_circle</span>
      <span class="text-sm font-semibold" style="color: var(--text-body)">{{ item }}</span>
     </div>
    </div>
   </aside>
  </div>
 </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import api from '../../../axios'

defineOptions({ layout: AdminLayout })

const stats = ref([
 { label: 'Users', icon: 'manage_accounts', value: '0' },
 { label: 'Roles', icon: 'admin_panel_settings', value: '0' },
 { label: 'Menus', icon: 'menu', value: '0' },
])

const modules = [
 { icon: 'group', title: 'User & Role', body: 'Kelola akses admin dan operator aplikasi.' },
 { icon: 'menu', title: 'Menu', body: 'Atur navigasi publik website.' },
 { icon: 'settings', title: 'Pengaturan', body: 'Atur profil sistem dan preferensi aplikasi.' },
]

const activeModules = ['Manajemen User', 'Manajemen Role', 'Menu', 'Profile', 'Pengaturan']

onMounted(async () => {
 try {
  const [users, roles, menus] = await Promise.all([
   api.get('/users', { params: { per_page: 1 } }),
   api.get('/roles', { params: { per_page: 1 } }),
   api.get('/menus/primary/items')
  ])

  stats.value[0].value = String(users.data.total ?? 0)
  stats.value[1].value = String(roles.data.total ?? 0)
  stats.value[2].value = String(menus.data.length ?? 0)
 } catch {
  /* Keep zero values when API is unavailable. */
 }
})
</script>
