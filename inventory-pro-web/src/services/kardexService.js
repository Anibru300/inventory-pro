import apiClient from './api'

export const kardexService = {
  // Kardex report
  getKardex: (params) => apiClient.get('/kardex', { params }),
  
  // Valuation
  getValuation: (params = {}) => apiClient.get('/kardex/valuation', { params }),
  
  // Movements by type
  getMovementsByType: (params) => apiClient.get('/kardex/movements-by-type', { params }),
  
  // Inventory turnover
  getInventoryTurnover: (params) => apiClient.get('/kardex/inventory-turnover', { params }),
  
  // Movement type labels
  getMovementTypeLabel: (type) => {
    const labels = {
      entrada_compra: 'Compra',
      entrada_devolucion_cliente: 'Devolución Cliente',
      entrada_ajuste: 'Ajuste Positivo',
      entrada_transferencia: 'Transferencia Entrada',
      salida_venta: 'Venta',
      salida_devolucion_proveedor: 'Devolución Proveedor',
      salida_ajuste: 'Ajuste Negativo',
      salida_transferencia: 'Transferencia Salida',
      salida_merma: 'Merma',
    }
    return labels[type] || type
  },
}
