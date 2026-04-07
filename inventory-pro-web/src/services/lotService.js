import apiClient from './api'

export const lotService = {
  // CRUD Operations
  getAll: (params = {}) => apiClient.get('/product-lots', { params }),
  getById: (id) => apiClient.get(`/product-lots/${id}`),
  create: (data) => apiClient.post('/product-lots', data),
  update: (id, data) => apiClient.put(`/product-lots/${id}`, data),
  delete: (id) => apiClient.delete(`/product-lots/${id}`),
  
  // Special endpoints
  getAvailable: (params) => apiClient.get('/product-lots/available/list', { params }),
  getExpiring: (days = 30) => apiClient.get('/product-lots/expiring/list', { params: { days } }),
  getStats: () => apiClient.get('/product-lots/stats/overview'),
  
  // Status labels
  getStatusLabel: (status) => {
    const labels = {
      active: 'Activo',
      depleted: 'Agotado',
      expired: 'Vencido',
      quarantine: 'Cuarentena',
    }
    return labels[status] || status
  },
  
  getStatusColor: (status) => {
    const colors = {
      active: 'green',
      depleted: 'gray',
      expired: 'red',
      quarantine: 'yellow',
    }
    return colors[status] || 'gray'
  },
}
