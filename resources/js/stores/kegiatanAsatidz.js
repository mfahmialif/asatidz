import { defineStore } from 'pinia'
import api from '../axios'

export const useKegiatanAsatidzStore = defineStore('kegiatanAsatidz', {
  state: () => ({
    kegiatans: [],
    pagination: {
      currentPage: 1,
      lastPage: 1,
      total: 0,
      from: 0,
      to: 0,
    },
    loading: false,
    error: null,
  }),

  actions: {
    async fetchKegiatans(params = {}) {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/kegiatan-asatidz', { params })
        this.kegiatans = data.data
        this.pagination = {
          currentPage: data.current_page,
          lastPage: data.last_page,
          total: data.total,
          from: data.from,
          to: data.to,
        }
      } catch (e) {
        this.error = e.response?.data?.message || 'Gagal memuat data'
        console.error(e)
      } finally {
        this.loading = false
      }
    },

    async createKegiatan(payload) {
      try {
        const { data } = await api.post('/kegiatan-asatidz', payload)
        return data
      } catch (e) {
        throw e
      }
    },

    async updateKegiatan(id, payload) {
      try {
        const { data } = await api.put(`/kegiatan-asatidz/${id}`, payload)
        return data
      } catch (e) {
        throw e
      }
    },

    async deleteKegiatan(id) {
      try {
        await api.delete(`/kegiatan-asatidz/${id}`)
      } catch (e) {
        throw e
      }
    }
  }
})
