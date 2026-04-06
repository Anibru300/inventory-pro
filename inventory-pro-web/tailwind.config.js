/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Background colors
        'bg-primary': '#0B0F19',
        'bg-secondary': '#151B2B',
        'bg-tertiary': '#1E2538',
        'bg-elevated': '#252D42',
        
        // Accent colors
        'accent-primary': '#6366F1',
        'accent-secondary': '#8B5CF6',
        
        // Semantic colors
        'success': '#10B981',
        'warning': '#F59E0B',
        'danger': '#EF4444',
        'info': '#3B82F6',
        
        // Text colors
        'text-primary': '#F8FAFC',
        'text-secondary': '#94A3B8',
        'text-tertiary': '#64748B',
        
        // Border colors
        'border-default': '#2D3748',
        'border-hover': '#4A5568',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      animation: {
        'fade-in': 'fadeIn 0.2s ease-in-out',
        'slide-in': 'slideIn 0.2s ease-out',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideIn: {
          '0%': { transform: 'translateY(-10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [],
}