<template>
 <div class="flex flex-col gap-6">

  <!-- ═══ ACTION BAR & FILTERS ═══ -->
  <div class="flex flex-col gap-4">
   <div class="flex items-center justify-between">
    <h2 class="text-2xl font-black" style="color: var(--text-heading)">Laporan & Rekapitulasi</h2>
    <div class="flex items-center gap-3">
     <a 
       :href="exportExcelUrl" 
       target="_blank"
       class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition-all"
     >
      <span class="material-symbols-outlined text-[20px]">table</span>
      Excel
     </a>
     <a 
       :href="exportPdfUrl" 
       target="_blank"
       class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition-all"
     >
      <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
      PDF
     </a>
    </div>
   </div>

    <div class="table-wrapper rounded-xl p-4 flex flex-col md:flex-row gap-4 items-end">
     <!-- Filter Asatidz -->
     <div class="flex-1 w-full flex flex-col gap-1.5">
      <label class="text-xs font-bold" style="color: var(--text-muted)">ASATIDZ</label>
      <select v-model="filters.asatidz_id" class="input-field w-full">
       <option value="">Semua Asatidz</option>
       <option v-for="a in asatidzOptions" :key="a.id" :value="a.id">{{ a.nama }}</option>
      </select>
     </div>

     <!-- Filter Mode -->
     <div class="flex-1 w-full flex flex-col gap-1.5">
      <label class="text-xs font-bold" style="color: var(--text-muted)">MODE FILTER WAKTU</label>
      <select v-model="filterMode" class="input-field w-full">
       <option value="bulan">Mode Bulan</option>
       <option value="rentang">Mode Rentang Tanggal</option>
      </select>
     </div>

     <!-- Filter Bulan -->
     <div v-if="filterMode === 'bulan'" class="flex-1 w-full flex flex-col gap-1.5">
      <label class="text-xs font-bold" style="color: var(--text-muted)">PILIH BULAN</label>
      <input type="month" v-model="filterMonth" class="input-field w-full" />
     </div>

     <!-- Filter Rentang -->
     <template v-if="filterMode === 'rentang'">
      <div class="flex-1 w-full flex flex-col gap-1.5">
       <label class="text-xs font-bold" style="color: var(--text-muted)">DARI TANGGAL</label>
       <input type="date" v-model="filters.start_date" class="input-field w-full" />
      </div>
      <div class="flex-1 w-full flex flex-col gap-1.5">
       <label class="text-xs font-bold" style="color: var(--text-muted)">SAMPAI TANGGAL</label>
       <input type="date" v-model="filters.end_date" class="input-field w-full" />
      </div>
     </template>

     <!-- Clear Filters -->
     <button @click="clearFilters" class="px-4 py-2 border rounded-lg font-bold transition-colors hover:bg-red-500/10 hover:text-red-500 hover:border-red-500/30 h-[42px]" style="border-color: var(--border); color: var(--text-muted)">
      Reset
     </button>
    </div>
  </div>

  <!-- ═══ DATA TABLE ═══ -->
  <div class="table-wrapper rounded-xl overflow-hidden">
   <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
     <thead>
      <tr>
       <th class="px-6 py-4 text-xs font-black tracking-wider uppercase border-b" style="color: var(--text-muted); border-color: var(--border)">Tanggal</th>
       <th class="px-6 py-4 text-xs font-black tracking-wider uppercase border-b" style="color: var(--text-muted); border-color: var(--border)">Nama Asatidz</th>
       <th class="px-6 py-4 text-xs font-black tracking-wider uppercase border-b" style="color: var(--text-muted); border-color: var(--border)">Daftar Kegiatan</th>
       <th class="px-6 py-4 text-xs font-black tracking-wider uppercase border-b" style="color: var(--text-muted); border-color: var(--border)">Total Poin</th>
      </tr>
     </thead>
     <tbody class="divide-y" style="border-color: var(--border)">
      <tr v-for="item in data" :key="item.id" class="transition-colors hover:bg-black/5" :style="{ borderColor: 'var(--border)' }">
       <td class="px-6 py-5 whitespace-nowrap font-medium" style="color: var(--text-heading)">
        {{ formatDate(item.tanggal_kegiatan) }}
       </td>
       <td class="px-6 py-5 whitespace-nowrap font-bold" style="color: var(--text-heading)">
        {{ item.asatidz?.nama }}
       </td>
       <td class="px-6 py-5">
        <div class="flex flex-col gap-1">
         <div v-for="detail in item.details" :key="detail.id" class="text-sm" style="color: var(--text-muted)">
          • {{ detail.master_kegiatan?.nama }} 
          <span v-if="detail.keterangan" class="italic opacity-70">({{ detail.keterangan }})</span>
         </div>
        </div>
       </td>
       <td class="px-6 py-5 whitespace-nowrap">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-accent/10 text-accent">
         <span class="material-symbols-outlined text-[14px]">star</span>
         {{ item.total_poin }}
        </span>
       </td>
      </tr>
      <tr v-if="!data.length && !loading">
       <td colspan="4" class="px-6 py-12 text-center text-sm" style="color: var(--text-muted)">
        <div class="flex flex-col items-center gap-2">
         <span class="material-symbols-outlined text-[48px] opacity-20">inventory_2</span>
         Tidak ada laporan kegiatan yang sesuai kriteria.
        </div>
       </td>
      </tr>
     </tbody>
    </table>
   </div>

   <!-- ═══ PAGINATION ═══ -->
   <div v-if="pagination.lastPage > 1" class="px-6 py-4 border-t flex items-center justify-between" style="border-color: var(--border)">
    <div class="text-sm" style="color: var(--text-muted)">
     Menampilkan <span class="font-bold text-accent">{{ pagination.from }}</span> - <span class="font-bold text-accent">{{ pagination.to }}</span> dari <span class="font-bold">{{ pagination.total }}</span>
    </div>
    <div class="flex gap-1">
     <button 
      v-for="page in pageNumbers" 
      :key="page"
      @click="page !== '...' && goToPage(page)"
      :class="['w-8 h-8 flex items-center justify-center rounded-lg text-sm font-bold transition-all', page === currentPage ? 'bg-accent text-white shadow-lg shadow-accent/30' : 'hover:bg-accent/10']"
      :style="{ color: page === currentPage ? '#fff' : 'var(--text-heading)', cursor: page === '...' ? 'default' : 'pointer' }"
     >
      {{ page }}
     </button>
    </div>
   </div>
  </div>

 </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import api from '../../../axios'
import { useAuthStore } from '../../../stores/auth'

defineOptions({ layout: AdminLayout })

const authStore = useAuthStore()
const token = localStorage.getItem('token')

const loading = ref(false)
const data = ref([])
const asatidzOptions = ref([])

const pagination = ref({ total: 0, perPage: 10, currentPage: 1, lastPage: 1, from: 0, to: 0 })
const currentPage = ref(1)

const filterMode = ref('bulan')
const date = new Date()
const y = date.getFullYear()
const m = String(date.getMonth() + 1).padStart(2, '0')
const filterMonth = ref(`${y}-${m}`)

const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).toISOString().split('T')[0]
const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).toISOString().split('T')[0]

const filters = ref({
 asatidz_id: '',
 start_date: firstDay,
 end_date: lastDay
})

watch(filterMonth, (newVal) => {
 if (filterMode.value === 'bulan' && newVal) {
  const [year, month] = newVal.split('-')
  filters.value.start_date = `${year}-${month}-01`
  const lastDate = new Date(year, month, 0)
  const ldy = lastDate.getFullYear()
  const ldm = String(lastDate.getMonth() + 1).padStart(2, '0')
  const ldd = String(lastDate.getDate()).padStart(2, '0')
  filters.value.end_date = `${ldy}-${ldm}-${ldd}`
 }
})

watch(filterMode, (newVal) => {
 if (newVal === 'bulan') {
   const now = new Date()
   const currY = now.getFullYear()
   const currM = String(now.getMonth() + 1).padStart(2, '0')
   filterMonth.value = `${currY}-${currM}`
 } else {
   filters.value.start_date = ''
   filters.value.end_date = ''
 }
})

// Build export URLs dynamically based on filters
const exportExcelUrl = computed(() => {
 const params = new URLSearchParams()
 if (filters.value.asatidz_id) params.append('asatidz_id', filters.value.asatidz_id)
 if (filters.value.start_date) params.append('start_date', filters.value.start_date)
 if (filters.value.end_date) params.append('end_date', filters.value.end_date)
 return `/api/laporan/export?${params.toString()}`
})

const exportPdfUrl = computed(() => {
 const params = new URLSearchParams()
 if (filters.value.asatidz_id) params.append('asatidz_id', filters.value.asatidz_id)
 if (filters.value.start_date) params.append('start_date', filters.value.start_date)
 if (filters.value.end_date) params.append('end_date', filters.value.end_date)
 return `/api/laporan/export/pdf?${params.toString()}`
})

async function fetchAsatidzOptions() {
 try {
  const res = await api.get('/asatidz', { params: { per_page: 500 } })
  asatidzOptions.value = res.data.data
 } catch(e) {
  console.error(e)
 }
}

async function loadData() {
 loading.value = true
 try {
  const { data: res } = await api.get('/laporan', {
   params: {
    page: currentPage.value,
    per_page: pagination.value.perPage,
    asatidz_id: filters.value.asatidz_id,
    start_date: filters.value.start_date,
    end_date: filters.value.end_date
   }
  })
  data.value = res.data
  pagination.value = {
   total: res.total,
   perPage: res.per_page,
   currentPage: res.current_page,
   lastPage: res.last_page,
   from: res.from,
   to: res.to
  }
 } catch (e) {
  console.error(e)
 } finally {
  loading.value = false
 }
}

onMounted(() => {
 fetchAsatidzOptions()
 loadData()
})

watch(filters, () => {
 currentPage.value = 1
 loadData()
}, { deep: true })

function clearFilters() {
 filters.value.asatidz_id = ''
 filterMode.value = 'bulan'
 const now = new Date()
 const currY = now.getFullYear()
 const currM = String(now.getMonth() + 1).padStart(2, '0')
 filterMonth.value = `${currY}-${currM}`
}

function formatDate(dateStr) {
 if (!dateStr) return '-'
 const d = new Date(dateStr)
 return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })
}

function goToPage(page) {
 if (page >= 1 && page <= pagination.value.lastPage) {
  currentPage.value = page
  loadData()
 }
}

const pageNumbers = computed(() => {
 const last = pagination.value.lastPage || 1
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
</script>

<style scoped>
.table-wrapper {
 background: var(--bg-card);
 border: 1px solid var(--border);
 box-shadow: var(--shadow-card);
}
.input-field {
 background: var(--bg-input);
 border: 1px solid var(--border);
 color: var(--text-heading);
 border-radius: 8px;
 padding: 8px 12px;
 font-size: 14px;
 outline: none;
 transition: border-color 0.2s;
}
.input-field:focus {
 border-color: var(--color-accent);
}
</style>
