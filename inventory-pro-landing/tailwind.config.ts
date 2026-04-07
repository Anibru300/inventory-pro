import type { Config } from 'tailwindcss'

const config: Config = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        // CJ Consultoría Brand Colors
        'cj-navy': '#0a1628',
        'cj-navy-light': '#111d32',
        'cj-navy-dark': '#070f1a',
        'cj-gold': '#c9a962',
        'cj-gold-light': '#d4b978',
        'cj-gold-dark': '#a88b4a',
        'cj-silver': '#e8e8e8',
        'cj-silver-dark': '#94a3b8',
        
        // Semantic
        'success': '#10b981',
        'warning': '#f59e0b',
        'danger': '#ef4444',
        'info': '#3b82f6',
        
        // Backgrounds
        'bg-primary': '#0a1628',
        'bg-secondary': '#111d32',
        'bg-tertiary': '#1a2942',
        'bg-card': '#0f172a',
      },
      fontFamily: {
        'heading': ['Montserrat', 'system-ui', 'sans-serif'],
        'body': ['Open Sans', 'system-ui', 'sans-serif'],
        'tagline': ['Playfair Display', 'serif'],
      },
      animation: {
        'gradient': 'gradient 8s linear infinite',
        'float': 'float 6s ease-in-out infinite',
        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'shimmer': 'shimmer 2s linear infinite',
      },
      keyframes: {
        gradient: {
          '0%, 100%': { backgroundPosition: '0% 50%' },
          '50%': { backgroundPosition: '100% 50%' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-20px)' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
      },
      backgroundImage: {
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'gradient-gold': 'linear-gradient(135deg, #c9a962 0%, #d4b978 50%, #a88b4a 100%)',
        'gradient-navy': 'linear-gradient(180deg, #0a1628 0%, #070f1a 100%)',
      },
    },
  },
  plugins: [],
}
export default config