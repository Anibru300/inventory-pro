'use client'

import { useState } from 'react'

const plans = [
  {
    name: 'Gratis',
    description: 'Perfecto para empezar',
    price: 0,
    period: 'para siempre',
    popular: false,
    features: [
      { text: 'Hasta 100 productos', included: true },
      { text: '1 almacén', included: true },
      { text: '1 usuario', included: true },
      { text: 'Movimientos básicos', included: true },
      { text: 'Vales PDF', included: true },
      { text: 'Reportes básicos', included: true },
      { text: 'Importación CSV (50 productos)', included: true },
      { text: 'Múltiples almacenes', included: false },
      { text: 'Usuarios adicionales', included: false },
      { text: 'Soporte prioritario', included: false },
    ],
    cta: 'Comenzar Gratis',
    ctaLink: 'https://inventory-pro-9ef8.onrender.com/#/register',
  },
  {
    name: 'Starter',
    description: 'Para pequeños negocios',
    price: 19,
    period: 'USD/mes',
    popular: false,
    features: [
      { text: 'Hasta 500 productos', included: true },
      { text: '2 almacenes', included: true },
      { text: '2 usuarios', included: true },
      { text: 'Movimientos ilimitados', included: true },
      { text: 'Vales PDF personalizados', included: true },
      { text: 'Reportes avanzados', included: true },
      { text: 'Importación CSV ilimitada', included: true },
      { text: 'Alertas de stock bajo', included: true },
      { text: 'Múltiples almacenes', included: false },
      { text: 'Soporte prioritario', included: false },
    ],
    cta: 'Elegir Starter',
    ctaLink: 'https://inventory-pro-9ef8.onrender.com/#/register?plan=starter',
  },
  {
    name: 'Pro',
    description: 'El más popular',
    price: 49,
    period: 'USD/mes',
    popular: true,
    features: [
      { text: 'Productos ilimitados', included: true },
      { text: '5 almacenes', included: true },
      { text: '5 usuarios', included: true },
      { text: 'Movimientos ilimitados', included: true },
      { text: 'Vales PDF personalizados', included: true },
      { text: 'Reportes avanzados', included: true },
      { text: 'Importación Excel/CSV', included: true },
      { text: 'Alertas inteligentes', included: true },
      { text: 'Kardex histórico', included: true },
      { text: 'Soporte por email', included: true },
    ],
    cta: 'Elegir Pro',
    ctaLink: 'https://inventory-pro-9ef8.onrender.com/#/register?plan=pro',
  },
  {
    name: 'Enterprise',
    description: 'Para grandes empresas',
    price: 99,
    period: 'USD/mes',
    popular: false,
    features: [
      { text: 'Todo lo del plan Pro', included: true },
      { text: 'Almacenes ilimitados', included: true },
      { text: 'Usuarios ilimitados', included: true },
      { text: 'API de integración', included: true },
      { text: 'Personalización de marca', included: true },
      { text: 'Backup automático', included: true },
      { text: 'Roles y permisos avanzados', included: true },
      { text: 'Integración con Shopify', included: true },
      { text: 'Soporte telefónico 24/7', included: true },
      { text: 'Onboarding dedicado', included: true },
    ],
    cta: 'Contactar Ventas',
    ctaLink: 'mailto:ventas@cjconsultoria.com?subject=Interesado%20en%20plan%20Enterprise',
  },
]

export default function Pricing() {
  const [isAnnual, setIsAnnual] = useState(false)

  return (
    <section id="planes" className="relative py-32 bg-cj-navy-light">
      {/* Background */}
      <div className="absolute inset-0">
        <div className="absolute bottom-0 right-0 w-[600px] h-[600px] bg-cj-gold/5 rounded-full blur-3xl" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="text-center max-w-3xl mx-auto mb-16">
          <span className="inline-block px-4 py-1.5 bg-cj-gold/10 border border-cj-gold/20 rounded-full text-sm text-cj-gold font-medium mb-6">
            Planes y Precios
          </span>
          <h2 className="text-4xl md:text-5xl font-heading font-bold text-white mb-6">
            Elige el plan perfecto para
            <span className="text-cj-gold"> tu negocio</span>
          </h2>
          <p className="text-lg text-cj-silver-dark mb-8">
            Comienza gratis y escala según crezcas. Sin contratos, cancela cuando quieras.
          </p>

          {/* Billing Toggle */}
          <div className="flex items-center justify-center gap-4">
            <span className={`text-sm ${!isAnnual ? 'text-white' : 'text-cj-silver-dark'}`}>Mensual</span>
            <button
              onClick={() => setIsAnnual(!isAnnual)}
              className={`relative w-14 h-7 rounded-full transition-colors ${
                isAnnual ? 'bg-cj-gold' : 'bg-white/20'
              }`}
            >
              <div
                className={`absolute top-1 w-5 h-5 bg-white rounded-full transition-transform ${
                  isAnnual ? 'translate-x-7' : 'translate-x-1'
                }`}
              />
            </button>
            <span className={`text-sm ${isAnnual ? 'text-white' : 'text-cj-silver-dark'}`}>
              Anual <span className="text-cj-gold">(2 meses gratis)</span>
            </span>
          </div>
        </div>

        {/* Pricing Cards */}
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          {plans.map((plan) => (
            <div
              key={plan.name}
              className={`relative rounded-2xl p-6 transition-all hover:scale-105 ${
                plan.popular
                  ? 'bg-gradient-to-b from-cj-gold/20 to-cj-navy border-2 border-cj-gold shadow-2xl shadow-cj-gold/20'
                  : 'bg-cj-navy border border-white/10 hover:border-cj-gold/30'
              }`}
            >
              {/* Popular Badge */}
              {plan.popular && (
                <div className="absolute -top-4 left-1/2 -translate-x-1/2">
                  <span className="px-4 py-1 bg-cj-gold text-cj-navy text-sm font-bold rounded-full">
                    Más Popular
                  </span>
                </div>
              )}

              {/* Plan Header */}
              <div className="text-center mb-6 pt-2">
                <h3 className="text-xl font-heading font-bold text-white mb-1">{plan.name}</h3>
                <p className="text-sm text-cj-silver-dark">{plan.description}</p>
              </div>

              {/* Price */}
              <div className="text-center mb-6">
                <div className="flex items-baseline justify-center gap-1">
                  <span className="text-2xl text-cj-silver-dark">$</span>
                  <span className="text-5xl font-heading font-bold text-white">
                    {isAnnual && plan.price > 0 ? Math.round(plan.price * 10 / 12) : plan.price}
                  </span>
                  <span className="text-cj-silver-dark">/{isAnnual ? 'mes' : 'mes'}</span>
                </div>
                <p className="text-xs text-cj-silver-dark mt-1">
                  {isAnnual && plan.price > 0 ? 'Facturado anualmente' : plan.period}
                </p>
              </div>

              {/* Features */}
              <ul className="space-y-3 mb-8">
                {plan.features.map((feature, index) => (
                  <li key={index} className="flex items-start gap-3">
                    {feature.included ? (
                      <svg className="w-5 h-5 text-cj-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                      </svg>
                    ) : (
                      <svg className="w-5 h-5 text-cj-silver-dark/30 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    )}
                    <span className={feature.included ? 'text-cj-silver text-sm' : 'text-cj-silver-dark/40 text-sm'}>
                      {feature.text}
                    </span>
                  </li>
                ))}
              </ul>

              {/* CTA */}
              <a
                href={plan.ctaLink}
                className={`block w-full py-3 text-center font-heading font-semibold rounded-xl transition-all ${
                  plan.popular
                    ? 'bg-cj-gold text-cj-navy hover:bg-cj-gold-light hover:shadow-lg hover:shadow-cj-gold/30'
                    : 'bg-white/5 text-white border border-white/10 hover:bg-white/10 hover:border-cj-gold/30'
                }`}
              >
                {plan.cta}
              </a>
            </div>
          ))}
        </div>

        {/* Additional Info */}
        <div className="mt-16 grid md:grid-cols-3 gap-8 text-center">
          <div className="p-6">
            <div className="w-12 h-12 bg-cj-gold/10 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg className="w-6 h-6 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h4 className="text-lg font-semibold text-white mb-2">Sin compromiso</h4>
            <p className="text-sm text-cj-silver-dark">Cancela en cualquier momento sin penalizaciones</p>
          </div>
          <div className="p-6">
            <div className="w-12 h-12 bg-cj-gold/10 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg className="w-6 h-6 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
            <h4 className="text-lg font-semibold text-white mb-2">Pago seguro</h4>
            <p className="text-sm text-cj-silver-dark">Stripe y MercadoPago para tu tranquilidad</p>
          </div>
          <div className="p-6">
            <div className="w-12 h-12 bg-cj-gold/10 rounded-xl flex items-center justify-center mx-auto mb-4">
              <svg className="w-6 h-6 text-cj-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <h4 className="text-lg font-semibold text-white mb-2">Soporte incluido</h4>
            <p className="text-sm text-cj-silver-dark">Ayuda cuando la necesites, sin costo extra</p>
          </div>
        </div>
      </div>
    </section>
  )
}