import apiClient from './api'

export const transferService = {
  // CRUD Operations
  getAll: (params = {}) => apiClient.get('/warehouse-transfers', { params }),
  getById: (id) => apiClient.get(`/warehouse-transfers/${id}`),
  create: (data) => apiClient.post('/warehouse-transfers', data),
  
  // Actions
  send: (id) => apiClient.post(`/warehouse-transfers/${id}/send`),
  receive: (id, data) => apiClient.post(`/warehouse-transfers/${id}/receive`, data),
  cancel: (id, reason) => apiClient.post(`/warehouse-transfers/${id}/cancel`, { reason }),
  
  // Stats
  getStats: () => apiClient.get('/warehouse-transfers/stats/overview'),
  
  // Status labels
  getStatusLabel: (status) => {
    const labels = {
      pending: 'Pendiente',
      preparing: 'En Preparación',
      in_transit: 'En Tránsito',
      received: 'Recibida',
      partially_received: 'Parcialmente Recibida',
      cancelled: 'Cancelada',
      rejected: 'Rechazada',
    }
    return labels[status] || status
  },
  
  getStatusColor: (status) => {
    const colors = {
      pending: 'yellow',
      preparing: 'blue',
      in_transit: 'indigo',
      received: 'green',
      partially_received: 'orange',
      cancelled: 'gray',
      rejected: 'red',
    }
    return colors[status] || 'gray'
  },
}
