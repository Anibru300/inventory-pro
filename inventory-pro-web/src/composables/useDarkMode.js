import { ref, watch, onMounted } from 'vue'

const isDark = ref(false)

export function useDarkMode() {
  const initDarkMode = () => {
    // Check localStorage first
    const savedMode = localStorage.getItem('darkMode')
    if (savedMode !== null) {
      isDark.value = savedMode === 'true'
    } else {
      // Check system preference
      isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    applyDarkMode()
  }

  const applyDarkMode = () => {
    const html = document.documentElement
    const body = document.body
    
    if (isDark.value) {
      html.classList.add('dark')
      body.classList.add('dark')
      body.classList.remove('bg-slate-50')
      body.classList.add('bg-slate-900')
    } else {
      html.classList.remove('dark')
      body.classList.remove('dark')
      body.classList.remove('bg-slate-900')
      body.classList.add('bg-slate-50')
    }
    
    localStorage.setItem('darkMode', isDark.value)
  }

  const toggleDarkMode = () => {
    isDark.value = !isDark.value
    applyDarkMode()
  }

  const setDarkMode = (value) => {
    isDark.value = value
    applyDarkMode()
  }

  onMounted(() => {
    initDarkMode()
  })

  return {
    isDark,
    toggleDarkMode,
    setDarkMode,
    initDarkMode
  }
}
