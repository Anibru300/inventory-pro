import apiClient from './api'

export const warehouseService = {
  getAll: (params = {}) => apiClient.get('/warehouses', { params }),
  getById: (id) => apiClient.get(`/warehouses/${id}`),
  create: (data) => apiClient.post('/warehouses', data),
  update: (id, data) => apiClient.put(`/warehouses/${id}`, data),
  delete: (id) => apiClient.delete(`/warehouses/${id}`),
}
