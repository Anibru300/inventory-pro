<template>
  <div class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full overflow-hidden">
      <!-- Header -->
      <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800">Escanear Código de Barras</h3>
        <button @click="$emit('close')" class="p-2 text-slate-400 hover:text-slate-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Scanner -->
      <div class="p-4">
        <!-- Camera Select -->
        <div v-if="cameras.length > 1" class="mb-4">
          <label class="block text-sm font-medium text-slate-700 mb-2">Cámara</label>
          <select v-model="selectedCamera" @change="startScanner"
            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500">
            <option v-for="camera in cameras" :key="camera.id" :value="camera.id">
              {{ camera.label }}
            </option>
          </select>
        </div>

        <!-- Video Container -->
        <div class="relative bg-black rounded-xl overflow-hidden aspect-video">
          <video ref="videoRef" class="w-full h-full object-cover" autoplay playsinline muted></video>
          
          <!-- Scanning Line Animation -->
          <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-blue-500/50 to-transparent animate-scan"></div>
            
            <!-- Corner Markers -->
            <div class="absolute top-4 left-4 w-8 h-8 border-l-4 border-t-4 border-blue-500 rounded-tl-lg"></div>
            <div class="absolute top-4 right-4 w-8 h-8 border-r-4 border-t-4 border-blue-500 rounded-tr-lg"></div>
            <div class="absolute bottom-4 left-4 w-8 h-8 border-l-4 border-b-4 border-blue-500 rounded-bl-lg"></div>
            <div class="absolute bottom-4 right-4 w-8 h-8 border-r-4 border-b-4 border-blue-500 rounded-br-lg"></div>
          </div>

          <!-- Loading State -->
          <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center bg-black/50">
            <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
          </div>
        </div>

        <!-- Manual Entry -->
        <div class="mt-4">
          <p class="text-center text-sm text-slate-500 mb-2">- o ingresa manualmente -</p>
          <div class="flex gap-2">
            <input 
              v-model="manualCode"
              type="text" 
              placeholder="Código de barras"
              class="flex-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500"
              @keyup.enter="submitManualCode"
            />
            <button @click="submitManualCode" 
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
              Buscar
            </button>
          </div>
        </div>

        <!-- Instructions -->
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
          <p class="text-sm text-blue-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Centra el código de barras en el recuadro
          </p>
        </div>

        <!-- Detected Codes History -->
        <div v-if="detectedCodes.length > 0" class="mt-4">
          <p class="text-sm font-medium text-slate-700 mb-2">Últimos detectados:</p>
          <div class="flex flex-wrap gap-2">
            <button v-for="code in detectedCodes" :key="code" 
              @click="selectCode(code)"
              class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm transition-colors">
              {{ code }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const emit = defineEmits(['close', 'detected'])

const videoRef = ref(null)
const isLoading = ref(true)
const manualCode = ref('')
const cameras = ref([])
const selectedCamera = ref('')
const detectedCodes = ref([])

let stream = null
let animationId = null
let lastScanTime = 0
const SCAN_COOLDOWN = 2000 // 2 seconds between scans

onMounted(async () => {
  await getCameras()
  await startScanner()
})

onUnmounted(() => {
  stopScanner()
})

async function getCameras() {
  try {
    const devices = await navigator.mediaDevices.enumerateDevices()
    cameras.value = devices.filter(d => d.kind === 'videoinput')
    if (cameras.value.length > 0) {
      selectedCamera.value = cameras.value[0].id
    }
  } catch (err) {
    console.error('Error getting cameras:', err)
  }
}

async function startScanner() {
  isLoading.value = true
  stopScanner()

  try {
    const constraints = {
      video: {
        facingMode: 'environment', // Use back camera on mobile
        width: { ideal: 1280 },
        height: { ideal: 720 }
      }
    }

    if (selectedCamera.value) {
      constraints.video.deviceId = { exact: selectedCamera.value }
    }

    stream = await navigator.mediaDevices.getUserMedia(constraints)
    
    if (videoRef.value) {
      videoRef.value.srcObject = stream
      videoRef.value.play()
      isLoading.value = false
      startDetection()
    }
  } catch (err) {
    console.error('Error starting scanner:', err)
    isLoading.value = false
  }
}

function stopScanner() {
  if (stream) {
    stream.getTracks().forEach(track => track.stop())
    stream = null
  }
  if (animationId) {
    cancelAnimationFrame(animationId)
    animationId = null
  }
}

function startDetection() {
  // Simple barcode detection using frame analysis
  // For production, use a library like @zxing/library or quagga
  
  const detect = () => {
    const now = Date.now()
    if (now - lastScanTime < SCAN_COOLDOWN) {
      animationId = requestAnimationFrame(detect)
      return
    }

    // Simulate detection for demo (in production, use actual barcode detection)
    // This is where you'd integrate with a barcode library
    
    animationId = requestAnimationFrame(detect)
  }
  
  animationId = requestAnimationFrame(detect)
}

function submitManualCode() {
  if (manualCode.value.trim()) {
    addDetectedCode(manualCode.value.trim())
  }
}

function selectCode(code) {
  emit('detected', code)
}

function addDetectedCode(code) {
  lastScanTime = Date.now()
  if (!detectedCodes.value.includes(code)) {
    detectedCodes.value.unshift(code)
    if (detectedCodes.value.length > 5) {
      detectedCodes.value.pop()
    }
  }
  emit('detected', code)
}

// For demo purposes - in production, integrate with actual barcode detection library
// This simulates a barcode detection after 3 seconds for testing
defineExpose({ simulateDetection: (code) => addDetectedCode(code) })
</script>

<style scoped>
@keyframes scan {
  0% { transform: translateY(0); opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { transform: translateY(100vh); opacity: 0; }
}

.animate-scan {
  animation: scan 2s linear infinite;
}
</style>
