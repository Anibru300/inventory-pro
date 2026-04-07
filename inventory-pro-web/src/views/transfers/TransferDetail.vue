<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-white">Detalle de Transferencia</h1>
        <p class="text-cj-silver mt-1">{{ transfer?.transfer_number }}</p>
      </div>
      <router-link
        to="/transfers"
        class="px-4 py-2 border border-cj-gold/50 text-cj-gold rounded-lg hover:bg-cj-gold/10 transition-colors"
      >
        ← Volver
      </router-link>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-12">
      <div class="text-cj-gold text-xl">Cargando...</div>
    </div>

    <!-- Content -->
    <div v-else-if="transfer" class="space-y-6">
      <!-- Info General -->
      <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-cj-silver text-sm">Almacén Origen</label>
            <p class="text-white font-medium">{{ transfer.source_warehouse?.name }}</p>
          </div>
          <div>
            <label class="text-cj-silver text-sm">Almacén Destino</label>
            <p class="text-white font-medium">{{ transfer.destination_warehouse?.name }}</p>
          </div>
          <div>
            <label class="text-cj-silver text-sm">Fecha de Transferencia</label>
            <p class="text-white">{{ formatDate(transfer.transfer_date) }}</p>
          </div>
          <div>
            <label class="text-cj-silver text-sm">Estado</label>
            <span :class="getStatusBadgeClass(transfer.status)">
              {{ getStatusLabel(transfer.status) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Items -->
      <div class="bg-cj-navy/30 border border-cj-gold/20 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-cj-gold mb-4">Productos</h2>
        <table class="w-full">
          <thead class="bg-cj-navy/50">
            <tr>
              <th class="text-left text-cj-gold font-medium p-3">Producto</th>
              <th class="text-right text-cj-gold font-medium p-3">Cantidad</th>
              <th class="text-right text-cj-gold font-medium p-3">Costo Unit.</th>
              <th class="text-right text-cj-gold font-medium p-3">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in transfer.items" :key="item.id" class="border-t border-cj-gold/10">
              <td class="p-3 text-white">{{ item.product?.name }}</td>
              <td class="p-3 text-right text-cj-silver">{{ item.quantity_requested }}</td>
              <td class="p-3 text-right text-cj-silver">${{ formatNumber(item.unit_cost) }}</td>
              <td class="p-3 text-right text-cj-gold">${{ formatNumber(item.total_cost) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Actions -->
      <div v-if="canSend || canReceive" class="flex gap-4">
        <button
          v-if="canSend"
          @click="sendTransfer"
          :disabled="isProcessing"
          class="bg-cj-gold text-cj-navy px-6 py-2 rounded-lg font-medium hover:bg-cj-gold/90 disabled:opacity-50"
        >
          {{ isProcessing ? 'Enviando...' : 'Enviar Transferencia' }}
        </button>
        <button
          v-if="canReceive"
          @click="showReceiveModal = true"
          class="bg-green-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-green-700"
        >
          Recibir Transferencia
        </button>
      </div>
    </div>

    <!-- Error -->
    <div v-else class="text-center py-12 text-red-400">
      Transferencia no encontrada
    </div>

    <!-- Receive Modal -->
    <div v-if="showReceiveModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-cj-navy border border-cj-gold/30 rounded-lg p-6 max-w-2xl w-full mx-4">
        <h3 class="text-lg font-semibold text-cj-gold mb-4">Recibir Transferencia</h3>
        
        <div v-for="item in transfer?.items" :key="item.id" class="mb-4 p-4 bg-cj-navy/50 rounded">
          <p class="text-white font-medium">{{ item.product?.name }}</p>
          <div class="grid grid-cols-2 gap-4 mt-2">
            <div>
              <label class="text-cj-silver text-sm">Cantidad Enviada</label>
              <p class="text-white">{{ item.quantity_sent }}</p>
            </div>
            <div>
              <label class="text-cj-silver text-sm">Cantidad Recibida</label>
              <input
                v-model.number="receiveData[item.id].quantity_received"
                type="number"
                class="w-full px-3 py-1 bg-cj-navy border border-cj-gold/30 rounded text-white"
              />
            </div>
          </div>
        </div>

        <div class="flex gap-4 mt-6">
          <button
            @click="receiveTransfer"
            :disabled="isProcessing"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 disabled:opacity-50"
          >
            {{ isProcessing ? 'Procesando...' : 'Confirmar Recepción' }}
          </button>
          <button
            @click="showReceiveModal = false"
            class="px-4 py-2 border border-cj-gold/50 text-cj-gold rounded-lg"
          >
            Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { transferService } from '@/services/transferService'

const route = useRoute()
const router = useRouter()

const transfer = ref(null)
const loading = ref(true)
const isProcessing = ref(false)
const showReceiveModal = ref(false)
const receiveData = ref({})

const canSend = computed(() => transfer.value?.status === 'pending')
const canReceive = computed(() => transfer.value?.status === 'in_transit')

const fetchTransfer = async () => {
  try {
    const response = await transferService.getById(route.params.id)
    transfer.value = response.data
    
    // Initialize receive data
    transfer.value.items.forEach(item => {
      receiveData.value[item.id] = {
        item_id: item.id,
        quantity_received: item.quantity_sent || item.quantity_requested,
        status: 'received'
      }
    })
  } catch (error) {
    console.error('Error fetching transfer:', error)
  } finally {
    loading.value = false
  }
}

const sendTransfer = async () => {
  isProcessing.value = true
  try {
    await transferService.send(transfer.value.id)
    await fetchTransfer()
  } catch (error) {
    console.error('Error sending transfer:', error)
    alert('Error al enviar la transferencia')
  } finally {
    isProcessing.value = false
  }
}

const receiveTransfer = async () => {
  isProcessing.value = true
  try {
    const items = Object.values(receiveData.value)
    await transferService.receive(transfer.value.id, { items })
    showReceiveModal.value = false
    await fetchTransfer()
  } catch (error) {
    console.error('Error receiving transfer:', error)
    alert('Error al recibir la transferencia')
  } finally {
    isProcessing.value = false
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES')
}

const formatNumber = (num) => {
  return Number(num || 0).toLocaleString('es-ES', { minimumFractionDigits: 2 })
}

const getStatusLabel = (status) => {
  return transferService.getStatusLabel(status)
}

const getStatusBadgeClass = (status) => {
  const color = transferService.getStatusColor(status)
  const classes = {
    yellow: 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
    blue: 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    indigo: 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30',
    green: 'bg-green-500/20 text-green-400 border border-green-500/30',
    orange: 'bg-orange-500/20 text-orange-400 border border-orange-500/30',
    gray: 'bg-gray-500/20 text-gray-400 border border-gray-500/30',
    red: 'bg-red-500/20 text-red-400 border border-red-500/30',
  }
  return `px-3 py-1 rounded-full text-sm font-medium ${classes[color] || classes.gray}`
}

onMounted(fetchTransfer)
</script>
