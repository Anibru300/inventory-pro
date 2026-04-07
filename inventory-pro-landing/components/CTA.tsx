'use client'

export default function CTA() {
  return (
    <section className="relative py-32 overflow-hidden">
      {/* Background */}
      <div className="absolute inset-0 bg-gradient-to-br from-cj-gold/20 via-cj-navy to-cj-navy" />
      <div className="absolute inset-0 bg-[url('/pattern.svg')] opacity-5" />
      
      {/* Animated Circles */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px]">
        <div className="absolute inset-0 border border-cj-gold/20 rounded-full animate-pulse-slow" />
        <div className="absolute inset-10 border border-cj-gold/15 rounded-full animate-pulse-slow" style={{ animationDelay: '1s' }} />
        <div className="absolute inset-20 border border-cj-gold/10 rounded-full animate-pulse-slow" style={{ animationDelay: '2s' }} />
      </div>

      <div className="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        {/* Badge */}
        <div className="inline-flex items-center gap-2 px-4 py-2 bg-cj-gold/10 border border-cj-gold/30 rounded-full mb-8">
          <svg className="w-5 h-5 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span className="text-cj-gold font-medium">Setup en menos de 5 minutos</span>
        </div>

        {/* Title */}
        <h2 className="text-4xl md:text-5xl lg:text-6xl font-heading font-bold text-white mb-6">
          ¿Listo para transformar tu
          <span className="text-cj-gold"> gestión de inventario?</span>
        </h2>

        {/* Description */}
        <p className="text-xl text-cj-silver-dark mb-10 max-w-2xl mx-auto">
          Únete a cientos de empresas que ya optimizan sus operaciones con Inventory Pro. 
          Comienza gratis hoy mismo.
        </p>

        {/* CTA Buttons */}
        <div className="flex flex-col sm:flex-row gap-4 justify-center mb-12">
          <a
            href="https://inventory-pro-9ef8.onrender.com/#/register"
            className="group inline-flex items-center justify-center gap-2 px-8 py-4 bg-cj-gold text-cj-navy font-heading font-bold text-lg rounded-xl hover:bg-cj-gold-light transition-all hover:shadow-2xl hover:shadow-cj-gold/30 hover:-translate-y-1"
          >
            Crear Cuenta Gratis
            <svg className="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>
          <a
            href="https://inventory-pro-9ef8.onrender.com/#/login"
            className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/5 border border-white/20 text-white font-heading font-semibold text-lg rounded-xl hover:bg-white/10 hover:border-cj-gold/30 transition-all"
          >
            Iniciar Sesión
          </a>
        </div>

        {/* Trust Badges */}
        <div className="flex flex-wrap items-center justify-center gap-8 text-sm text-cj-silver-dark">
          <div className="flex items-center gap-2">
            <svg className="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Sin tarjeta de crédito</span>
          </div>
          <div className="flex items-center gap-2">
            <svg className="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Setup instantáneo</span>
          </div>
          <div className="flex items-center gap-2">
            <svg className="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Cancela cuando quieras</span>
          </div>
        </div>
      </div>
    </section>
  )
}