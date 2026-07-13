<template>
 <div class="flex flex-col gap-6">

  <!-- ═══ ACTION BAR ═══ -->
  <div class="flex items-center justify-between">
   <button @click="openCreateModal" class="flex items-center gap-2 rounded-lg h-10 px-5 bg-accent text-btn-text font-bold transition-colors hover:bg-accent/90 shadow-[0_0_15px_rgba(37, 99, 235,0.3)] shrink-0 cursor-pointer active:scale-95"
       style="color: var(--text-btn)">
    <span class="material-symbols-outlined text-[20px]">person_add</span>
    <span>Tambah Asatidz</span>
   </button>
  </div>

  <!-- ═══ FILTERS BAR ═══ -->
  <div class="flex flex-col lg:flex-row lg:flex-wrap lg:justify-between lg:items-center gap-4">
   <!-- Search -->
   <div class="relative w-full lg:w-[400px]">
    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-accent text-[20px] z-10">search</span>
    <input v-model="searchQuery"
        class="filter-input w-full rounded-xl py-2.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent"
        placeholder="Cari asatidz (nama/username)..." type="text" />
   </div>
   <!-- Filter Controls -->
   <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-3 lg:gap-4">
    <!-- Entries per page -->
    <div class="flex items-center gap-2 shrink-0">
     <span class="text-sm font-medium shrink-0" style="color: var(--text-body)">Show:</span>
     <VueMultiselect
      v-model="perPage"
      :options="perPageOptions"
      :close-on-select="true"
      :clear-on-select="false"
      :searchable="true"
      :allow-empty="false"
      :show-labels="false"
      label="name"
      track-by="value"
      placeholder="10"
      class="w-[90px]"
     />
     <span class="text-sm font-medium shrink-0" style="color: var(--text-body)">entries</span>
    </div>
   </div>
  </div>

  <!-- ═══ TABLE ═══ -->
  <div class="flex flex-col gap-4">
   <div class="table-wrapper rounded-xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto p-2">
     <table class="w-full text-left border-collapse">
      <thead>
       <tr class="table-head">
        <th class="px-6 py-4 text-sm font-semibold tracking-wide" style="color: var(--text-heading)">Nama</th>
        <th class="px-6 py-4 text-sm font-semibold tracking-wide" style="color: var(--text-heading)">Username</th>
        <th class="px-6 py-4 text-sm font-semibold tracking-wide" style="color: var(--text-heading)">No. Telepon</th>
        <th class="px-6 py-4 text-sm font-semibold tracking-wide" style="color: var(--text-heading)">Status</th>
        <th class="px-6 py-4 text-sm font-semibold tracking-wide text-right" style="color: var(--text-heading)">Aksi</th>
       </tr>
      </thead>
      <tbody class="table-body">
       <tr v-for="item in store.asatidz" :key="item.id" class="table-row-hover">
        <td class="px-6 py-5">
         <Link :href="`/administrator/asatidz/${item.id}`" class="text-sm font-bold text-accent hover:underline decoration-accent underline-offset-4">{{ item.nama }}</Link>
        </td>
        <td class="px-6 py-5">
         <span class="admin-badge admin-badge--neutral admin-badge--username">
          {{ item.user?.username || '-' }}
         </span>
        </td>
        <td class="px-6 py-5">
         <span class="text-sm" style="color: var(--text-muted)">{{ item.no_telepon || '-' }}</span>
        </td>
        <td class="px-6 py-5">
         <span :class="statusBadge(item.user?.status)">{{ item.user?.status || '-' }}</span>
        </td>
        <td class="px-6 py-5 text-right">
         <div class="flex items-center justify-end gap-1">
          <Link :href="`/administrator/asatidz/${item.id}`" class="action-btn p-2 rounded-lg transition-all duration-200" title="Detail Profil">
           <span class="material-symbols-outlined text-[20px]">visibility</span>
          </Link>
          <button @click="openEditModal(item)" class="action-btn p-2 rounded-lg transition-all duration-200" title="Edit">
           <span class="material-symbols-outlined text-[20px]">edit</span>
          </button>
          <button @click="confirmDelete(item)" class="action-btn action-btn-delete p-2 rounded-lg transition-all duration-200" title="Delete">
           <span class="material-symbols-outlined text-[20px]">delete</span>
          </button>
         </div>
        </td>
       </tr>
      </tbody>
     </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-bar flex items-center justify-between px-6 py-4">
     <span class="text-sm font-medium" style="color: var(--text-muted)">Menampilkan {{ store.pagination.from || 0 }} ke {{ store.pagination.to || 0 }} dari {{ store.pagination.total || 0 }} asatidz</span>
     <div class="flex items-center gap-1.5 ml-auto">
      <button @click="goToPage(currentPage - 1)" class="page-btn p-2 rounded-lg flex items-center justify-center disabled:opacity-50 cursor-pointer" :disabled="currentPage <= 1">
       <span class="material-symbols-outlined text-[20px]">chevron_left</span>
      </button>
      <template v-for="p in pageNumbers" :key="p">
       <span v-if="p === '...'" class="w-8 h-8 flex items-center justify-center text-sm" style="color: var(--text-muted)">...</span>
       <button v-else @click="goToPage(p)" :class="p === currentPage ? 'page-btn-active w-8 h-8 rounded-full font-bold text-sm flex items-center justify-center' : 'page-btn w-8 h-8 rounded-full text-sm font-medium flex items-center justify-center cursor-pointer'">{{ p }}</button>
      </template>
      <button @click="goToPage(currentPage + 1)" class="page-btn p-2 rounded-lg flex items-center justify-center cursor-pointer" :disabled="currentPage >= store.pagination.lastPage">
       <span class="material-symbols-outlined text-[20px]">chevron_right</span>
      </button>
     </div>
    </div>
   </div>
  </div>

  <!-- ═══ MODAL ═══ -->
  <Transition name="fade">
   <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="showModal = false">
    <div class="modal-card w-full max-w-lg rounded-2xl p-6 flex flex-col gap-5">
     <h3 class="text-lg font-bold" style="color: var(--text-heading)">{{ editingData ? 'Edit Asatidz' : 'Tambah Asatidz' }}</h3>
     <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Username</label>
       <input v-model="form.username" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" placeholder="Username login" />
      </div>
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Nama Lengkap</label>
       <input v-model="form.nama" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" placeholder="Nama Asatidz" />
      </div>
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Password {{ editingData ? '(kosongkan jika tidak diubah)' : '' }}</label>
       <input v-model="form.password" type="password" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" placeholder="••••••" />
      </div>
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">No Telepon</label>
       <input v-model="form.no_telepon" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" placeholder="08..." />
      </div>
      <div class="flex flex-col gap-1.5 sm:col-span-2">
       <label class="text-sm font-medium" style="color: var(--text-body)">Alamat</label>
       <textarea v-model="form.alamat" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" placeholder="Alamat lengkap"></textarea>
      </div>
      <div class="flex flex-col gap-1.5 sm:col-span-2">
       <label class="text-sm font-medium" style="color: var(--text-body)">Status Login</label>
       <VueMultiselect
        v-model="formStatusOption"
        :options="modalStatusOptions"
        :close-on-select="true"
        :searchable="false"
        :allow-empty="false"
        :show-labels="false"
        label="name"
        track-by="value"
        placeholder="Pilih Status"
       />
      </div>
     </div>
     <div v-if="formError" class="text-sm text-red-400 bg-red-500/10 border border-red-500/30 rounded-lg px-3 py-2">{{ formError }}</div>
     <div class="flex justify-end gap-3">
      <button @click="showModal = false" class="px-5 py-2 rounded-lg text-sm font-medium cursor-pointer" style="color: var(--text-body); border: 1px solid var(--border)">Batal</button>
      <button @click="saveData" :disabled="formLoading" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-accent text-sm font-bold cursor-pointer active:scale-95 disabled:opacity-50" style="color: var(--text-btn)">
       <span v-if="formLoading" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
       {{ editingData ? 'Simpan' : 'Tambah' }}
      </button>
     </div>
    </div>
   </div>
  </Transition>

 </div>
</template>

<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AdminLayout })

import { ref, computed, onMounted, watch } from 'vue'
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { useAsatidzStore } from '../../../stores/asatidz'

const store = useAsatidzStore()
const searchQuery = ref('')

const perPageOptions = [
 { name: '10', value: 10 },
 { name: '25', value: 25 },
 { name: '50', value: 50 },
 { name: '100', value: 100 }
]

const perPage = ref(perPageOptions[0])
const currentPage = ref(1)

// ── Modal state ──
const showModal = ref(false)
const editingData = ref(null)
const form = ref({ username: '', nama: '', password: '', no_telepon: '', alamat: '', status: 'Active' })
const formError = ref('')
const formLoading = ref(false)

const modalStatusOptions = [
 { name: 'Active', value: 'Active' },
 { name: 'Inactive', value: 'Inactive' }
]
const formStatusOption = computed({
 get: () => modalStatusOptions.find(o => o.value === form.value.status) || modalStatusOptions[0],
 set: (val) => { form.value.status = val.value }
})

// ── Fetch ──
function loadData() {
 store.fetchAsatidz({
  search: searchQuery.value || undefined,
  per_page: perPage.value.value,
  page: currentPage.value,
 })
}

onMounted(() => {
 loadData()
})

// Watchers
let searchTimeout
watch(searchQuery, () => {
 clearTimeout(searchTimeout)
 searchTimeout = setTimeout(() => { currentPage.value = 1; loadData() }, 400)
})
watch([perPage], () => { currentPage.value = 1; loadData() })

// ── Pagination ──
function goToPage(page) {
 if (page >= 1 && page <= store.pagination.lastPage) {
  currentPage.value = page
  loadData()
 }
}

const pageNumbers = computed(() => {
 const last = store.pagination.lastPage || 1
 const curr = currentPage.value
 const pages = []
 for (let i = 1; i <= last; i++) {
  if (i === 1 || i === last || (i >= curr - 1 && i <= curr + 1)) {
   pages.push(i)
  } else if (pages[pages.length - 1] !== '...') {
   pages.push('...')
  }
 }
 return pages
})

// ── CRUD ──
function openCreateModal() {
 editingData.value = null
 form.value = { username: '', nama: '', password: '', no_telepon: '', alamat: '', status: 'Active' }
 formError.value = ''
 showModal.value = true
}

function openEditModal(item) {
 editingData.value = item
 form.value = {
  username: item.user?.username || '',
  nama: item.nama,
  password: '',
  no_telepon: item.no_telepon || '',
  alamat: item.alamat || '',
  status: item.user?.status || 'Active',
 }
 formError.value = ''
 showModal.value = true
}

async function saveData() {
 formError.value = ''
 formLoading.value = true
 try {
  const payload = { ...form.value }
  if (editingData.value && !payload.password) {
   delete payload.password
  }

  if (editingData.value) {
   await store.updateAsatidz(editingData.value.id, payload)
  } else {
   await store.createAsatidz(payload)
  }
  showModal.value = false
  loadData()
 } catch (e) {
  const errors = e.response?.data?.errors
  if (errors) {
   formError.value = Object.values(errors).flat().join(' ')
  } else {
   formError.value = e.response?.data?.message || 'Terjadi kesalahan.'
  }
 } finally {
  formLoading.value = false
 }
}

async function confirmDelete(item) {
 if (!confirm(`Hapus Asatidz "${item.nama}" beserta akun loginnya?`)) return
 try {
  await store.deleteAsatidz(item.id)
  loadData()
 } catch (e) {
  alert(e.response?.data?.message || 'Gagal menghapus Asatidz.')
 }
}

// ── Helpers ──
function statusBadge(status) {
 const base = 'admin-badge admin-badge--status'
 if (status === 'Active') return `${base} admin-badge--success`
 return `${base} admin-badge--muted`
}
</script>

<style scoped>
/* ═══ Filter Inputs ═══ */
.filter-input {
 background: var(--bg-card);
 border: 1px solid var(--border);
 color: var(--text-heading);
 transition: box-shadow 0.3s ease;
}
.filter-input::placeholder { color: var(--text-muted); }
.filter-input:hover { box-shadow: 0 0 15px rgba(37, 99, 235, 0.15); }
.filter-input:focus { border-color: var(--color-accent); box-shadow: 0 0 12px rgba(37, 99, 235, 0.3); }

/* ═══ Badges ═══ */
.admin-badge {
 --badge-color: #94a3b8;
 --badge-bg: rgba(148, 163, 184, 0.12);
 --badge-border: rgba(148, 163, 184, 0.28);
 display: inline-flex;
 align-items: center;
 justify-content: center;
 min-height: 1.75rem;
 border-radius: 999px;
 padding: 0.25rem 0.7rem;
 border: 1px solid var(--badge-border);
 background: var(--badge-bg);
 color: var(--badge-color);
 font-size: 0.75rem;
 font-weight: 700;
 line-height: 1;
 white-space: nowrap;
}
.admin-badge--username {
 min-height: 2rem;
 border-radius: 0.625rem;
 padding-inline: 0.75rem;
 font-size: 0.875rem;
}
.admin-badge--success { --badge-color: #4ade80; --badge-bg: rgba(34, 197, 94, 0.12); --badge-border: rgba(34, 197, 94, 0.28); }
.admin-badge--muted,
.admin-badge--neutral { --badge-color: #94a3b8; --badge-bg: rgba(148, 163, 184, 0.12); --badge-border: rgba(148, 163, 184, 0.28); }

:global(.admin-root[data-theme="light"]) .admin-badge--success { --badge-color: #15803d; --badge-bg: #dcfce7; --badge-border: #86efac; }
:global(.admin-root[data-theme="light"]) .admin-badge--muted,
:global(.admin-root[data-theme="light"]) .admin-badge--neutral { --badge-color: #475569; --badge-bg: #f1f5f9; --badge-border: #cbd5e1; }

/* ═══ Action Buttons ═══ */
.action-btn { color: var(--text-muted); }
.action-btn:hover { color: var(--color-accent); background: var(--bg-input); }
.action-btn-delete:hover { color: #f87171; background: var(--bg-input); }

/* ═══ Pagination ═══ */
.pagination-bar { border-top: 1px solid var(--border); background: var(--bg-card); }
.page-btn { color: var(--text-muted); border: 1px solid transparent; transition: all 0.2s ease; }
.page-btn:hover { background: var(--bg-input); color: var(--text-heading); }
.page-btn-active { background: var(--color-accent); color: var(--text-btn); box-shadow: 0 0 10px rgba(37, 99, 235, 0.4); }

/* ═══ Modal ═══ */
.modal-card { background: var(--bg-card); border: 1px solid var(--border); box-shadow: 0 20px 60px rgba(0,0,0,0.5); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
