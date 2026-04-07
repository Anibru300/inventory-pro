'use client'

import { useEffect, useRef, useState } from 'react'

const features = [
  {
    icon: (
      <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
      </svg>
    ),
    title: 'Vales PDF Automáticos',
    description: 'Cada movimiento genera automáticamente un vale profesional en PDF con folio único, listo para imprimir o compartir.',
    highlight: 'Nuevo',
  },
  {
    icon: (
      <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
      </svg>
    ),
    title: 'Importación Masiva',
    description: 'Sube cientos de productos en segundos desde Excel o CSV. Categorías y almacenes se crean automáticamente.',
  },
  {
    icon: (
      <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
      </svg>
    ),
    title: 'Reportes Avanzados',
    description: 'Análisis de valoración de inventario, movimientos por período, productos más vendidos y alertas de stock bajo.',
  },
  {
    icon: (
      <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
      </svg>
    ),
    title: 'Multi-Almacén',
    description: 'Gestiona múltiples ubicaciones, transferencias entre almacenes y stock consolidado en tiempo real.',
  },
  {
    icon: (
      <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
      </svg>
    ),
    title: 'Seguridad Empresarial',
    description: 'Cada cliente tiene sus datos aislados (multi-tenant). Autenticación segura con tokens y control de acceso.',
  },
  {
    icon: (
      <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
      </svg>
    ),
    title: 'Diseño Responsivo',
    description: 'Accede desde cualquier dispositivo: computadora, tablet o smartphone. Interfaz moderna y fácil de usar.',
  },
]

export default function Features() {
  const [visibleItems, setVisibleItems] = useState<number[]>([])
  const sectionRef = useRef<HTMLElement>(null)

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const index = parseInt(entry.target.getAttribute('data-index') || '0')
            setVisibleItems((prev) => [...new Set([...prev, index])])
          }
        })
      },
      { threshold: 0.2 }
    )

    const items = sectionRef.current?.querySelectorAll('.feature-item')
    items?.forEach((item) => observer.observe(item))

    return () => observer.disconnect()
  }, [])

  return (
    <section id="funciones" ref={sectionRef} className="relative py-32 bg-cj-navy">
      {/* Background */}
      <div className="absolute inset-0">
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-cj-gold/5 rounded-full blur-3xl" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="text-center max-w-3xl mx-auto mb-20">
          <span className="inline-block px-4 py-1.5 bg-cj-gold/10 border border-cj-gold/20 rounded-full text-sm text-cj-gold font-medium mb-6">
            Funcionalidades
          </span>
          <h2 className="text-4xl md:text-5xl font-heading font-bold text-white mb-6">
            Todo lo que necesitas para
            <span className="text-cj-gold"> gestionar tu inventario</span>
          </h2>
          <p className="text-lg text-cj-silver-dark">
            Diseñado por expertos en logística y consultoría empresarial. 
            Cada función resuelve un problema real de tu negocio.
          </p>
        </div>

        {/* Features Grid */}
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          {features.map((feature, index) => (
            <div
              key={feature.title}
              data-index={index}
              className={`feature-item group relative p-8 bg-cj-navy-light border border-white/5 rounded-2xl transition-all duration-700 hover:border-cj-gold/30 hover:bg-cj-navy-light/80 ${
                visibleItems.includes(index)
                  ? 'opacity-100 translate-y-0'
                  : 'opacity-0 translate-y-10'
              }`}
              style={{ transitionDelay: `${index * 100}ms` }}
            >
              {/* Hover Glow */}
              <div className="absolute inset-0 bg-gradient-to-br from-cj-gold/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity" />
              
              <div className="relative">
                {/* Icon */}
                <div className="w-14 h-14 bg-cj-gold/10 border border-cj-gold/20 rounded-xl flex items-center justify-center text-cj-gold mb-6 group-hover:scale-110 group-hover:bg-cj-gold/20 transition-all">
                  {feature.icon}
                </div>

                {/* Content */}
                <div className="flex items-start justify-between mb-3">
                  <h3 className="text-xl font-heading font-semibold text-white">
                    {feature.title}
                  </h3>
                  {feature.highlight && (
                    <span className="px-2 py-1 bg-cj-gold text-cj-navy text-xs font-bold rounded">
                      {feature.highlight}
                    </span>
                  )}
                </div>
                <p className="text-cj-silver-dark leading-relaxed">
                  {feature.description}
                </p>
              </div>
            </div>
          ))}
        </div>

        {/* Bottom CTA */}
        <div className="mt-20 text-center">
          <p className="text-cj-silver-dark mb-6">
            ¿Necesitas una función específica para tu negocio?
          </p>
          <a
            href="mailto:soporte@cjconsultoria.com"
            className="inline-flex items-center gap-2 px-6 py-3 border border-cj-gold/30 text-cj-gold rounded-xl hover:bg-cj-gold/10 transition-colors"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Contáctanos
          </a>
        </div>
      </div>
    </section>
  )
}