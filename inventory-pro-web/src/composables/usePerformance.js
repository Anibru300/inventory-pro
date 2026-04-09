import { ref, computed } from 'vue'

/**
 * Composable para optimización de rendimiento en listas
 */
export function useVirtualList(items, options = {}) {
  const { itemHeight = 60, overscan = 5 } = options
  
  const containerHeight = ref(600)
  const scrollTop = ref(0)
  
  const totalHeight = computed(() => items.value.length * itemHeight)
  
  const visibleRange = computed(() => {
    const start = Math.floor(scrollTop.value / itemHeight)
    const visibleCount = Math.ceil(containerHeight.value / itemHeight)
    
    return {
      start: Math.max(0, start - overscan),
      end: Math.min(items.value.length, start + visibleCount + overscan)
    }
  })
  
  const visibleItems = computed(() => {
    const { start, end } = visibleRange.value
    return items.value.slice(start, end).map((item, index) => ({
      ...item,
      _index: start + index,
      _style: {
        position: 'absolute',
        top: `${(start + index) * itemHeight}px`,
        height: `${itemHeight}px`,
        left: 0,
        right: 0,
      }
    }))
  })
  
  const onScroll = (event) => {
    scrollTop.value = event.target.scrollTop
  }
  
  return {
    containerHeight,
    totalHeight,
    visibleItems,
    onScroll,
    visibleRange
  }
}

/**
 * Composable para debounce de búsquedas
 */
export function useDebounce(value, delay = 300) {
  const debouncedValue = ref(value.value)
  let timeoutId = null
  
  const setValue = (newValue) => {
    if (timeoutId) {
      clearTimeout(timeoutId)
    }
    
    timeoutId = setTimeout(() => {
      debouncedValue.value = newValue
    }, delay)
  }
  
  return {
    debouncedValue,
    setValue
  }
}

/**
 * Composable para caché de datos
 */
export function useCache() {
  const cache = new Map()
  
  const get = (key) => {
    const item = cache.get(key)
    if (!item) return null
    
    if (Date.now() > item.expiresAt) {
      cache.delete(key)
      return null
    }
    
    return item.value
  }
  
  const set = (key, value, ttlSeconds = 300) => {
    cache.set(key, {
      value,
      expiresAt: Date.now() + (ttlSeconds * 1000)
    })
  }
  
  const clear = () => {
    cache.clear()
  }
  
  return {
    get,
    set,
    clear
  }
}

/**
 * Composable para lazy loading de imágenes
 */
export function useLazyImage() {
  const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target
        img.src = img.dataset.src
        img.classList.remove('lazy-image')
        img.classList.add('loaded')
        imageObserver.unobserve(img)
      }
    })
  }, {
    rootMargin: '50px'
  })
  
  const observe = (element) => {
    if (element) {
      imageObserver.observe(element)
    }
  }
  
  return {
    observe
  }
}
