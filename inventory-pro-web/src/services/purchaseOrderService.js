import apiClient from './api'

export const purchaseOrderService = {
  // CRUD
  getAll(params = {}) {
    return apiClient.get('/purchase-orders', { params })
  },

  getById(id) {
    return apiClient.get(`/purchase-orders/${id}`)
  },

  create(data) {
    return apiClient.post('/purchase-orders', data)
  },

  update(id, data) {
    return apiClient.put(`/purchase-orders/${id}`, data)
  },

  delete(id) {
    return apiClient.delete(`/purchase-orders/${id}`)
  },

  // Actions
  send(id) {
    return apiClient.post(`/purchase-orders/${id}/send`)
  },

  receive(id, data) {
    return apiClient.post(`/purchase-orders/${id}/receive`, data)
  },

  cancel(id, reason) {
    return apiClient.post(`/purchase-orders/${id}/cancel`, { reason })
  },

  // Stats
  getStats() {
    return apiClient.get('/purchase-orders-stats/overview')
  },
}
