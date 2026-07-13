import { defineStore } from 'pinia'
import api from '../axios'

export const useDashboardStore = defineStore('dashboard', {
  state: () => ({
    admin: {
      widgets: {
        total_asatidz: 0,
        hari_ini: { poin: 0, laporan: 0, label: '' },
        bulan_ini: { poin: 0, laporan: 0, label: '' },
        tahun_ini: { poin: 0, laporan: 0, label: '' }
      },
      leaderboard: [],
      chart: {
        labels: [],
        points: [],
        reports: []
      }
    },
    asatidz: {
      widgets: {
        hari_ini: { poin: 0, laporan: 0, label: '' },
        minggu_ini: { poin: 0, laporan: 0, label: '' },
        bulan_ini: { poin: 0, laporan: 0, label: '' },
        tahun_ini: { poin: 0, laporan: 0, label: '' }
      },
      chart: {
        labels: [],
        points: []
      }
    },
    loading: false,
    error: null
  }),
  actions: {
    async fetchAdminDashboard() {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/dashboard/admin')
        this.admin = data
      } catch (e) {
        this.error = e.response?.data?.message || 'Failed to fetch admin dashboard'
      } finally {
        this.loading = false
      }
    },
    async fetchAsatidzDashboard() {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/dashboard/asatidz')
        this.asatidz = data
      } catch (e) {
        this.error = e.response?.data?.message || 'Failed to fetch asatidz dashboard'
      } finally {
        this.loading = false
      }
    }
  }
})
