<template>
  <div class="flex flex-col gap-6">
    <!-- WIDGETS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      
      <!-- TOTAL ASATIDZ -->
      <div class="widget-card group p-6 rounded-2xl flex flex-col gap-3 relative overflow-hidden transition-all duration-300 cursor-pointer">
        <div class="absolute -right-4 -bottom-4 opacity-[0.05] group-hover:scale-110 group-hover:-rotate-6 group-hover:opacity-10 transition-all duration-500">
          <span class="material-symbols-outlined text-[100px] text-accent">groups</span>
        </div>
        <div class="flex flex-col relative z-10">
          <span class="text-sm font-bold tracking-wider" style="color: var(--text-muted)">TOTAL ASATIDZ</span>
          <span class="text-xs mt-0.5" style="color: var(--text-body)">Pengguna Aktif</span>
        </div>
        <div class="flex items-end justify-between mt-2 relative z-10">
          <div>
            <div class="text-4xl font-black" style="color: var(--text-heading)">{{ dashboardStore.admin.widgets.total_asatidz }}</div>
          </div>
          <div class="text-right">
            <div class="text-xs font-medium" style="color: var(--text-muted)">Personil</div>
          </div>
        </div>
      </div>

      <!-- HARI INI -->
      <div class="widget-card group p-6 rounded-2xl flex flex-col gap-3 relative overflow-hidden transition-all duration-300 cursor-pointer">
        <div class="absolute -right-4 -bottom-4 opacity-[0.05] group-hover:scale-110 group-hover:-rotate-6 group-hover:opacity-10 transition-all duration-500">
          <span class="material-symbols-outlined text-[100px] text-blue-500">today</span>
        </div>
        <div class="flex flex-col relative z-10">
          <span class="text-sm font-bold tracking-wider" style="color: var(--text-muted)">HARI INI (GLOBAL)</span>
          <span class="text-xs mt-0.5" style="color: var(--text-body)">{{ dashboardStore.admin.widgets.hari_ini.label }}</span>
        </div>
        <div class="flex items-end justify-between mt-2 relative z-10">
          <div>
            <div class="text-3xl font-black" style="color: var(--text-heading)">{{ dashboardStore.admin.widgets.hari_ini.poin }} <span class="text-sm font-medium opacity-60">Poin</span></div>
          </div>
          <div class="text-right">
            <div class="text-xl font-bold" style="color: var(--text-heading)">{{ dashboardStore.admin.widgets.hari_ini.laporan }}</div>
            <div class="text-xs font-medium" style="color: var(--text-muted)">Laporan</div>
          </div>
        </div>
      </div>

      <!-- BULAN INI -->
      <div class="widget-card group p-6 rounded-2xl flex flex-col gap-3 relative overflow-hidden transition-all duration-300 cursor-pointer">
        <div class="absolute -right-4 -bottom-4 opacity-[0.05] group-hover:scale-110 group-hover:-rotate-6 group-hover:opacity-10 transition-all duration-500">
          <span class="material-symbols-outlined text-[100px] text-purple-500">calendar_month</span>
        </div>
        <div class="flex flex-col relative z-10">
          <span class="text-sm font-bold tracking-wider" style="color: var(--text-muted)">BULAN INI (GLOBAL)</span>
          <span class="text-xs mt-0.5" style="color: var(--text-body)">{{ dashboardStore.admin.widgets.bulan_ini.label }}</span>
        </div>
        <div class="flex items-end justify-between mt-2 relative z-10">
          <div>
            <div class="text-3xl font-black" style="color: var(--text-heading)">{{ dashboardStore.admin.widgets.bulan_ini.poin }} <span class="text-sm font-medium opacity-60">Poin</span></div>
          </div>
          <div class="text-right">
            <div class="text-xl font-bold" style="color: var(--text-heading)">{{ dashboardStore.admin.widgets.bulan_ini.laporan }}</div>
            <div class="text-xs font-medium" style="color: var(--text-muted)">Laporan</div>
          </div>
        </div>
      </div>

      <!-- TAHUN INI -->
      <div class="widget-card group p-6 rounded-2xl flex flex-col gap-3 relative overflow-hidden transition-all duration-300 cursor-pointer">
        <div class="absolute -right-4 -bottom-4 opacity-[0.05] group-hover:scale-110 group-hover:-rotate-6 group-hover:opacity-10 transition-all duration-500">
          <span class="material-symbols-outlined text-[100px] text-yellow-500">emoji_events</span>
        </div>
        <div class="flex flex-col relative z-10">
          <span class="text-sm font-bold tracking-wider" style="color: var(--text-muted)">TAHUN INI (GLOBAL)</span>
          <span class="text-xs mt-0.5" style="color: var(--text-body)">{{ dashboardStore.admin.widgets.tahun_ini.label }}</span>
        </div>
        <div class="flex items-end justify-between mt-2 relative z-10">
          <div>
            <div class="text-3xl font-black" style="color: var(--text-heading)">{{ dashboardStore.admin.widgets.tahun_ini.poin }} <span class="text-sm font-medium opacity-60">Poin</span></div>
          </div>
          <div class="text-right">
            <div class="text-xl font-bold" style="color: var(--text-heading)">{{ dashboardStore.admin.widgets.tahun_ini.laporan }}</div>
            <div class="text-xs font-medium" style="color: var(--text-muted)">Laporan</div>
          </div>
        </div>
      </div>

    </div>

    <!-- MAIN CONTENT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- CHART -->
      <div class="lg:col-span-2 widget-card p-6 rounded-2xl flex flex-col gap-4">
        <h3 class="text-lg font-bold" style="color: var(--text-heading)">Grafik Aktivitas (7 Hari Terakhir)</h3>
        <div class="flex-1 min-h-[300px] relative">
          <canvas id="adminChart"></canvas>
        </div>
      </div>

      <!-- LEADERBOARD -->
      <div class="widget-card p-6 rounded-2xl flex flex-col gap-4">
        <h3 class="text-lg font-bold flex items-center gap-2" style="color: var(--text-heading)">
          <span class="material-symbols-outlined text-yellow-500">military_tech</span>
          Top 10 Bulan Ini
        </h3>
        <div class="flex flex-col gap-4 overflow-y-auto max-h-[300px] pr-2 custom-scrollbar">
          <div v-for="(asatidz, index) in dashboardStore.admin.leaderboard" :key="asatidz.id" class="flex items-center gap-4 p-3 rounded-xl border border-[var(--border)] transition-colors hover:border-accent">
            <div class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center font-bold text-accent shrink-0">
              {{ index + 1 }}
            </div>
            <div class="flex flex-col flex-1 min-w-0">
              <span class="text-sm font-bold truncate" style="color: var(--text-heading)">{{ asatidz.nama }}</span>
              <span class="text-xs" style="color: var(--text-muted)">{{ asatidz.total_poin_bulan_ini || 0 }} Poin</span>
            </div>
          </div>
          <div v-if="!dashboardStore.admin.leaderboard.length" class="text-sm text-center py-4" style="color: var(--text-muted)">Belum ada data</div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import { useDashboardStore } from '../../../stores/dashboard'
import { onMounted } from 'vue'
import Chart from 'chart.js/auto'

defineOptions({ layout: AdminLayout })

const dashboardStore = useDashboardStore()
let chartInstance = null

onMounted(async () => {
  await dashboardStore.fetchAdminDashboard()
  renderChart()
})

function renderChart() {
  const ctx = document.getElementById('adminChart')
  if (!ctx) return
  
  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: dashboardStore.admin.chart.labels,
      datasets: [
        {
          label: 'Total Poin',
          data: dashboardStore.admin.chart.points,
          borderColor: '#2563eb',
          backgroundColor: 'rgba(37, 99, 235, 0.1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true,
          yAxisID: 'y'
        },
        {
          label: 'Total Laporan',
          data: dashboardStore.admin.chart.reports,
          borderColor: '#10b981',
          backgroundColor: 'transparent',
          borderWidth: 2,
          borderDash: [5, 5],
          tension: 0.3,
          yAxisID: 'y1'
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: true, position: 'bottom', labels: { color: 'var(--text-muted)' } } },
      scales: {
        y: { type: 'linear', display: true, position: 'left', beginAtZero: true, grid: { color: 'rgba(100,116,139,0.1)' } },
        y1: { type: 'linear', display: true, position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } },
        x: { grid: { display: false } }
      }
    }
  })
}
</script>

<style scoped>
.widget-card { 
  background: var(--bg-card); 
  box-shadow: var(--shadow-card); 
  border: 1px solid var(--border);
}
.widget-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0,0,0,0.4);
  border-color: var(--color-accent);
}
:global(.admin-root[data-theme="light"]) .widget-card:hover {
  box-shadow: 0 12px 32px rgba(0,0,0,0.1);
}
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: var(--border-light); border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }
</style>
