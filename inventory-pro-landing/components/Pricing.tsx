'use client'

import { motion } from 'framer-motion'
import { Check } from 'lucide-react'

const plans = [
  {
    name: 'Starter',
    price: 29,
    description: 'Perfecto para pequeños negocios',
    features: [
      'Hasta 500 productos',
      '1 almacén',
      '2 usuarios',
      'Reportes básicos',
      'Soporte por email',
      'API limitada',
    ],
    cta: 'Comenzar Prueba',
    popular: false,
  },
  {
    name: 'Pro',
    price: 79,
    description: 'Para negocios en crecimiento',
    features: [
      'Productos ilimitados',
      '5 almacenes',
      '10 usuarios',
      'Reportes avanzados',
      'Soporte prioritario',
      'API completa',
      'Integraciones',
      'Múltiples monedas',
    ],
    cta: 'Comenzar Prueba',
    popular: true,
  },
  {
    name: 'Enterprise',
    price: null,
    description: 'Para grandes organizaciones',
    features: [
      'Todo lo de Pro',
      'Almacenes ilimitados',
      'Usuarios ilimitados',
      'Custom reporting',
      'Soporte 24/7',
      'SLA garantizado',
      'On-premise opcional',
      'Desarrollo a medida',
    ],
    cta: 'Contactar Ventas',
    popular: false,
  },
]

export default function Pricing() {
  return (
    <section id="pricing" className="py-24">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-16">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Precios simples y
            <span className="gradient-text"> transparentes</span>
          </h2>
          <p className="text-gray-400 text-lg max-w-2xl mx-auto">
            Elige el plan que mejor se adapte a tus necesidades. Todos incluyen prueba gratuita de 14 días.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {plans.map((plan, index) => (
            <motion.div
              key={plan.name}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              className={`relative rounded-2xl p-8 ${
                plan.popular
                  ? 'bg-gradient-to-b from-accent-primary/20 to-bg-secondary border-2 border-accent-primary'
                  : 'bg-bg-secondary border border-gray-800'
              }`}
            >
              {plan.popular && (
                <div className="absolute -top-4 left-1/2 -translate-x-1/2">
                  <span className="bg-accent-primary text-white px-4 py-1 rounded-full text-sm font-medium">
                    Más Popular
                  </span>
                </div>
              )}

              <div className="mb-6">
                <h3 className="text-xl font-semibold mb-2">{plan.name}</h3>
                <p className="text-gray-400 text-sm">{plan.description}</p>
              </div>

              <div className="mb-6">
                {plan.price ? (
                  <div className="flex items-baseline gap-1">
                    <span className="text-4xl font-bold">${plan.price}</span>
                    <span className="text-gray-400">/mes</span>
                  </div>
                ) : (
                  <div className="text-4xl font-bold">Personalizado</div>
                )}
              </div>

              <ul className="space-y-3 mb-8">
                {plan.features.map((feature) => (
                  <li key={feature} className="flex items-center gap-3">
                    <Check className="w-5 h-5 text-accent-primary flex-shrink-0" />
                    <span className="text-gray-300">{feature}</span>
                  </li>
                ))}
              </ul>

              <a
                href={plan.price ? 'http://localhost:5173/register' : '#contact'}
                className={`block w-full text-center py-3 rounded-lg font-medium transition-colors ${
                  plan.popular
                    ? 'bg-accent-primary hover:bg-accent-secondary text-white'
                    : 'bg-bg-tertiary hover:bg-bg-elevated text-white border border-gray-700'
                }`}
              >
                {plan.cta}
              </a>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  )
}