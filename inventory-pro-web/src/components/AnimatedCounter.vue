<template>
  <span>{{ displayValue }}</span>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
  end: { type: Number, required: true },
  duration: { type: Number, default: 2000 },
  prefix: { type: String, default: '' },
  suffix: { type: String, default: '' }
})

const displayValue = ref(props.prefix + '0' + props.suffix)

const animate = () => {
  const startTime = performance.now()
  const startValue = 0
  
  const updateValue = (currentTime) => {
    const elapsed = currentTime - startTime
    const progress = Math.min(elapsed / props.duration, 1)
    
    // Easing function (ease-out)
    const easeOut = 1 - Math.pow(1 - progress, 3)
    const currentValue = Math.floor(startValue + (props.end - startValue) * easeOut)
    
    displayValue.value = props.prefix + currentValue.toLocaleString() + props.suffix
    
    if (progress < 1) {
      requestAnimationFrame(updateValue)
    }
  }
  
  requestAnimationFrame(updateValue)
}

onMounted(() => {
  // Use Intersection Observer to start animation when visible
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animate()
        observer.disconnect()
      }
    })
  }, { threshold: 0.5 })
  
  observer.observe(document.querySelector('.text-3xl'))
})
</script>
