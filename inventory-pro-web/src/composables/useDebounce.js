import { ref } from 'vue'

export function useDebounce() {
  const timeout = ref(null)

  const debounce = (fn, delay = 300) => {
    return (...args) => {
      clearTimeout(timeout.value)
      timeout.value = setTimeout(() => fn(...args), delay)
    }
  }

  return { debounce }
}
