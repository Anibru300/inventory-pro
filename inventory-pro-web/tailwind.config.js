/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      // ========================================
      // PALETA DE COLORES - SISTEMA ERP PROFESIONAL
      // Basado en Guía Completa de Diseño UI/UX
      // ========================================
      colors: {
        // -- AZUL ELÉCTRICO (Acento Principal) --
        // Uso: Botones primarios, estados activos, progreso
        // Regla: Máximo 10-15% del área visual
        'electric': {
          DEFAULT: '#7DF9FF',      // Azul eléctrico puro - Acentos máximos
          hover: '#5EE7F0',        // Atenuado para hover sostenido
          dark: '#00E5FF',         // Adaptación modo oscuro
          muted: 'rgba(125, 249, 255, 0.1)',  // Fondos tenues
          focus: 'rgba(125, 249, 255, 0.3)',  // Anillos de focus
        },
        
        // -- GRIS PLATEADO (Base Estructural) --
        // Uso: Fondos, estructura, estados inactivos
        'silver': {
          50: '#FAFAFA',
          100: '#F5F5F5',
          200: '#E8E8E8',        // Superficies elevadas, cards
          300: '#DEDEDE',        // Headers de sección
          400: '#C4C4C4',        // Bordes, separadores
          500: '#9CA3AF',        // Líneas de referencia
          600: '#656565',        // Texto secundario
          700: '#4C4C4C',        // Texto principal, iconos
          800: '#374151',
          900: '#1F2937',        // Fondos modo oscuro
        },
        
        // -- FONDOS POR NIVEL DE ELEVACIÓN --
        'surface': {
          0: '#FFFFFF',            // Fondo principal (modo claro)
          1: '#F8F9FA',            // Área de trabajo
          2: '#E8E8E8',            // Cards, modales
          3: '#DEDEDE',            // Headers de sección
          inverted: '#1F2937',     // Tooltips oscuros
        },
        
        // -- COLORES SEMÁNTICOS DE ACCIÓN --
        // Códigos de estado consistentes con psicología del color
        'semantic': {
          // Éxito/Disponible - Verde esmeralda
          success: {
            DEFAULT: '#10B981',
            light: '#34D399',
            dark: '#059669',
            bg: '#D1FAE5',
            text: '#065F46',
          },
          // Advertencia/Atención - Ámbar
          warning: {
            DEFAULT: '#F59E0B',
            light: '#FBBF24',
            dark: '#D97706',
            bg: '#FEF3C7',
            text: '#92400E',
          },
          // Error/Crítico - Rojo coral
          error: {
            DEFAULT: '#EF4444',
            light: '#F87171',
            dark: '#DC2626',
            bg: '#FEE2E2',
            text: '#991B1B',
          },
          // Información/Neutral - Azul acero
          info: {
            DEFAULT: '#3B82F6',
            light: '#60A5FA',
            dark: '#2563EB',
            bg: '#DBEAFE',
            text: '#1E40AF',
          },
        },
        
        // -- PALETA EXTENDIDA PARA GRÁFICOS --
        'chart': {
          primary: '#7DF9FF',      // Eléctrico
          secondary: '#6366F1',    // Índigo
          tertiary: '#8B5CF6',     // Violeta
          quaternary: '#EC4899',   // Fucsia
          quintenary: '#F472B6',   // Rosa
          sextary: '#FB923C',      // Naranja
          septary: '#FBBF24',      // Ámbar
          octary: '#34D399',       // Esmeralda
          nonary: '#22D3EE',       // Cian
          neutral: '#9CA3AF',      // Gris referencia
        },
        
        // -- COLORES LEGACY (Mantener compatibilidad) --
        'cj-navy': '#0a1628',
        'cj-navy-light': '#132238',
        'cj-navy-dark': '#050d18',
        'cj-silver': '#e8e8e8',
        'cj-gold': '#c9a962',
        'cj-blue': '#1e3a5f',
        'cj-electric': '#2563eb',
        
        // -- ESTADOS GENERALES --
        'success': '#10b981',
        'warning': '#f59e0b',
        'danger': '#ef4444',
        'info': '#06b6d4',
      },
      
      // ========================================
      // TIPOGRAFÍA - SISTEMA DE FUENTES
      // ========================================
      fontFamily: {
        // Inter: Diseñada para interfaces, números tabulares
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
        // Lato: Calidez humana, legibilidad
        body: ['Lato', 'system-ui', 'sans-serif'],
        // DM Sans: Geometría contemporánea, dashboards
        display: ['DM Sans', 'system-ui', 'sans-serif'],
        // Legacy (mantener compatibilidad)
        heading: ['Montserrat', 'system-ui', 'sans-serif'],
      },
      
      fontSize: {
        // Jerarquía tipográfica profesional
        '2xs': ['0.625rem', { lineHeight: '0.875rem' }],    // 10px - Badges
        'xs': ['0.75rem', { lineHeight: '1rem' }],           // 12px - Datos tabulares densos
        'sm': ['0.875rem', { lineHeight: '1.25rem' }],       // 14px - Cuerpo de texto
        'base': ['1rem', { lineHeight: '1.5rem' }],          // 16px - Texto base
        'lg': ['1.125rem', { lineHeight: '1.75rem' }],       // 18px - Subtítulos
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],        // 20px - Títulos pequeños
        '2xl': ['1.5rem', { lineHeight: '2rem' }],           // 24px - Títulos de página
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],      // 30px - KPIs grandes
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],        // 36px - Dashboard headers
      },
      
      fontWeight: {
        light: '300',
        normal: '400',
        medium: '500',
        semibold: '600',    // Énfasis, títulos
        bold: '700',        // Valores monetarios totales
      },
      
      // ========================================
      // ESPACIADO - SISTEMA 8PX GRID
      // ========================================
      spacing: {
        // Base: múltiplos de 8px
        '0': '0',
        '0.5': '0.125rem',   // 2px - Micro ajustes
        '1': '0.25rem',      // 4px - Espaciado mínimo
        '2': '0.5rem',       // 8px - $space-xs
        '3': '0.75rem',      // 12px - Microespaciado tablas
        '4': '1rem',         // 16px - $space-sm
        '5': '1.25rem',      // 20px - Espaciado intermedio
        '6': '1.5rem',       // 24px - $space-md
        '8': '2rem',         // 32px - $space-lg (entre secciones)
        '10': '2.5rem',      // 40px
        '12': '3rem',        // 48px - $space-xl
        '16': '4rem',        // 64px - Macroespaciado
        '20': '5rem',        // 80px
        '24': '6rem',        // 96px
      },
      
      // ========================================
      // BORDES Y RADIUS
      // ========================================
      borderRadius: {
        'none': '0',         // Precisión técnica (legacy)
        'sm': '0.25rem',     // 4px - Inputs, badges
        'DEFAULT': '0.375rem', // 6px - Recomendado ERP moderno
        'md': '0.5rem',      // 8px - Cards, botones
        'lg': '0.75rem',     // 12px - Modales
        'xl': '1rem',        // 16px - Contenedores grandes
        '2xl': '1.5rem',     // 24px - Hero sections
      },
      
      // ========================================
      // SOMBRAS - SISTEMA DE ELEVACIÓN
      // ========================================
      boxShadow: {
        // Nivel 0: Plano
        'none': 'none',
        
        // Nivel 1: Sutil - Cards en layout denso
        'sm': '0 1px 3px rgba(0, 0, 0, 0.1)',
        
        // Nivel 2: Moderada - Hover, dropdowns
        'DEFAULT': '0 4px 6px rgba(0, 0, 0, 0.12)',
        'md': '0 4px 6px rgba(0, 0, 0, 0.12)',
        
        // Nivel 3: Prominente - Modales, drawers
        'lg': '0 10px 15px rgba(0, 0, 0, 0.15)',
        'xl': '0 20px 25px rgba(0, 0, 0, 0.2)',
        
        // Sombras específicas
        'card': '0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06)',
        'card-hover': '0 10px 15px rgba(0, 0, 0, 0.12), 0 4px 6px rgba(0, 0, 0, 0.08)',
        'dropdown': '0 10px 15px rgba(0, 0, 0, 0.15)',
        'modal': '0 25px 50px rgba(0, 0, 0, 0.25)',
        
        // Sombras de estado
        'focus': '0 0 0 3px rgba(125, 249, 255, 0.3)',
        'focus-error': '0 0 0 3px rgba(239, 68, 68, 0.3)',
        
        // Legacy
        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.4)',
        'glow': '0 0 30px rgba(125, 249, 255, 0.15)',
      },
      
      // ========================================
      // TRANSICIONES Y ANIMACIONES
      // ========================================
      transitionDuration: {
        '75': '75ms',
        '100': '100ms',      // Active/Pressed
        '150': '150ms',      // Hover, Release
        '200': '200ms',
        '300': '300ms',      // Toggle modo oscuro
        '500': '500ms',
      },
      
      transitionTimingFunction: {
        'ease-in': 'cubic-bezier(0.4, 0, 1, 1)',      // Salida de elementos
        'ease-out': 'cubic-bezier(0, 0, 0.2, 1)',     // Entrada de elementos
        'ease-in-out': 'cubic-bezier(0.4, 0, 0.2, 1)', // Cambios de estado
      },
      
      // ========================================
      // Z-INDEX
      // ========================================
      zIndex: {
        '0': '0',
        '10': '10',          // Sticky headers
        '20': '20',          // Dropdowns
        '30': '30',          // Tooltips
        '40': '40',          // Modales backdrop
        '50': '50',          // Modales contenido
        '60': '60',          // Toasts/notificaciones
      },
      
      // ========================================
      // DIMENSIONES DE TABLA
      // ========================================
      height: {
        'table-row': '48px',      // Estándar
        'table-row-sm': '40px',   // Compacto
        'table-row-lg': '56px',   // Confort
        'header': '64px',         // Header superior
        'sidebar': 'calc(100vh - 64px)',
      },
      
      minWidth: {
        'table-col-xs': '48px',
        'table-col-sm': '80px',
        'table-col-md': '120px',
        'table-col-lg': '160px',
        'table-col-xl': '200px',
      },
    },
  },
  plugins: [
    // Plugin para números tabulares (alineación de cantidades/precios)
    function({ addUtilities }) {
      addUtilities({
        '.tabular-nums': {
          fontVariantNumeric: 'tabular-nums',
        },
        '.lining-nums': {
          fontVariantNumeric: 'lining-nums',
        },
        '.slashed-zero': {
          fontVariantNumeric: 'slashed-zero',
        },
      });
    },
  ],
}
