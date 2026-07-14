<template>
 <div class="flex flex-col gap-6">

  <!-- ═══ ACTION BAR ═══ -->
  <div class="flex items-center justify-between">
   <button @click="openCreateModal" class="flex items-center gap-2 rounded-lg h-10 px-5 bg-accent text-btn-text font-bold transition-colors hover:bg-accent/90 shadow-[0_0_15px_rgba(37, 99, 235,0.3)] shrink-0 cursor-pointer active:scale-95"
       style="color: var(--text-btn)">
    <span class="material-symbols-outlined text-[20px]">add_box</span>
    <span>Input Kegiatan</span>
   </button>
  </div>

  <!-- ═══ FILTERS BAR ═══ -->
  <div class="flex flex-col lg:flex-row lg:flex-wrap lg:justify-between lg:items-center gap-4">
   <!-- Date Filter -->
   <div class="relative w-full lg:w-[250px]">
    <input v-model="filterDate"
        class="filter-input w-full rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent"
        type="date" />
   </div>
   <!-- Filter Controls -->
   <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-3 lg:gap-4">
    <button v-if="filterDate" @click="clearFilter" class="text-sm font-medium hover:text-accent transition-colors cursor-pointer" style="color: var(--text-muted)">
     Clear Filter
    </button>
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

  <!-- ═══ TABLE & DATA VIEW ═══ -->
  <div class="flex flex-col gap-4">
   <div class="table-wrapper rounded-xl overflow-hidden shadow-2xl">
    
    <!-- DESKTOP TABLE (Hidden on mobile/tablet) -->
    <div class="hidden lg:block overflow-x-auto p-2">
     <table class="w-full text-left border-collapse">
      <thead>
       <tr class="table-head">
        <th class="px-6 py-4 text-sm font-semibold tracking-wide" style="color: var(--text-heading)">Tanggal</th>
        <th class="px-6 py-4 text-sm font-semibold tracking-wide" style="color: var(--text-heading)">Daftar Kegiatan</th>
        <th class="px-6 py-4 text-sm font-semibold tracking-wide" style="color: var(--text-heading)">Total Poin</th>
        <th class="px-6 py-4 text-sm font-semibold tracking-wide text-right" style="color: var(--text-heading)">Aksi</th>
       </tr>
      </thead>
      <tbody class="table-body">
       <tr v-if="store.loading" class="table-row-hover">
        <td colspan="5" class="px-6 py-5 text-center text-sm" style="color: var(--text-muted)">Memuat data...</td>
       </tr>
       <tr v-else-if="!store.kegiatans.length" class="table-row-hover">
        <td colspan="5" class="px-6 py-5 text-center text-sm" style="color: var(--text-muted)">Belum ada kegiatan.</td>
       </tr>
       <tr v-for="item in store.kegiatans" :key="item.id" class="table-row-hover">
        <td class="px-6 py-5">
         <span class="text-sm font-medium" style="color: var(--text-heading)">{{ formatDate(item.tanggal_kegiatan) }}</span>
        </td>
        <td class="px-6 py-5">
         <div class="flex flex-col gap-1">
          <div v-for="detail in item.details" :key="detail.id" class="text-sm" style="color: var(--text-muted)">
           • {{ detail.master_kegiatan?.nama }} 
           <span v-if="detail.keterangan" class="italic opacity-70">({{ detail.keterangan }})</span>
          </div>
         </div>
        </td>
        <td class="px-6 py-5">
         <span class="admin-badge admin-badge--sky">{{ item.total_poin }}</span>
        </td>
        <td class="px-6 py-5 text-right">
         <div class="flex items-center justify-end gap-1">
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

    <!-- MOBILE & TAB LIST VIEW (Hidden on desktop) -->
    <div class="block lg:hidden p-4 bg-[var(--bg-table-body)]">
     <div v-if="store.loading" class="py-10 text-center text-sm font-medium" style="color: var(--text-muted)">Memuat data...</div>
     <div v-else-if="!store.kegiatans.length" class="py-10 text-center text-sm font-medium" style="color: var(--text-muted)">Belum ada kegiatan.</div>
     
     <div class="flex flex-col gap-4" v-else>
      <div v-for="item in store.kegiatans" :key="item.id" class="p-4 rounded-xl border border-[var(--border)] bg-[var(--bg-input)] shadow-sm">
       <!-- Card Header -->
       <div class="flex items-start justify-between border-b border-[var(--border)] pb-3 mb-3">
        <div class="flex flex-col">
         <span class="text-[0.95rem] font-bold" style="color: var(--text-heading)">{{ formatDate(item.tanggal_kegiatan) }}</span>
         <span class="text-xs font-bold mt-1" style="color: var(--text-muted)">Total Poin: <span class="text-[var(--color-accent)]">{{ item.total_poin }}</span></span>
        </div>
        <div class="flex items-center gap-1 shrink-0 bg-[var(--bg-card)] rounded-lg p-0.5 border border-[var(--border-light)] shadow-sm">
         <button @click="openEditModal(item)" class="action-btn p-1.5 rounded-md transition-all duration-200" title="Edit">
          <span class="material-symbols-outlined text-[18px]">edit</span>
         </button>
         <button @click="confirmDelete(item)" class="action-btn action-btn-delete p-1.5 rounded-md transition-all duration-200" title="Delete">
          <span class="material-symbols-outlined text-[18px]">delete</span>
         </button>
        </div>
       </div>
       
       <!-- Card Body (Details) -->
       <div class="flex flex-col gap-3">
        <div v-for="detail in item.details" :key="detail.id" class="flex gap-2">
         <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5 text-[var(--color-accent)]">check_circle</span>
         <div class="flex flex-col">
          <span class="text-sm font-bold" style="color: var(--text-body)">{{ detail.master_kegiatan?.nama }}</span>
          <span v-if="detail.keterangan" class="text-[0.8rem] italic opacity-80 mt-0.5" style="color: var(--text-muted)">{{ detail.keterangan }}</span>
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-bar flex flex-col sm:flex-row items-center justify-between px-4 sm:px-6 py-4 gap-4 sm:gap-0">
     <span class="text-xs sm:text-sm font-medium text-center sm:text-left" style="color: var(--text-muted)">Menampilkan {{ store.pagination.from || 0 }} ke {{ store.pagination.to || 0 }} dari {{ store.pagination.total || 0 }} data</span>
     <div class="flex items-center gap-1.5 w-full sm:w-auto justify-center sm:justify-end">
      <button @click="goToPage(currentPage - 1)" class="page-btn p-1.5 rounded-lg flex items-center justify-center disabled:opacity-50 cursor-pointer" :disabled="currentPage <= 1">
       <span class="material-symbols-outlined text-[20px]">chevron_left</span>
      </button>
      <template v-for="p in pageNumbers" :key="p">
       <span v-if="p === '...'" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center text-sm" style="color: var(--text-muted)">...</span>
       <button v-else @click="goToPage(p)" :class="p === currentPage ? 'page-btn-active w-7 h-7 sm:w-8 sm:h-8 rounded-full font-bold text-sm flex items-center justify-center' : 'page-btn w-7 h-7 sm:w-8 sm:h-8 rounded-full text-sm font-medium flex items-center justify-center cursor-pointer'">{{ p }}</button>
      </template>
      <button @click="goToPage(currentPage + 1)" class="page-btn p-1.5 rounded-lg flex items-center justify-center cursor-pointer" :disabled="currentPage >= store.pagination.lastPage">
       <span class="material-symbols-outlined text-[20px]">chevron_right</span>
      </button>
     </div>
    </div>
   </div>
  </div>

  <!-- ═══ MODAL ═══ -->
  <Teleport to=".admin-root" v-if="isMounted">
   <Transition name="modal-overlay">
    <div v-if="showModal" class="modal-overlay fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-end lg:items-center justify-center p-0 lg:p-4" @click.self="showModal = false">
     <div class="modal-card w-full max-w-3xl max-h-[95vh] lg:max-h-[90vh] flex flex-col rounded-t-3xl rounded-b-none lg:rounded-2xl relative shadow-[0_-10px_40px_rgba(0,0,0,0.3)] lg:shadow-2xl"
          :style="{ transform: isDragging ? `translateY(${currentY}px)` : '', transition: isDragging ? 'none' : '' }"
          @touchstart="onTouchStart"
          @touchmove="onTouchMove"
          @touchend="onTouchEnd">
      
      <!-- Mobile Drag Handle indicator -->
      <div class="w-full flex justify-center py-3 lg:hidden absolute top-0 left-0 right-0 pointer-events-none">
       <div class="w-12 h-1.5 rounded-full bg-[var(--border)] opacity-50"></div>
      </div>
      
      <div class="p-5 pt-8 lg:pt-6 lg:p-6 shrink-0 border-b border-[var(--border)] flex justify-between items-center">
       <h3 class="text-lg font-bold" style="color: var(--text-heading)">{{ editingData ? 'Edit Kegiatan Harian' : 'Input Kegiatan Harian' }}</h3>
       <button @click="showModal = false" class="lg:hidden p-2 rounded-full hover:bg-[var(--bg-input)] bg-[var(--bg-input)] border border-[var(--border)]">
        <span class="material-symbols-outlined text-[18px]">close</span>
       </button>
      </div>

      <div ref="scrollContainer" class="p-5 lg:p-6 overflow-y-auto flex-1 flex flex-col gap-6">
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Tanggal Kegiatan</label>
       <input v-model="form.tanggal_kegiatan" type="date" class="filter-input w-full lg:w-1/2 rounded-xl py-3 lg:py-2.5 px-4 text-[0.95rem] lg:text-sm focus:outline-none focus:ring-1 focus:ring-accent" />
      </div>

      <div class="flex flex-col gap-4 lg:gap-3">
       <div class="flex items-center justify-between">
        <label class="text-sm font-medium" style="color: var(--text-body)">Daftar Aktivitas</label>
       </div>
       
       <div class="flex flex-col gap-4">
        <div v-for="(detail, index) in form.details" :key="index" class="p-5 lg:p-4 rounded-2xl lg:rounded-xl border border-[var(--border)] bg-[var(--bg-card)] shadow-sm flex flex-col gap-4 relative">
         <button v-if="form.details.length > 1" @click="removeDetailRow(index)" type="button" class="absolute top-2 right-2 text-red-500 hover:bg-red-500/10 p-2 rounded-lg cursor-pointer transition-colors bg-red-500/5 lg:bg-transparent" title="Hapus baris">
          <span class="material-symbols-outlined text-[20px] lg:text-[18px]">close</span>
         </button>
         
         <div class="flex flex-col lg:flex-row gap-5 lg:gap-4 mt-4 lg:mt-0">
          <div class="flex flex-col gap-2 flex-1">
           <label class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-muted)">Jenis Kegiatan</label>
           <VueMultiselect
            v-model="detail.masterKegiatanOption"
            :options="getAvailableOptions(index)"
            :close-on-select="true"
            :searchable="true"
            :allow-empty="false"
            :show-labels="false"
            open-direction="bottom"
            label="name_with_point"
            track-by="id"
            placeholder="Pilih Kegiatan"
            class="mobile-friendly-multiselect"
           />
          </div>
          <div class="flex flex-col gap-2 flex-1">
           <label class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-muted)">Keterangan (Opsional)</label>
           <input v-model="detail.keterangan" type="text" class="filter-input w-full rounded-xl py-3 lg:py-2.5 px-4 text-[0.95rem] lg:text-sm focus:outline-none focus:ring-1 focus:ring-accent" placeholder="Contoh: Mengajar kelas 1A" />
          </div>
         </div>
        </div>
       </div>

       <button @click="addDetailRow" type="button" class="mt-2 text-[0.95rem] lg:text-sm font-bold text-accent hover:bg-accent/10 border border-dashed border-accent/50 py-3 rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add_circle</span> Tambah Aktivitas Lain
       </button>
      </div>
      
      <div v-if="formError" class="text-sm font-medium text-red-400 bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3">{{ formError }}</div>
      <!-- Extra space for dropdown -->
      <div class="h-32 lg:h-20 shrink-0"></div>
     </div>

     <div class="p-5 lg:p-6 shrink-0 border-t border-[var(--border)] flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 bg-[var(--bg-input)] rounded-b-none lg:rounded-b-2xl">
      <div class="flex items-center justify-between w-full sm:w-auto shrink-0 bg-[var(--bg-card)] sm:bg-transparent p-3 sm:p-0 rounded-xl sm:rounded-none border sm:border-none border-[var(--border-light)]">
       <span class="text-sm font-medium" style="color: var(--text-muted)">Total Estimasi Poin:</span>
       <span class="font-black text-xl lg:text-lg text-[var(--color-accent)] sm:ml-2">{{ calculatedPoints }}</span>
      </div>
      <div class="flex gap-3 w-full sm:w-auto">
       <button @click="showModal = false" class="flex-1 sm:flex-none px-5 py-3 lg:py-2 rounded-xl text-[0.95rem] lg:text-sm font-bold cursor-pointer bg-[var(--bg-card)] hover:bg-[var(--bg-input)] transition-colors" style="color: var(--text-body); border: 1px solid var(--border)">Batal</button>
       <button @click="saveData" :disabled="formLoading" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-3 lg:py-2 rounded-xl bg-accent text-[0.95rem] lg:text-sm font-bold cursor-pointer active:scale-95 disabled:opacity-50 shadow-[0_5px_15px_var(--color-accent-soft)]" style="color: var(--text-btn)">
        <span v-if="formLoading" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
        {{ editingData ? 'Simpan Perubahan' : 'Submit Kegiatan' }}
       </button>
      </div>
     </div>
    </div>
   </div>
  </Transition>
  </Teleport>

 </div>
</template>

<script setup>
import AsatidzLayout from '../../../Layouts/AsatidzLayout.vue'

defineOptions({ layout: AsatidzLayout })

import { ref, computed, onMounted, watch, onUnmounted } from 'vue'
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { useKegiatanAsatidzStore } from '../../../stores/kegiatanAsatidz'
import { useAuthStore } from '../../../stores/auth'
import api from '../../../axios'

const store = useKegiatanAsatidzStore()
const authStore = useAuthStore()

const filterDate = ref('')

const perPageOptions = [
 { name: '10', value: 10 },
 { name: '25', value: 25 },
 { name: '50', value: 50 }
]

const perPage = ref(perPageOptions[0])
const currentPage = ref(1)

// ── Master Kegiatan ──
const masterKegiatans = ref([])
const masterKegiatanOptions = computed(() => {
 return masterKegiatans.value.map(k => ({
  id: k.id,
  nama: k.nama,
  poin: k.poin,
  name_with_point: `${k.nama} (${k.poin} Poin)`
 }))
})

function getAvailableOptions(currentIndex) {
 const selectedIds = form.value.details
  .map((d, idx) => (idx !== currentIndex && d.masterKegiatanOption) ? d.masterKegiatanOption.id : null)
  .filter(id => id !== null)
 
 return masterKegiatanOptions.value.filter(opt => !selectedIds.includes(opt.id))
}

async function fetchMasterKegiatans() {
 try {
  const { data } = await api.get('/master-kegiatan', { params: { per_page: 100 } })
  // Filter active if needed, currently loading all
  masterKegiatans.value = data.data.filter(k => k.status === 'Aktif')
 } catch (e) {
  console.error(e)
 }
}

// ── Modal state ──
const showModal = ref(false)
const editingData = ref(null)
const form = ref({ tanggal_kegiatan: '', details: [] })
const formError = ref('')
const formLoading = ref(false)

// ── Touch Drag to Close ──
const touchStartY = ref(0)
const currentY = ref(0)
const isDragging = ref(false)
const scrollContainer = ref(null)

function onTouchStart(e) {
 // Hanya jalankan drag jika pengguna menyentuh area diluar scroll atau jika scroll sedang di posisi paling atas
 if (scrollContainer.value && scrollContainer.value.contains(e.target)) {
  if (scrollContainer.value.scrollTop > 0) return
 }
 touchStartY.value = e.touches[0].clientY
 isDragging.value = true
}

function onTouchMove(e) {
 if (!isDragging.value) return
 const y = e.touches[0].clientY
 const deltaY = y - touchStartY.value
 
 if (deltaY > 0) {
  currentY.value = deltaY
  // Mencegah pull-to-refresh pada mobile Chrome
  if (e.cancelable) {
   e.preventDefault()
  }
 } else {
  currentY.value = 0
 }
}

function onTouchEnd() {
 if (!isDragging.value) return
 isDragging.value = false
 if (currentY.value > 120) {
  showModal.value = false
  setTimeout(() => { currentY.value = 0 }, 300)
 } else {
  currentY.value = 0
 }
}

const calculatedPoints = computed(() => {
 let total = 0
 form.value.details.forEach(d => {
  if (d.masterKegiatanOption) total += d.masterKegiatanOption.poin
 })
 return total
})

function addDetailRow() {
 form.value.details.push({ masterKegiatanOption: null, keterangan: '' })
}
function removeDetailRow(index) {
 form.value.details.splice(index, 1)
}

// ── Fetch ──
function loadData() {
 store.fetchKegiatans({
  tanggal: filterDate.value || undefined,
  per_page: perPage.value.value,
  page: currentPage.value,
 })
}

const isMounted = ref(false)
onMounted(() => {
 isMounted.value = true
 loadData()
 fetchMasterKegiatans()
 window.addEventListener('open-input-kegiatan', openCreateModal)
})

onUnmounted(() => {
 window.removeEventListener('open-input-kegiatan', openCreateModal)
})

// Watchers
watch([filterDate, perPage], () => { currentPage.value = 1; loadData() })

function clearFilter() {
 filterDate.value = ''
}

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
 const today = new Date().toISOString().split('T')[0]
 form.value = { tanggal_kegiatan: today, details: [{ masterKegiatanOption: null, keterangan: '' }] }
 formError.value = ''
 showModal.value = true
}

function openEditModal(item) {
 editingData.value = item
 form.value = {
  tanggal_kegiatan: item.tanggal_kegiatan.split('T')[0],
  details: item.details.map(d => ({
   masterKegiatanOption: masterKegiatanOptions.value.find(o => o.id === d.kegiatan_id) || null,
   keterangan: d.keterangan || ''
  }))
 }
 formError.value = ''
 showModal.value = true
}

async function saveData() {
 formError.value = ''
 
 // Validation
 if (!form.value.tanggal_kegiatan) {
  formError.value = 'Tanggal kegiatan harus diisi.'
  return
 }
 
 const invalidDetails = form.value.details.filter(d => !d.masterKegiatanOption)
 if (invalidDetails.length > 0) {
  formError.value = 'Semua baris aktivitas harus dipilih jenis kegiatannya.'
  return
 }
 
 formLoading.value = true
 try {
  const payload = {
   tanggal_kegiatan: form.value.tanggal_kegiatan,
   details: form.value.details.map(d => ({
    master_kegiatan_id: d.masterKegiatanOption.id,
    keterangan: d.keterangan
   }))
  }

  if (editingData.value) {
   await store.updateKegiatan(editingData.value.id, payload)
  } else {
   await store.createKegiatan(payload)
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
 if (!confirm(`Hapus data kegiatan tanggal ${formatDate(item.tanggal_kegiatan)}?`)) return
 try {
  await store.deleteKegiatan(item.id)
  loadData()
 } catch (e) {
  alert(e.response?.data?.message || 'Gagal menghapus kegiatan.')
 }
}

// ── Helpers ──
function formatDate(dateStr) {
 if (!dateStr) return '-'
 const date = new Date(dateStr)
 return date.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
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
.admin-badge--sky { --badge-color: #38bdf8; --badge-bg: rgba(14, 165, 233, 0.14); --badge-border: rgba(14, 165, 233, 0.3); }

:global(.admin-root[data-theme="light"]) .admin-badge--sky { --badge-color: #0369a1; --badge-bg: #e0f2fe; --badge-border: #7dd3fc; }

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
.modal-card { background: var(--bg-card); border: 1px solid var(--border); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s; overscroll-behavior-y: none; touch-action: pan-y; }

.modal-overlay { overscroll-behavior-y: none; touch-action: none; }

.modal-overlay-enter-active, .modal-overlay-leave-active { transition: opacity 0.3s ease; }
.modal-overlay-enter-from, .modal-overlay-leave-to { opacity: 0; }

@media (max-width: 1023px) {
 .modal-overlay-enter-from .modal-card, .modal-overlay-leave-to .modal-card { transform: translateY(100%); }
}
@media (min-width: 1024px) {
 .modal-overlay-enter-from .modal-card, .modal-overlay-leave-to .modal-card { transform: translateY(20px) scale(0.95); opacity: 0; }
}
</style>
