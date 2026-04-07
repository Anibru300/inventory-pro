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
        // Background colors - tonos más suaves
        'bg-primary': '#0F172A',
        'bg-secondary': '#1E293B',
        'bg-tertiary': '#334155',
        'bg-elevated': '#475569',
        
        // Accent colors - Azul corporativo profesional
        'accent-primary': '#3B82F6',
        'accent-secondary': '#60A5FA',
        'accent-hover': '#2563EB',
        
        // Semantic colors
        'success': '#10B981',
        'warning': '#F59E0B',
        'danger': '#EF4444',
        'info': '#06B6D4',
        
        // Text colors
        'text-primary': '#F1F5F9',
        'text-secondary': '#CBD5E1',
        'text-tertiary': '#94A3B8',
        
        // Border colors
        'border-default': '#334155',
        'border-hover': '#475569',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-in': 'slideIn 0.3s ease-out',
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
      boxShadow: {
        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.3)',
        'glow': '0 0 20px rgba(59, 130, 246, 0.3)',
      },
    },
  },
  plugins: [],
}
