<template>
  <div class="flex flex-col gap-6">
    <!-- WIDGETS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      
      <!-- HARI INI -->
      <div class="widget-card group p-6 rounded-2xl flex flex-col gap-3 relative overflow-hidden transition-all duration-300 cursor-pointer">
        <div class="absolute -right-4 -bottom-4 opacity-[0.05] group-hover:scale-110 group-hover:-rotate-6 group-hover:opacity-10 transition-all duration-500">
          <span class="material-symbols-outlined text-[100px] text-blue-500">today</span>
        </div>
        <div class="flex flex-col relative z-10">
          <span class="text-sm font-bold tracking-wider" style="color: var(--text-muted)">HARI INI</span>
          <span class="text-xs mt-0.5" style="color: var(--text-body)">{{ dashboardStore.asatidz.widgets.hari_ini.label }}</span>
        </div>
        <div class="flex items-end justify-between mt-2 relative z-10">
          <div>
            <div class="text-3xl font-black" style="color: var(--text-heading)">{{ dashboardStore.asatidz.widgets.hari_ini.poin }} <span class="text-sm font-medium opacity-60">Poin</span></div>
          </div>
          <div class="text-right">
            <div class="text-xl font-bold" style="color: var(--text-heading)">{{ dashboardStore.asatidz.widgets.hari_ini.laporan }}</div>
            <div class="text-xs font-medium" style="color: var(--text-muted)">Data</div>
          </div>
        </div>
      </div>

      <!-- MINGGU INI -->
      <div class="widget-card group p-6 rounded-2xl flex flex-col gap-3 relative overflow-hidden transition-all duration-300 cursor-pointer">
        <div class="absolute -right-4 -bottom-4 opacity-[0.05] group-hover:scale-110 group-hover:-rotate-6 group-hover:opacity-10 transition-all duration-500">
          <span class="material-symbols-outlined text-[100px] text-green-500">date_range</span>
        </div>
        <div class="flex flex-col relative z-10">
          <span class="text-sm font-bold tracking-wider" style="color: var(--text-muted)">MINGGU INI</span>
          <span class="text-xs mt-0.5" style="color: var(--text-body)">{{ dashboardStore.asatidz.widgets.minggu_ini.label }}</span>
        </div>
        <div class="flex items-end justify-between mt-2 relative z-10">
          <div>
            <div class="text-3xl font-black" style="color: var(--text-heading)">{{ dashboardStore.asatidz.widgets.minggu_ini.poin }} <span class="text-sm font-medium opacity-60">Poin</span></div>
          </div>
          <div class="text-right">
            <div class="text-xl font-bold" style="color: var(--text-heading)">{{ dashboardStore.asatidz.widgets.minggu_ini.laporan }}</div>
            <div class="text-xs font-medium" style="color: var(--text-muted)">Data</div>
          </div>
        </div>
      </div>

      <!-- BULAN INI -->
      <div class="widget-card group p-6 rounded-2xl flex flex-col gap-3 relative overflow-hidden transition-all duration-300 cursor-pointer">
        <div class="absolute -right-4 -bottom-4 opacity-[0.05] group-hover:scale-110 group-hover:-rotate-6 group-hover:opacity-10 transition-all duration-500">
          <span class="material-symbols-outlined text-[100px] text-purple-500">calendar_month</span>
        </div>
        <div class="flex flex-col relative z-10">
          <span class="text-sm font-bold tracking-wider" style="color: var(--text-muted)">BULAN INI</span>
          <span class="text-xs mt-0.5" style="color: var(--text-body)">{{ dashboardStore.asatidz.widgets.bulan_ini.label }}</span>
        </div>
        <div class="flex items-end justify-between mt-2 relative z-10">
          <div>
            <div class="text-3xl font-black" style="color: var(--text-heading)">{{ dashboardStore.asatidz.widgets.bulan_ini.poin }} <span class="text-sm font-medium opacity-60">Poin</span></div>
          </div>
          <div class="text-right">
            <div class="text-xl font-bold" style="color: var(--text-heading)">{{ dashboardStore.asatidz.widgets.bulan_ini.laporan }}</div>
            <div class="text-xs font-medium" style="color: var(--text-muted)">Data</div>
          </div>
        </div>
      </div>

      <!-- TAHUN INI -->
      <div class="widget-card group p-6 rounded-2xl flex flex-col gap-3 relative overflow-hidden transition-all duration-300 cursor-pointer">
        <div class="absolute -right-4 -bottom-4 opacity-[0.05] group-hover:scale-110 group-hover:-rotate-6 group-hover:opacity-10 transition-all duration-500">
          <span class="material-symbols-outlined text-[100px] text-yellow-500">emoji_events</span>
        </div>
        <div class="flex flex-col relative z-10">
          <span class="text-sm font-bold tracking-wider" style="color: var(--text-muted)">TAHUN INI</span>
          <span class="text-xs mt-0.5" style="color: var(--text-body)">{{ dashboardStore.asatidz.widgets.tahun_ini.label }}</span>
        </div>
        <div class="flex items-end justify-between mt-2 relative z-10">
          <div>
            <div class="text-3xl font-black" style="color: var(--text-heading)">{{ dashboardStore.asatidz.widgets.tahun_ini.poin }} <span class="text-sm font-medium opacity-60">Poin</span></div>
          </div>
          <div class="text-right">
            <div class="text-xl font-bold" style="color: var(--text-heading)">{{ dashboardStore.asatidz.widgets.tahun_ini.laporan }}</div>
            <div class="text-xs font-medium" style="color: var(--text-muted)">Data</div>
          </div>
        </div>
      </div>

    </div>

    <!-- MAIN CONTENT -->
    <div class="grid grid-cols-1 gap-6">
      
      <!-- CHART -->
      <div class="widget-card p-6 rounded-2xl flex flex-col gap-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-bold" style="color: var(--text-heading)">Aktivitas 7 Hari Terakhir</h3>
          <Link href="/asatidz/kegiatan-asatidz" class="text-sm font-bold text-accent hover:underline">Lihat Detail</Link>
        </div>
        <div class="flex-1 min-h-[350px] relative">
          <canvas id="asatidzChart"></canvas>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import AsatidzLayout from '../../../Layouts/AsatidzLayout.vue'
import { Link } from '@inertiajs/vue3'
import { useDashboardStore } from '../../../stores/dashboard'
import { onMounted } from 'vue'
import Chart from 'chart.js/auto'

defineOptions({ layout: AsatidzLayout })

const dashboardStore = useDashboardStore()
let chartInstance = null

onMounted(async () => {
  await dashboardStore.fetchAsatidzDashboard()
  renderChart()
})

function renderChart() {
  const ctx = document.getElementById('asatidzChart')
  if (!ctx) return
  
  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: dashboardStore.asatidz.chart.labels,
      datasets: [
        {
          label: 'Poin',
          data: dashboardStore.asatidz.chart.points,
          backgroundColor: '#2563eb',
          borderRadius: 6
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, grid: { color: 'rgba(100,116,139,0.1)' } },
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
</style>
