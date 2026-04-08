<template>
  <span ref="counterRef">{{ displayValue }}</span>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
  target: { type: Number, required: true },
  duration: { type: Number, default: 2000 },
  prefix: { type: String, default: '' },
  suffix: { type: String, default: '' }
})

const displayValue = ref(props.prefix + '0' + props.suffix)
const counterRef = ref(null)
const hasAnimated = ref(false)

const animate = () => {
  if (hasAnimated.value) return
  hasAnimated.value = true
  
  const startTime = performance.now()
  const startValue = 0
  const endValue = Number(props.target) || 0
  
  const updateValue = (currentTime) => {
    const elapsed = currentTime - startTime
    const progress = Math.min(elapsed / props.duration, 1)
    
    // Easing function (ease-out)
    const easeOut = 1 - Math.pow(1 - progress, 3)
    const currentValue = Math.floor(startValue + (endValue - startValue) * easeOut)
    
    displayValue.value = props.prefix + currentValue.toLocaleString() + props.suffix
    
    if (progress < 1) {
      requestAnimationFrame(updateValue)
    } else {
      displayValue.value = props.prefix + endValue.toLocaleString() + props.suffix
    }
  }
  
  requestAnimationFrame(updateValue)
}

onMounted(() => {
  if (!counterRef.value) return
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animate()
        observer.disconnect()
      }
    })
  }, { threshold: 0.3 })
  
  observer.observe(counterRef.value)
})

// Watch for target changes
watch(() => props.target, (newVal) => {
  if (newVal && hasAnimated.value) {
    displayValue.value = props.prefix + Number(newVal).toLocaleString() + props.suffix
  }
})
</script>
