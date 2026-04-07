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
        // CJ Consultoria - Paleta Corporativa
        'cj-navy': '#0B1F3A',
        'cj-navy-light': '#152B4D',
        'cj-navy-dark': '#061428',
        
        'cj-silver': '#C0C0C0',
        'cj-silver-light': '#E8E8E8',
        'cj-silver-dark': '#8A8A8A',
        
        'cj-electric': '#2E7DE8',
        'cj-electric-light': '#5B9EF0',
        'cj-electric-dark': '#1A5FC0',
        
        'cj-gray': '#6B7280',
        'cj-gray-light': '#9CA3AF',
        'cj-gray-dark': '#4B5563',
        
        // Fondos - Más profesionales
        'bg-primary': '#0B1F3A',
        'bg-secondary': 'rgba(21, 43, 77, 0.95)',
        'bg-tertiary': 'rgba(30, 58, 95, 0.9)',
        'bg-card': 'rgba(255, 255, 255, 0.03)',
        
        // Accent colors
        'accent-primary': '#2E7DE8',
        'accent-secondary': '#5B9EF0',
        'accent-hover': '#1A5FC0',
        
        // Semantic colors
        'success': '#10B981',
        'warning': '#F59E0B',
        'danger': '#EF4444',
        'info': '#06B6D4',
        
        // Text colors
        'text-primary': '#F8FAFC',
        'text-secondary': '#C0C0C0',
        'text-tertiary': '#6B7280',
        
        // Border colors - Más sutiles
        'border-default': 'rgba(192, 192, 192, 0.1)',
        'border-hover': 'rgba(192, 192, 192, 0.2)',
      },
      fontFamily: {
        sans: ['Open Sans', 'system-ui', 'sans-serif'],
        heading: ['Montserrat', 'system-ui', 'sans-serif'],
        display: ['Playfair Display', 'serif'],
      },
      backgroundImage: {
        'gradient-radial': 'radial-gradient(ellipse at top, rgba(46, 125, 232, 0.15) 0%, transparent 50%)',
        'gradient-mesh': 'linear-gradient(135deg, rgba(11, 31, 58, 1) 0%, rgba(21, 43, 77, 0.98) 50%, rgba(11, 31, 58, 1) 100%)',
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
        'glow': '0 0 20px rgba(46, 125, 232, 0.3)',
        'silver': '0 0 30px rgba(192, 192, 192, 0.1)',
        'card': '0 8px 32px rgba(0, 0, 0, 0.3)',
      },
    },
  },
  plugins: [],
}
