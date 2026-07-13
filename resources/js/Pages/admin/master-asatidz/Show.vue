<template>
 <div class="flex flex-col gap-6">

  <!-- ═══ HEADER & BACK BUTTON ═══ -->
  <div class="flex items-center gap-4">
   <Link href="/administrator/asatidz" class="flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors" style="background: var(--bg-card); border-color: var(--border);">
    <span class="material-symbols-outlined text-[20px]" style="color: var(--text-heading)">arrow_back</span>
   </Link>
   <div class="flex flex-col">
    <h2 class="text-2xl font-black" style="color: var(--text-heading)">Detail Asatidz</h2>
    <span class="text-sm font-medium" style="color: var(--text-muted)">Informasi profil dan riwayat aktivitas</span>
   </div>
  </div>

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6" v-if="asatidz">
   
   <!-- ═══ PROFILE CARD ═══ -->
   <div class="xl:col-span-1 flex flex-col gap-6">
    <div class="profile-card p-6 rounded-2xl flex flex-col gap-6">
     <div class="flex items-center justify-between">
      <h3 class="text-lg font-bold" style="color: var(--text-heading)">Informasi Akun</h3>
      <div class="flex items-center gap-2 shrink-0">
       <button @click="openEditModal" class="action-btn p-2 rounded-lg transition-all" title="Edit Profil">
        <span class="material-symbols-outlined text-[20px]">edit</span>
       </button>
      </div>
     </div>
     
     <div class="flex flex-col items-center gap-3 border-b pb-6" style="border-color: var(--border)">
      <div class="w-24 h-24 rounded-full bg-accent/10 flex items-center justify-center border-4 border-accent/20 text-accent">
       <span class="material-symbols-outlined text-[48px]">person</span>
      </div>
      <div class="text-center">
       <h4 class="text-xl font-bold" style="color: var(--text-heading)">{{ asatidz.nama }}</h4>
       <div class="text-sm font-medium mt-1" style="color: var(--text-muted)">{{ asatidz.user?.username }}</div>
      </div>
      <span :class="statusBadge(asatidz.user?.status)">{{ asatidz.user?.status || 'Unknown' }}</span>
     </div>

     <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-1">
       <span class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-muted)">No. Telepon</span>
       <span class="text-sm font-medium" style="color: var(--text-heading)">{{ asatidz.no_telepon || '-' }}</span>
      </div>
      <div class="flex flex-col gap-1">
       <span class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-muted)">Alamat</span>
       <span class="text-sm font-medium" style="color: var(--text-heading)">{{ asatidz.alamat || '-' }}</span>
      </div>
      <div class="flex flex-col gap-1">
       <span class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-muted)">Terdaftar Pada</span>
       <span class="text-sm font-medium" style="color: var(--text-heading)">{{ formatDate(asatidz.created_at) }}</span>
      </div>
     </div>

     <div class="mt-2 pt-4 border-t" style="border-color: var(--border)">
      <button @click="resetPassword" :disabled="resetting" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg font-bold text-sm transition-all border border-red-500/30 text-red-500 hover:bg-red-500 hover:text-white disabled:opacity-50">
       <span v-if="resetting" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
       <span v-else class="material-symbols-outlined text-[18px]">lock_reset</span>
       Reset Password ke "password"
      </button>
     </div>
    </div>
   </div>

   <!-- ═══ ACTIVITY HISTORY ═══ -->
   <div class="xl:col-span-2 flex flex-col gap-6">

    <!-- CHART CARD -->
      <!-- CHART CARD -->
      <div class="profile-card rounded-2xl flex flex-col p-6 mb-6">
       <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
         <h2 class="text-lg font-bold flex items-center flex-wrap gap-2" style="color: var(--text-heading)">Grafik Poin</h2>
         <p class="text-sm" style="color: var(--text-muted)">{{ chartSubtitle }}</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
         <select v-model="chartMode" class="filter-input rounded-lg py-1.5 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-accent cursor-pointer">
          <option value="harian">Harian</option>
          <option value="bulanan">Bulanan</option>
          <option value="tahunan">Tahunan</option>
         </select>
         
         <input v-if="chartMode === 'harian'" type="month" v-model="chartFilterMonth" class="filter-input rounded-lg py-1.5 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-accent" title="Pilih Bulan" />
         
         <input v-if="chartMode === 'bulanan'" type="number" min="2000" max="2100" v-model="chartFilterYear" class="filter-input w-[100px] rounded-lg py-1.5 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-accent" title="Pilih Tahun" placeholder="Tahun" />
        </div>
       </div>
       <div class="h-[300px] w-full relative">
        <div v-if="chartLoading" class="absolute inset-0 flex items-center justify-center bg-black/5 z-10 rounded-xl">
         <div class="w-8 h-8 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
        </div>
        <canvas id="asatidzChart"></canvas>
       </div>
      </div>

    <div class="profile-card rounded-2xl flex flex-col overflow-hidden">
     <div class="p-6 border-b flex flex-col gap-4" style="border-color: var(--border)">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
       <h3 class="text-lg font-bold shrink-0" style="color: var(--text-heading)">Riwayat Kegiatan</h3>
       <div class="flex items-center gap-1.5 px-3 py-1.5 bg-accent/10 rounded-lg border border-accent/20 w-fit sm:ml-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-accent">Total Poin:</span>
        <span class="text-sm font-black text-accent">{{ totalPoin }}</span>
       </div>
      </div>
      <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3">
       <div class="flex items-center gap-2 w-full sm:w-auto">
        <span class="text-sm font-bold shrink-0" style="color: var(--text-muted)">Filter:</span>
        <select v-model="filterMode" class="filter-input flex-1 sm:w-auto rounded-lg py-1.5 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-accent cursor-pointer">
         <option value="bulan">Mode Bulan</option>
         <option value="rentang">Rentang Waktu</option>
        </select>
       </div>

       <div v-if="filterMode === 'bulan'" class="flex items-center gap-2 w-full sm:w-auto">
        <input type="month" v-model="filterMonth" class="filter-input flex-1 sm:w-auto rounded-lg py-1.5 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-accent" title="Filter Bulan & Tahun" />
       </div>

       <div v-if="filterMode === 'rentang'" class="flex items-center gap-2 w-full sm:w-auto">
        <input type="date" v-model="filterStartDate" class="filter-input flex-1 sm:w-[130px] rounded-lg py-1.5 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-accent" title="Tanggal Mulai" />
        <span class="text-xs font-bold hidden sm:block" style="color: var(--text-muted)">-</span>
        <input type="date" v-model="filterEndDate" class="filter-input flex-1 sm:w-[130px] rounded-lg py-1.5 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-accent" title="Tanggal Selesai" />
       </div>
       
       <button @click="clearFilter" v-if="isFilterActive" class="flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg text-sm font-bold transition-all w-full sm:w-auto border" style="color: var(--text-muted); border-color: var(--border)">
        <span class="material-symbols-outlined text-[16px]">close</span>
        Reset
       </button>
       
       <a :href="pdfExportUrl" target="_blank" class="flex items-center justify-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold transition-all whitespace-nowrap w-full sm:w-auto sm:ml-auto">
        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
        Download PDF
       </a>
      </div>
     </div>
     
     <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
       <thead>
        <tr class="border-b" style="border-color: var(--border); background: var(--bg-input)">
         <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-40" style="color: var(--text-muted)">Tanggal</th>
         <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color: var(--text-muted)">Kegiatan</th>
         <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right w-32" style="color: var(--text-muted)">Total Poin</th>
        </tr>
       </thead>
       <tbody class="divide-y" style="border-color: var(--border)">
        <tr v-if="loadingKegiatan">
         <td colspan="3" class="px-6 py-12 text-center">
          <div class="flex flex-col items-center justify-center gap-3">
           <span class="material-symbols-outlined text-accent animate-spin text-[32px]">progress_activity</span>
           <span class="text-sm font-medium" style="color: var(--text-muted)">Memuat kegiatan...</span>
          </div>
         </td>
        </tr>
        <template v-else>
         <tr v-for="item in kegiatanList" :key="item.id" class="hover:bg-black/5 transition-colors">
          <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--text-heading)">
           {{ formatDate(item.tanggal_kegiatan) }}
          </td>
          <td class="px-6 py-4">
           <ul class="flex flex-col gap-1">
            <li v-for="detail in item.details" :key="detail.id" class="text-sm" style="color: var(--text-body)">
             • {{ detail.master_kegiatan?.nama }}
             <span v-if="detail.keterangan" class="italic opacity-70">({{ detail.keterangan }})</span>
            </li>
           </ul>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-right">
           <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-accent/10 text-accent">
            {{ item.total_poin }}
           </span>
          </td>
         </tr>
         <tr v-if="!kegiatanList.length">
          <td colspan="3" class="px-6 py-12 text-center text-sm" style="color: var(--text-muted)">
           Tidak ada riwayat kegiatan.
          </td>
         </tr>
        </template>
       </tbody>
      </table>
     </div>
     
     <!-- Pagination Controls -->
     <div v-if="lastPage > 1" class="flex flex-wrap items-center justify-between gap-4 p-6 border-t" style="border-color: var(--border)">
      <span class="text-sm" style="color: var(--text-muted)">Halaman {{ currentPage }} dari {{ lastPage }}</span>
      <div class="flex items-center gap-2 shrink-0">
       <button @click="fetchKegiatan(currentPage - 1)" :disabled="currentPage === 1" class="px-3 py-1.5 rounded-lg border text-sm font-medium transition-all hover:bg-black/5 disabled:opacity-50 disabled:cursor-not-allowed" style="border-color: var(--border); color: var(--text-body)">
        Sebelumnya
       </button>
       <button @click="fetchKegiatan(currentPage + 1)" :disabled="currentPage === lastPage" class="px-3 py-1.5 rounded-lg border text-sm font-medium transition-all hover:bg-black/5 disabled:opacity-50 disabled:cursor-not-allowed" style="border-color: var(--border); color: var(--text-body)">
        Selanjutnya
       </button>
      </div>
     </div>
    </div>
   </div>
  </div>

  <!-- Loading State -->
  <div v-else class="flex flex-col items-center justify-center py-20 gap-4">
   <span class="material-symbols-outlined text-[48px] animate-spin text-accent">progress_activity</span>
   <span class="text-sm font-medium" style="color: var(--text-muted)">Memuat data asatidz...</span>
  </div>

  <!-- ═══ MODAL EDIT ═══ -->
  <Transition name="fade">
   <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="showModal = false">
    <div class="modal-card w-full max-w-lg rounded-2xl p-6 flex flex-col gap-5">
     <h3 class="text-lg font-bold" style="color: var(--text-heading)">Edit Profil Asatidz</h3>
     <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Username</label>
       <input v-model="form.username" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" />
      </div>
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Nama Lengkap</label>
       <input v-model="form.nama" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" />
      </div>
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">No Telepon</label>
       <input v-model="form.no_telepon" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" />
      </div>
      <div class="flex flex-col gap-1.5">
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
       />
      </div>
      <div class="flex flex-col gap-1.5 sm:col-span-2">
       <label class="text-sm font-medium" style="color: var(--text-body)">Alamat</label>
       <textarea v-model="form.alamat" class="filter-input rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent" rows="3"></textarea>
      </div>
     </div>
     <div v-if="formError" class="text-sm text-red-400 bg-red-500/10 border border-red-500/30 rounded-lg px-3 py-2">{{ formError }}</div>
     <div class="flex justify-end gap-3 mt-2">
      <button @click="showModal = false" class="px-5 py-2 rounded-lg text-sm font-medium cursor-pointer" style="color: var(--text-body); border: 1px solid var(--border)">Batal</button>
      <button @click="saveData" :disabled="formLoading" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-accent text-sm font-bold cursor-pointer active:scale-95 disabled:opacity-50" style="color: var(--text-btn)">
       <span v-if="formLoading" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
       Simpan Perubahan
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
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import Chart from 'chart.js/auto'
import api from '../../../axios'

defineOptions({ layout: AdminLayout })

const props = defineProps({
 id: {
  type: [String, Number],
  required: true
 }
})

const asatidz = ref(null)
const kegiatanList = ref([])
const loadingKegiatan = ref(false)
const resetting = ref(false)
const filterMode = ref('bulan')
const filterMonth = ref('')
const filterStartDate = ref('')
const filterEndDate = ref('')

const chartMode = ref('harian')
const chartFilterMonth = ref('')
const chartFilterYear = ref(new Date().getFullYear().toString())
const chartDataList = ref([])
const chartLoading = ref(false)
let chartInstance = null

const chartSubtitle = computed(() => {
  if (chartMode.value === 'harian' && chartFilterMonth.value) {
    const [y, m] = chartFilterMonth.value.split('-')
    const d = new Date(y, m - 1, 1)
    return d.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })
  } else if (chartMode.value === 'bulanan' && chartFilterYear.value) {
    return `Tahun ${chartFilterYear.value}`
  } else if (chartMode.value === 'tahunan') {
    return 'Rekap Tahunan'
  }
  return ''
})

let chartTimeout = null
watch([chartMode, chartFilterMonth, chartFilterYear], () => {
  clearTimeout(chartTimeout)
  chartTimeout = setTimeout(() => {
    fetchChartData()
  }, 300)
})

const isFilterActive = computed(() => {
  const now = new Date()
  const y = now.getFullYear()
  const m = String(now.getMonth() + 1).padStart(2, '0')
  const currentMonthStr = `${y}-${m}`
  
  if (filterMode.value === 'bulan') {
    return filterMonth.value !== currentMonthStr
  }
  return !!filterStartDate.value || !!filterEndDate.value
})

function clearFilter() {
 const now = new Date()
 const y = now.getFullYear()
 const m = String(now.getMonth() + 1).padStart(2, '0')
 
 filterMode.value = 'bulan'
 filterMonth.value = `${y}-${m}`
 filterStartDate.value = ''
 filterEndDate.value = ''
}

const totalPoin = computed(() => {
 return kegiatanList.value.reduce((sum, item) => sum + (Number(item.total_poin) || 0), 0)
})

const pdfExportUrl = computed(() => {
 let url = `/api/laporan/export/pdf?asatidz_id=${props.id}`
 if (filterMode.value === 'bulan' && filterMonth.value) {
  const [year, month] = filterMonth.value.split('-')
  const startDate = `${year}-${month}-01`
  const lastDay = new Date(year, month, 0).getDate()
  const endDate = `${year}-${month}-${lastDay}`
  url += `&start_date=${startDate}&end_date=${endDate}`
 } else if (filterMode.value === 'rentang' && filterStartDate.value && filterEndDate.value) {
  url += `&start_date=${filterStartDate.value}&end_date=${filterEndDate.value}`
 }
 return url
})

const currentPage = ref(1)
const lastPage = ref(1)

let filterTimeout = null
watch([filterMode, filterMonth, filterStartDate, filterEndDate], () => {
 clearTimeout(filterTimeout)
 filterTimeout = setTimeout(() => {
  fetchKegiatan(1)
 }, 300)
})

watch(filterStartDate, (newVal) => {
 if (newVal && !filterEndDate.value) {
  filterEndDate.value = newVal
 }
})

watch(filterEndDate, (newVal) => {
 if (newVal && !filterStartDate.value) {
  filterStartDate.value = newVal
 }
})

// Modal edit state
const showModal = ref(false)
const form = ref({ username: '', nama: '', no_telepon: '', alamat: '', status: 'Active' })
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

async function fetchAsatidz() {
 try {
  const { data } = await api.get(`/asatidz/${props.id}`)
  asatidz.value = data
 } catch (e) {
  console.error(e)
  alert('Gagal memuat profil Asatidz.')
 }
}

async function fetchKegiatan(page = 1) {
 loadingKegiatan.value = true
 try {
  const params = { asatidz_id: props.id, per_page: 10, page }
  if (filterMode.value === 'bulan' && filterMonth.value) {
   const [year, month] = filterMonth.value.split('-')
   const startDate = `${year}-${month}-01`
   const lastDay = new Date(year, month, 0).getDate()
   const endDate = `${year}-${month}-${lastDay}`
   params.start_date = startDate
   params.end_date = endDate
  } else if (filterMode.value === 'rentang') {
   if (filterStartDate.value) params.start_date = filterStartDate.value
   if (filterEndDate.value) params.end_date = filterEndDate.value
  }
  const { data } = await api.get('/laporan', { params })
  kegiatanList.value = data.data
  currentPage.value = data.current_page || 1
  lastPage.value = data.last_page || 1
 } catch (e) {
  console.error(e)
 } finally {
  loadingKegiatan.value = false
 }
}

async function fetchChartData() {
 chartLoading.value = true
 try {
  const params = { asatidz_id: props.id, per_page: 1000 }
  if (chartMode.value === 'harian' && chartFilterMonth.value) {
   const [year, month] = chartFilterMonth.value.split('-')
   const lastDay = new Date(year, month, 0).getDate()
   params.start_date = `${year}-${month}-01`
   params.end_date = `${year}-${month}-${lastDay}`
  } else if (chartMode.value === 'bulanan' && chartFilterYear.value) {
   params.start_date = `${chartFilterYear.value}-01-01`
   params.end_date = `${chartFilterYear.value}-12-31`
  }
  const { data } = await api.get('/laporan', { params })
  chartDataList.value = data.data
  await nextTick()
  updateChart()
 } catch (e) {
  console.error(e)
 } finally {
  chartLoading.value = false
 }
}

function updateChart() {
  const ctx = document.getElementById('asatidzChart')
  if (!ctx) return
  
  if (chartInstance) chartInstance.destroy()

  const dataMap = new Map()
  let start = new Date()
  let end = new Date()

  if (chartMode.value === 'harian' && chartFilterMonth.value) {
    const [y, m] = chartFilterMonth.value.split('-')
    start = new Date(y, m - 1, 1)
    end = new Date(y, m, 0)
  } else if (chartMode.value === 'bulanan' && chartFilterYear.value) {
    start = new Date(chartFilterYear.value, 0, 1)
    end = new Date(chartFilterYear.value, 11, 31)
  } else if (chartMode.value === 'tahunan') {
    if (chartDataList.value.length > 0) {
      const dates = chartDataList.value.map(k => new Date(k.tanggal_kegiatan).getTime())
      start = new Date(Math.min(...dates))
      end = new Date(Math.max(...dates))
      start = new Date(start.getFullYear(), 0, 1)
      end = new Date(end.getFullYear(), 11, 31)
    } else {
      start = new Date(new Date().getFullYear() - 4, 0, 1)
      end = new Date(new Date().getFullYear(), 11, 31)
    }
  }

  if (chartMode.value === 'harian') {
    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
      const y = d.getFullYear()
      const m = String(d.getMonth() + 1).padStart(2, '0')
      const day = String(d.getDate()).padStart(2, '0')
      dataMap.set(`${y}-${m}-${day}`, 0)
    }
  } else if (chartMode.value === 'bulanan') {
    const startYear = start.getFullYear()
    const startMonth = start.getMonth()
    const endYear = end.getFullYear()
    const endMonth = end.getMonth()
    
    for (let y = startYear; y <= endYear; y++) {
      const mStart = (y === startYear) ? startMonth : 0
      const mEnd = (y === endYear) ? endMonth : 11
      for (let m = mStart; m <= mEnd; m++) {
        const mm = String(m + 1).padStart(2, '0')
        dataMap.set(`${y}-${mm}`, 0)
      }
    }
  } else {
    const startYear = start.getFullYear()
    const endYear = end.getFullYear()
    for (let y = startYear; y <= endYear; y++) {
      dataMap.set(`${y}`, 0)
    }
  }

  chartDataList.value.forEach(item => {
    const d = new Date(item.tanggal_kegiatan)
    let key
    const y = d.getFullYear()
    const m = String(d.getMonth() + 1).padStart(2, '0')
    const day = String(d.getDate()).padStart(2, '0')
    
    if (chartMode.value === 'harian') {
      key = `${y}-${m}-${day}`
    } else if (chartMode.value === 'bulanan') {
      key = `${y}-${m}`
    } else {
      key = `${y}`
    }
    
    if (dataMap.has(key)) {
      dataMap.set(key, dataMap.get(key) + (Number(item.total_poin) || 0))
    } else {
      dataMap.set(key, Number(item.total_poin) || 0)
    }
  })

  const labels = Array.from(dataMap.keys()).map(key => {
    if (chartMode.value === 'harian') {
      const [y, m, d] = key.split('-')
      return Number(d).toString()
    } else if (chartMode.value === 'bulanan') {
      const [y, m] = key.split('-')
      const date = new Date(y, m - 1, 1)
      return date.toLocaleDateString('id-ID', { month: 'short' })
    } else {
      return key
    }
  })
  const points = Array.from(dataMap.values())

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Total Poin',
          data: points,
          backgroundColor: '#2563eb',
          borderRadius: 6,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { type: 'linear', display: true, beginAtZero: true, grid: { color: 'rgba(100,116,139,0.1)' } },
        x: { grid: { display: false } }
      }
    }
  })
}

onMounted(() => {
 // Default to current month
 const now = new Date()
 const y = now.getFullYear()
 const m = String(now.getMonth() + 1).padStart(2, '0')
 filterMonth.value = `${y}-${m}`
 chartFilterMonth.value = `${y}-${m}`
 
 fetchAsatidz()
 fetchKegiatan()
 fetchChartData()
})

function openEditModal() {
 form.value = {
  username: asatidz.value.user?.username || '',
  nama: asatidz.value.nama,
  no_telepon: asatidz.value.no_telepon || '',
  alamat: asatidz.value.alamat || '',
  status: asatidz.value.user?.status || 'Active',
 }
 formError.value = ''
 showModal.value = true
}

async function saveData() {
 formError.value = ''
 formLoading.value = true
 try {
  await api.put(`/asatidz/${props.id}`, form.value)
  showModal.value = false
  await fetchAsatidz()
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

async function resetPassword() {
 if (!confirm('Anda yakin ingin me-reset password akun ini menjadi "password"?')) return
 
 resetting.value = true
 try {
  const { data } = await api.post(`/asatidz/${props.id}/reset-password`)
  alert(data.message || 'Password berhasil direset.')
 } catch (e) {
  alert(e.response?.data?.message || 'Gagal mereset password.')
 } finally {
  resetting.value = false
 }
}

function statusBadge(status) {
 const base = 'admin-badge admin-badge--status'
 if (status === 'Active') return `${base} admin-badge--success`
 return `${base} admin-badge--muted`
}

function formatDate(dateStr) {
 if (!dateStr) return '-'
 const d = new Date(dateStr)
 return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
}
</script>

<style scoped>
.profile-card {
 background: var(--bg-card);
 border: 1px solid var(--border);
 box-shadow: var(--shadow-card);
}
.action-btn { color: var(--text-muted); }
.action-btn:hover { color: var(--color-accent); background: var(--bg-input); }

.filter-input {
 background: var(--bg-card);
 border: 1px solid var(--border);
 color: var(--text-heading);
 transition: box-shadow 0.3s ease;
}
.filter-input::placeholder { color: var(--text-muted); }
.filter-input:hover { box-shadow: 0 0 15px rgba(37, 99, 235, 0.15); }
.filter-input:focus { border-color: var(--color-accent); box-shadow: 0 0 12px rgba(37, 99, 235, 0.3); }

.modal-card { background: var(--bg-card); border: 1px solid var(--border); box-shadow: 0 20px 60px rgba(0,0,0,0.5); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

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
.admin-badge--success { --badge-color: #4ade80; --badge-bg: rgba(34, 197, 94, 0.12); --badge-border: rgba(34, 197, 94, 0.28); }
.admin-badge--muted { --badge-color: #94a3b8; --badge-bg: rgba(148, 163, 184, 0.12); --badge-border: rgba(148, 163, 184, 0.28); }

:global(.admin-root[data-theme="light"]) .admin-badge--success { --badge-color: #15803d; --badge-bg: #dcfce7; --badge-border: #86efac; }
:global(.admin-root[data-theme="light"]) .admin-badge--muted { --badge-color: #475569; --badge-bg: #f1f5f9; --badge-border: #cbd5e1; }

/* Vue Multiselect */
:deep(.multiselect) { min-height: 36px; border-radius: 0.5rem; cursor: pointer; }
:deep(.multiselect__tags) { background: var(--bg-input); border: 1px solid var(--border); border-radius: 0.75rem; min-height: 36px; padding: 8px 40px 0 12px; }
:deep(.multiselect--active .multiselect__tags) { border-color: var(--color-accent); box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15); }
:deep(.multiselect__single) { background: transparent; color: var(--text-heading); font-size: 0.875rem; }
:deep(.multiselect__content-wrapper) { background: var(--bg-card); border: 1px solid var(--border); border-radius: 0.75rem; }
:deep(.multiselect__option) { color: var(--text-body); font-size: 0.875rem; }
:deep(.multiselect__option--highlight) { background: rgba(37, 99, 235, 0.12); color: var(--color-accent); }
:deep(.multiselect__option--selected) { background: rgba(37, 99, 235, 0.18); color: var(--color-accent); font-weight: 700; }
</style>
