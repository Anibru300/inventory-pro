'use client'

import { useEffect, useState } from 'react'

export default function Hero() {
  const [isVisible, setIsVisible] = useState(false)

  useEffect(() => {
    setIsVisible(true)
  }, [])

  return (
    <section id="inicio" className="relative min-h-screen flex items-center overflow-hidden">
      {/* Background Effects */}
      <div className="absolute inset-0 bg-cj-navy">
        {/* Gradient Orbs */}
        <div className="absolute top-20 left-10 w-96 h-96 bg-cj-gold/10 rounded-full blur-3xl animate-pulse-slow" />
        <div className="absolute bottom-20 right-10 w-[500px] h-[500px] bg-cj-gold/5 rounded-full blur-3xl animate-pulse-slow" style={{ animationDelay: '2s' }} />
        
        {/* Grid Pattern */}
        <div 
          className="absolute inset-0 opacity-[0.03]"
          style={{
            backgroundImage: `linear-gradient(rgba(201, 169, 98, 0.5) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(201, 169, 98, 0.5) 1px, transparent 1px)`,
            backgroundSize: '60px 60px'
          }}
        />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
        <div className="grid lg:grid-cols-2 gap-12 items-center">
          {/* Content */}
          <div 
            className={`space-y-8 transition-all duration-1000 ${
              isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
            }`}
          >
            {/* Badge */}
            <div className="inline-flex items-center gap-2 px-4 py-2 bg-cj-gold/10 border border-cj-gold/20 rounded-full">
              <span className="w-2 h-2 bg-cj-gold rounded-full animate-pulse" />
              <span className="text-sm text-cj-gold font-medium">Nuevo: Vales PDF Automáticos</span>
            </div>

            {/* Title */}
            <div className="space-y-4">
              <h1 className="text-5xl md:text-6xl lg:text-7xl font-heading font-bold leading-tight">
                <span className="text-white">Controla tu</span>
                <br />
                <span className="bg-gradient-to-r from-cj-gold via-cj-gold-light to-cj-gold bg-clip-text text-transparent">
                  Inventario
                </span>
                <br />
                <span className="text-white">como un Pro</span>
              </h1>
              <p className="font-tagline italic text-xl md:text-2xl text-cj-silver-dark">
                "Transformamos procesos en resultados sostenibles"
              </p>
            </div>

            {/* Description */}
            <p className="text-lg text-cj-silver-dark max-w-xl leading-relaxed">
              Sistema profesional de gestión de inventarios diseñado para PYMES. 
              Controla stock, genera vales automáticos, importa desde Excel y 
              toma decisiones con reportes en tiempo real.
            </p>

            {/* CTA Buttons */}
            <div className="flex flex-col sm:flex-row gap-4">
              <a
                href="https://inventory-pro-9ef8.onrender.com/#/register"
                className="group relative px-8 py-4 bg-gradient-to-r from-cj-gold to-cj-gold-dark text-cj-navy font-heading font-bold rounded-xl overflow-hidden transition-all hover:shadow-2xl hover:shadow-cj-gold/30 hover:-translate-y-1"
              >
                <span className="relative z-10 flex items-center justify-center gap-2">
                  Comenzar Gratis
                  <svg className="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                  </svg>
                </span>
                <div className="absolute inset-0 bg-gradient-to-r from-cj-gold-light to-cj-gold opacity-0 group-hover:opacity-100 transition-opacity" />
              </a>
              <a
                href="#funciones"
                className="px-8 py-4 bg-white/5 border border-white/10 text-white font-heading font-semibold rounded-xl hover:bg-white/10 hover:border-cj-gold/30 transition-all flex items-center justify-center gap-2"
              >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Ver Demo
              </a>
            </div>

            {/* Trust Indicators */}
            <div className="flex items-center gap-6 pt-4">
              <div className="flex -space-x-3">
                {[1, 2, 3, 4].map((i) => (
                  <div
                    key={i}
                    className="w-10 h-10 rounded-full bg-gradient-to-br from-cj-gold/30 to-cj-gold/10 border-2 border-cj-navy flex items-center justify-center text-xs font-bold text-cj-gold"
                  >
                    {String.fromCharCode(64 + i)}
                  </div>
                ))}
              </div>
              <div>
                <div className="flex items-center gap-1 text-cj-gold">
                  {[1, 2, 3, 4, 5].map((i) => (
                    <svg key={i} className="w-4 h-4 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  ))}
                </div>
                <p className="text-sm text-cj-silver-dark">+100 empresas confían en nosotros</p>
              </div>
            </div>
          </div>

          {/* Dashboard Preview */}
          <div 
            className={`relative transition-all duration-1000 delay-300 ${
              isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
            }`}
          >
            <div className="relative">
              {/* Glow Effect */}
              <div className="absolute -inset-4 bg-gradient-to-r from-cj-gold/20 to-cj-gold/5 rounded-2xl blur-2xl" />
              
              {/* Dashboard Mockup */}
              <div className="relative bg-cj-navy-light border border-cj-gold/20 rounded-2xl shadow-2xl overflow-hidden">
                {/* Header */}
                <div className="flex items-center gap-2 px-4 py-3 bg-cj-navy border-b border-white/5">
                  <div className="flex gap-1.5">
                    <div className="w-3 h-3 rounded-full bg-danger/80" />
                    <div className="w-3 h-3 rounded-full bg-warning/80" />
                    <div className="w-3 h-3 rounded-full bg-success/80" />
                  </div>
                  <div className="flex-1 text-center">
                    <span className="text-xs text-cj-silver-dark">Inventory Pro Dashboard</span>
                  </div>
                </div>
                
                {/* Content */}
                <div className="p-6 space-y-4">
                  {/* Stats Row */}
                  <div className="grid grid-cols-3 gap-3">
                    {[
                      { label: 'Productos', value: '1,234', color: 'cj-gold' },
                      { label: 'Stock Bajo', value: '8', color: 'warning' },
                      { label: 'Valor Total', value: '$125K', color: 'success' },
                    ].map((stat) => (
                      <div key={stat.label} className="bg-white/5 rounded-lg p-3 border border-white/5">
                        <p className="text-xs text-cj-silver-dark mb-1">{stat.label}</p>
                        <p className={`text-lg font-bold text-${stat.color}`}>{stat.value}</p>
                      </div>
                    ))}
                  </div>
                  
                  {/* Chart Placeholder */}
                  <div className="bg-white/5 rounded-lg p-4 border border-white/5">
                    <div className="flex items-end justify-between h-24 gap-2">
                      {[40, 65, 45, 80, 55, 90, 70].map((h, i) => (
                        <div
                          key={i}
                          className="flex-1 bg-gradient-to-t from-cj-gold/50 to-cj-gold rounded-t"
                          style={{ height: `${h}%` }}
                        />
                      ))}
                    </div>
                    <div className="flex justify-between mt-2 text-xs text-cj-silver-dark">
                      <span>Lun</span>
                      <span>Mar</span>
                      <span>Mie</span>
                      <span>Jue</span>
                      <span>Vie</span>
                      <span>Sab</span>
                      <span>Dom</span>
                    </div>
                  </div>
                  
                  {/* Table Placeholder */}
                  <div className="space-y-2">
                    {[1, 2, 3].map((i) => (
                      <div key={i} className="flex items-center gap-3 p-2 bg-white/5 rounded-lg">
                        <div className="w-8 h-8 rounded bg-cj-gold/20" />
                        <div className="flex-1">
                          <div className="h-2 w-24 bg-white/20 rounded" />
                          <div className="h-2 w-16 bg-white/10 rounded mt-1" />
                        </div>
                        <div className="h-2 w-12 bg-success/30 rounded" />
                      </div>
                    ))}
                  </div>
                </div>
              </div>
              
              {/* Floating Badge */}
              <div className="absolute -bottom-4 -right-4 bg-cj-navy border border-cj-gold/30 rounded-xl p-4 shadow-xl">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 bg-success/20 rounded-full flex items-center justify-center">
                    <svg className="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <div>
                    <p className="text-sm font-semibold text-white">Vale Generado</p>
                    <p className="text-xs text-cj-gold font-mono">SAL-2026-00042</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Scroll Indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg className="w-6 h-6 text-cj-gold/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
      </div>
    </section>
  )
}