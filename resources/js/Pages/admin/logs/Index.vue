<template>
 <div class="flex flex-col gap-6">
  <div class="log-toolbar rounded-xl p-4">
   <div class="relative w-full max-w-md">
    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-accent text-[20px]">search</span>
    <input
     v-model="search"
     class="log-input w-full rounded-xl py-2.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent"
     placeholder="Cari judul atau deskripsi log..."
     type="text"
     @input="debouncedFetch"
    />
   </div>
   <select v-model="action" class="log-select rounded-xl px-4 py-2.5 text-sm font-bold" @change="fetchLogs">
    <option value="all">Semua Aksi</option>
    <option value="created">Tambah</option>
    <option value="updated">Edit</option>
    <option value="deleted">Hapus</option>
   </select>
  </div>

  <div class="table-wrapper rounded-xl overflow-hidden shadow-2xl">
   <div v-if="loading" class="flex justify-center py-12">
    <span class="material-symbols-outlined text-accent text-4xl animate-spin">progress_activity</span>
   </div>
   <div v-else class="overflow-x-auto p-2">
    <table class="w-full text-left border-collapse">
     <thead>
      <tr class="table-head">
       <th class="px-4 py-4 text-sm font-semibold" style="color: var(--text-heading)">Aksi</th>
       <th class="px-4 py-4 text-sm font-semibold" style="color: var(--text-heading)">Catatan</th>
       <th class="px-4 py-4 text-sm font-semibold" style="color: var(--text-heading)">User</th>
       <th class="px-4 py-4 text-sm font-semibold" style="color: var(--text-heading)">Waktu</th>
       <th class="px-4 py-4 text-sm font-semibold" style="color: var(--text-heading)">Status</th>
      </tr>
     </thead>
     <tbody class="table-body">
      <tr v-if="logs.length === 0">
       <td colspan="5" class="px-4 py-12 text-center text-sm" style="color: var(--text-muted)">Belum ada log aktivitas.</td>
      </tr>
      <tr v-for="log in logs" :key="log.id" class="table-row-hover">
       <td class="px-4 py-4">
        <span :class="actionBadge(log.action)">{{ actionLabel(log.action) }}</span>
       </td>
       <td class="px-4 py-4">
        <p class="text-sm font-bold" style="color: var(--text-heading)">{{ log.subject_title || '-' }}</p>
        <p class="mt-1 text-xs" style="color: var(--text-muted)">{{ log.description }}</p>
       </td>
       <td class="px-4 py-4 text-sm" style="color: var(--text-muted)">{{ log.user?.name || '-' }}</td>
       <td class="px-4 py-4 text-sm" style="color: var(--text-muted)">{{ formatDate(log.created_at) }}</td>
       <td class="px-4 py-4">
        <span :class="log.read_by_current_user ? 'app-badge app-badge--neutral' : 'app-badge app-badge--success'">
         {{ log.read_by_current_user ? 'Dibaca' : 'Baru' }}
        </span>
       </td>
      </tr>
     </tbody>
    </table>
   </div>
   <div class="pagination-bar flex items-center justify-between px-6 py-4">
    <span class="text-sm font-medium" style="color: var(--text-muted)">Showing {{ logs.length }} of {{ totalItems }} items</span>
    <div class="flex items-center gap-1.5 ml-auto">
     <button class="page-btn p-2 rounded-lg cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" :disabled="currentPage <= 1" @click="goPage(currentPage - 1)">
      <span class="material-symbols-outlined text-[20px]">chevron_left</span>
     </button>
     <button class="page-btn-active w-8 h-8 rounded-full font-bold text-sm flex items-center justify-center">{{ currentPage }}</button>
     <button class="page-btn p-2 rounded-lg cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" :disabled="currentPage >= totalPages" @click="goPage(currentPage + 1)">
      <span class="material-symbols-outlined text-[20px]">chevron_right</span>
     </button>
    </div>
   </div>
  </div>
 </div>
</template>

<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

import { onMounted, ref } from 'vue'
import api from '../../../axios'

const loading = ref(false)
const logs = ref([])
const search = ref('')
const action = ref('all')
const currentPage = ref(1)
const totalPages = ref(1)
const totalItems = ref(0)
let debounceTimer = null

function debouncedFetch() {
 clearTimeout(debounceTimer)
 debounceTimer = setTimeout(() => {
  currentPage.value = 1
  fetchLogs()
 }, 300)
}

async function fetchLogs() {
 loading.value = true
 try {
  const { data } = await api.get('/activity-logs', {
   params: {
    page: currentPage.value,
    per_page: 15,
    search: search.value || undefined,
    action: action.value,
   },
  })
  logs.value = data.data || []
  currentPage.value = data.current_page || 1
  totalPages.value = data.last_page || 1
  totalItems.value = data.total || logs.value.length
  await api.post('/activity-logs/mark-read')
  logs.value = logs.value.map((log) => ({ ...log, read_by_current_user: true }))
  window.dispatchEvent(new CustomEvent('activity-logs:refresh'))
 } finally {
  loading.value = false
 }
}

function goPage(page) {
 if (page < 1 || page > totalPages.value) return
 currentPage.value = page
 fetchLogs()
}

function actionLabel(value) {
 if (value === 'created') return 'Tambah'
 if (value === 'updated') return 'Edit'
 if (value === 'deleted') return 'Hapus'
 return value
}

function actionBadge(value) {
 if (value === 'created') return 'app-badge app-badge--success'
 if (value === 'updated') return 'app-badge app-badge--blue'
 if (value === 'deleted') return 'app-badge app-badge--red'
 return 'app-badge app-badge--neutral'
}

function formatDate(value) {
 if (!value) return '-'
 return new Intl.DateTimeFormat('id-ID', {
  dateStyle: 'medium',
  timeStyle: 'short',
 }).format(new Date(value))
}

onMounted(fetchLogs)
</script>

<style scoped>
.log-toolbar {
 display: flex;
 flex-wrap: wrap;
 align-items: center;
 justify-content: space-between;
 gap: 1rem;
 background: var(--bg-card);
 border: 1px solid var(--border);
}
.log-input,
.log-select {
 background: var(--bg-input);
 border: 1px solid var(--border);
 color: var(--text-heading);
}
.log-input::placeholder {
 color: var(--text-muted);
}
.log-input:focus,
.log-select:focus {
 border-color: var(--color-accent);
 box-shadow: 0 0 12px rgba(37, 99, 235, 0.3);
}
.pagination-bar {
 border-top: 1px solid var(--border);
 background: var(--bg-card);
}
.page-btn {
 color: var(--text-muted);
 border: 1px solid transparent;
}
.page-btn:hover {
 background: var(--bg-input);
 color: var(--text-heading);
}
.page-btn-active {
 background: var(--color-accent);
 color: var(--text-btn);
}
</style>
