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
        // CJ Consultoria - Paleta Ejecutiva Premium
        'cj-navy': '#0a1628',
        'cj-navy-light': '#132238',
        'cj-navy-dark': '#050d18',
        
        // Plata premium
        'cj-silver': '#e8e8e8',
        'cj-silver-light': '#f5f5f5',
        'cj-silver-dark': '#a0a0a0',
        'cj-silver-dim': 'rgba(232, 232, 232, 0.1)',
        
        // Dorado ejecutivo (sutil)
        'cj-gold': '#c9a962',
        'cj-gold-light': '#d4b978',
        'cj-gold-dark': '#a88b4a',
        'cj-gold-dim': 'rgba(201, 169, 98, 0.15)',
        
        // Azul corporativo
        'cj-blue': '#1e3a5f',
        'cj-blue-light': '#2a4a73',
        'cj-blue-accent': '#3b5998',
        
        // Eléctrico para acentos
        'cj-electric': '#2563eb',
        'cj-electric-light': '#3b82f6',
        'cj-electric-dark': '#1d4ed8',
        
        // Fondos
        'bg-primary': '#0a1628',
        'bg-secondary': 'rgba(19, 34, 56, 0.98)',
        'bg-tertiary': 'rgba(30, 58, 95, 0.6)',
        'bg-card': 'rgba(255, 255, 255, 0.02)',
        'bg-hover': 'rgba(201, 169, 98, 0.05)',
        
        // Bordes elegantes
        'border-subtle': 'rgba(232, 232, 232, 0.06)',
        'border-silver': 'rgba(232, 232, 232, 0.12)',
        'border-gold': 'rgba(201, 169, 98, 0.3)',
        
        // Texto
        'text-primary': '#f8fafc',
        'text-secondary': '#e8e8e8',
        'text-muted': '#94a3b8',
        
        // Estados
        'success': '#10b981',
        'warning': '#f59e0b',
        'danger': '#ef4444',
        'info': '#06b6d4',
      },
      fontFamily: {
        sans: ['Open Sans', 'system-ui', 'sans-serif'],
        heading: ['Montserrat', 'system-ui', 'sans-serif'],
        display: ['Playfair Display', 'serif'],
      },
      backgroundImage: {
        'gradient-radial': 'radial-gradient(ellipse at top right, rgba(201, 169, 98, 0.08) 0%, transparent 50%)',
        'gradient-mesh': 'linear-gradient(135deg, #0a1628 0%, #0f172a 50%, #0a1628 100%)',
        'gradient-gold': 'linear-gradient(135deg, rgba(201, 169, 98, 0.1) 0%, transparent 50%)',
        'gradient-silver': 'linear-gradient(180deg, rgba(232, 232, 232, 0.03) 0%, transparent 100%)',
      },
      boxShadow: {
        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.4)',
        'glow': '0 0 30px rgba(201, 169, 98, 0.15)',
        'silver': '0 0 20px rgba(232, 232, 232, 0.08)',
        'card': '0 8px 32px rgba(0, 0, 0, 0.4)',
        'gold': '0 4px 20px rgba(201, 169, 98, 0.2)',
      },
    },
  },
  plugins: [],
}
