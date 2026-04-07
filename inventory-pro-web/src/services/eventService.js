import apiClient from './api'

export const eventService = {
  // CRUD Operations
  getAll: (params = {}) => apiClient.get('/inventory-events', { params }),
  getById: (id) => apiClient.get(`/inventory-events/${id}`),
  delete: (id) => apiClient.delete(`/inventory-events/${id}`),
  
  // Actions
  markAsProcessed: (id) => apiClient.post(`/inventory-events/${id}/process`),
  markAsNotified: (id) => apiClient.post(`/inventory-events/${id}/notify`),
  
  // Special endpoints
  getUnread: () => apiClient.get('/inventory-events/unread/list'),
  getStats: () => apiClient.get('/inventory-events/stats/overview'),
  
  // Event type labels
  getEventTypeLabel: (type) => {
    const labels = {
      stock_low: 'Stock Bajo',
      stock_out: 'Sin Stock',
      transfer_completed: 'Transferencia Completada',
      lot_expiring: 'Lote por Vencer',
      lot_expired: 'Lote Vencido',
      transfer_in_transit: 'Transferencia en Tránsito',
      transfer_received: 'Transferencia Recibida',
      adjustment_created: 'Ajuste Creado',
    }
    return labels[type] || type
  },
  
  getPriorityLabel: (priority) => {
    const labels = {
      low: 'Baja',
      medium: 'Media',
      high: 'Alta',
      critical: 'Crítica',
    }
    return labels[priority] || priority
  },
  
  getPriorityColor: (priority) => {
    const colors = {
      low: 'gray',
      medium: 'blue',
      high: 'orange',
      critical: 'red',
    }
    return colors[priority] || 'gray'
  },
}
