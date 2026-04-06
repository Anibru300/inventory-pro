'use client'

import { motion } from 'framer-motion'
import { ArrowRight, Check } from 'lucide-react'

export default function Hero() {
  return (
    <section className="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
      {/* Background gradients */}
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-accent-primary/20 via-bg-primary to-bg-primary" />
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-accent-primary/10 rounded-full blur-3xl" />
      
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center">
          {/* Badge */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-accent-primary/10 border border-accent-primary/30 mb-8"
          >
            <span className="w-2 h-2 bg-accent-primary rounded-full animate-pulse" />
            <span className="text-sm text-accent-primary">Nuevo: Soporte multi-almacén</span>
          </motion.div>

          {/* Headline */}
          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6"
          >
            Gestión de Inventarios
            <br />
            <span className="gradient-text">Inteligente y Simple</span>
          </motion.h1>

          {/* Subheadline */}
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto mb-8"
          >
            Controla tu stock, optimiza tus operaciones y toma decisiones basadas en datos. 
            La solución profesional que tu negocio necesita.
          </motion.p>

          {/* CTAs */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.3 }}
            className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12"
          >
            <a
              href="http://localhost:5173/register"
              className="btn-primary flex items-center gap-2 text-lg px-8 py-4"
            >
              Comenzar Prueba Gratis
              <ArrowRight size={20} />
            </a>
            <a
              href="#demo"
              className="btn-secondary flex items-center gap-2 text-lg px-8 py-4"
            >
              Ver Demo
            </a>
          </motion.div>

          {/* Trust badges */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.5, delay: 0.4 }}
            className="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500"
          >
            <span className="flex items-center gap-2">
              <Check size={16} className="text-success" />
              14 días gratis
            </span>
            <span className="flex items-center gap-2">
              <Check size={16} className="text-success" />
              Sin tarjeta de crédito
            </span>
            <span className="flex items-center gap-2">
              <Check size={16} className="text-success" />
              Cancela cuando quieras
            </span>
          </motion.div>
        </div>

        {/* Dashboard Preview */}
        <motion.div
          initial={{ opacity: 0, y: 40 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.5 }}
          className="mt-16 relative"
        >
          <div className="absolute inset-0 bg-gradient-to-t from-bg-primary via-transparent to-transparent z-10 h-32 bottom-0" />
          <div className="relative rounded-2xl overflow-hidden border border-gray-800 shadow-2xl">
            <div className="bg-bg-secondary p-4 border-b border-gray-800 flex items-center gap-2">
              <div className="flex gap-2">
                <div className="w-3 h-3 rounded-full bg-red-500" />
                <div className="w-3 h-3 rounded-full bg-yellow-500" />
                <div className="w-3 h-3 rounded-full bg-green-500" />
              </div>
              <div className="flex-1 text-center text-sm text-gray-500">
                app.inventorypro.com
              </div>
            </div>
            <div className="bg-bg-secondary p-8">
              {/* Mock Dashboard */}
              <div className="grid grid-cols-4 gap-4 mb-6">
                {[1, 2, 3, 4].map((i) => (
                  <div key={i} className="bg-bg-tertiary rounded-xl p-4">
                    <div className="h-2 w-20 bg-gray-700 rounded mb-2" />
                    <div className="h-8 w-16 bg-gray-600 rounded" />
                  </div>
                ))}
              </div>
              <div className="grid grid-cols-3 gap-4">
                <div className="col-span-2 bg-bg-tertiary rounded-xl p-4 h-48">
                  <div className="h-2 w-32 bg-gray-700 rounded mb-4" />
                  <div className="flex items-end gap-2 h-32">
                    {[40, 65, 45, 80, 55, 70, 90].map((h, i) => (
                      <div
                        key={i}
                        className="flex-1 bg-accent-primary/50 rounded-t"
                        style={{ height: `${h}%` }}
                      />
                    ))}
                  </div>
                </div>
                <div className="bg-bg-tertiary rounded-xl p-4">
                  <div className="h-2 w-24 bg-gray-700 rounded mb-4" />
                  <div className="space-y-3">
                    {[1, 2, 3, 4].map((i) => (
                      <div key={i} className="flex items-center gap-3">
                        <div className="w-8 h-8 bg-gray-700 rounded-lg" />
                        <div className="flex-1">
                          <div className="h-2 w-20 bg-gray-700 rounded mb-1" />
                          <div className="h-2 w-12 bg-gray-800 rounded" />
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </motion.div>
      </div>
    </section>
  )
}