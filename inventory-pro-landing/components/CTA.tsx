'use client'

import { motion } from 'framer-motion'
import { ArrowRight } from 'lucide-react'

export default function CTA() {
  return (
    <section id="contact" className="py-24">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, scale: 0.95 }}
          whileInView={{ opacity: 1, scale: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="relative rounded-3xl overflow-hidden"
        >
          {/* Background */}
          <div className="absolute inset-0 bg-gradient-to-br from-accent-primary to-accent-secondary" />
          <div className="absolute inset-0 bg-[url('/grid.svg')] opacity-20" />
          <div className="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl" />
          <div className="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl" />

          {/* Content */}
          <div className="relative py-16 px-8 md:px-16 text-center">
            <h2 className="text-3xl md:text-4xl font-bold text-white mb-4">
              ¿Listo para transformar tu gestión de inventarios?
            </h2>
            <p className="text-white/80 text-lg max-w-2xl mx-auto mb-8">
              Únete a cientos de empresas que ya confían en Inventory Pro. 
              Comienza tu prueba gratuita hoy mismo.
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <a
                href="http://localhost:5173/register"
                className="inline-flex items-center gap-2 bg-white text-accent-primary px-8 py-4 rounded-lg font-medium hover:bg-gray-100 transition-colors"
              >
                Comenzar Prueba Gratis
                <ArrowRight size={20} />
              </a>
              <a
                href="mailto:ventas@inventorypro.com"
                className="inline-flex items-center gap-2 bg-white/10 text-white px-8 py-4 rounded-lg font-medium hover:bg-white/20 transition-colors border border-white/30"
              >
                Contactar Ventas
              </a>
            </div>
          </div>
        </motion.div>
      </div>
    </section>
  )
}