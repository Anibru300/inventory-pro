'use client'

import { motion } from 'framer-motion'
import { Package, BarChart3, Truck, Shield, Zap, Users } from 'lucide-react'

const features = [
  {
    icon: Package,
    title: 'Control Total de Stock',
    description: 'Gestiona múltiples almacenes, controla niveles de stock y recibe alertas automáticas cuando el inventario está bajo.',
  },
  {
    icon: BarChart3,
    title: 'Reportes Avanzados',
    description: 'Analiza el rendimiento de tu inventario con reportes detallados de rotación, valoración y tendencias.',
  },
  {
    icon: Truck,
    title: 'Movimientos en Tiempo Real',
    description: 'Registra entradas, salidas y transferencias entre almacenes. Historial completo de auditoría.',
  },
  {
    icon: Shield,
    title: 'Seguridad Empresarial',
    description: 'Protección de datos con encriptación AES-256. Control de acceso basado en roles y autenticación JWT.',
  },
  {
    icon: Zap,
    title: 'API REST Completa',
    description: 'Integra Inventory Pro con tus sistemas existentes mediante nuestra API documentada y webhooks.',
  },
  {
    icon: Users,
    title: 'Multi-tenant',
    description: 'Arquitectura multi-empresa con aislamiento completo de datos entre diferentes organizaciones.',
  },
]

export default function Features() {
  return (
    <section id="features" className="py-24 bg-bg-secondary">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-16">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Todo lo que necesitas para
            <span className="gradient-text"> gestionar tu inventario</span>
          </h2>
          <p className="text-gray-400 text-lg max-w-2xl mx-auto">
            Funcionalidades diseñadas para empresas que buscan eficiencia y control total sobre su inventario.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {features.map((feature, index) => (
            <motion.div
              key={feature.title}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              className="bg-bg-tertiary rounded-2xl p-6 border border-gray-800 hover:border-accent-primary/50 transition-colors"
            >
              <div className="w-12 h-12 bg-accent-primary/10 rounded-xl flex items-center justify-center mb-4">
                <feature.icon className="w-6 h-6 text-accent-primary" />
              </div>
              <h3 className="text-xl font-semibold mb-2">{feature.title}</h3>
              <p className="text-gray-400">{feature.description}</p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  )
}