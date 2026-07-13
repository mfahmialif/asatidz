import { defineStore } from 'pinia'
import api from '../axios'

export const useAsatidzStore = defineStore('asatidz', {
  state: () => ({
    asatidz: [],
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
    async fetchAsatidz(params = {}) {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/asatidz', { params })
        this.asatidz = data.data
        this.pagination = {
          currentPage: data.current_page,
          lastPage: data.last_page,
          total: data.total,
          from: data.from,
          to: data.to,
        }
      } catch (e) {
        this.error = e.response?.data?.message || 'Gagal memuat data Asatidz'
        console.error(e)
      } finally {
        this.loading = false
      }
    },

    async createAsatidz(payload) {
      try {
        const { data } = await api.post('/asatidz', payload)
        return data
      } catch (e) {
        throw e
      }
    },

    async updateAsatidz(id, payload) {
      try {
        const { data } = await api.put(`/asatidz/${id}`, payload)
        return data
      } catch (e) {
        throw e
      }
    },

    async deleteAsatidz(id) {
      try {
        await api.delete(`/asatidz/${id}`)
      } catch (e) {
        throw e
      }
    }
  }
})
