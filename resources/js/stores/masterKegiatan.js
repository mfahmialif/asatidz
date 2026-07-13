import { defineStore } from 'pinia'
import api from '../axios'

export const useMasterKegiatanStore = defineStore('masterKegiatan', {
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
        const { data } = await api.get('/master-kegiatan', { params })
        this.kegiatans = data.data
        this.pagination = {
          currentPage: data.current_page,
          lastPage: data.last_page,
          total: data.total,
          from: data.from,
          to: data.to,
        }
      } catch (e) {
        this.error = e.response?.data?.message || 'Gagal memuat data kegiatan'
        console.error(e)
      } finally {
        this.loading = false
      }
    },

    async createKegiatan(payload) {
      try {
        const { data } = await api.post('/master-kegiatan', payload)
        return data
      } catch (e) {
        throw e
      }
    },

    async updateKegiatan(id, payload) {
      try {
        const { data } = await api.put(`/master-kegiatan/${id}`, payload)
        return data
      } catch (e) {
        throw e
      }
    },

    async deleteKegiatan(id) {
      try {
        await api.delete(`/master-kegiatan/${id}`)
      } catch (e) {
        throw e
      }
    }
  }
})
