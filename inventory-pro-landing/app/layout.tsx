import type { Metadata } from 'next'
import './globals.css'

export const metadata: Metadata = {
  title: 'Inventory Pro - Gestión Inteligente de Inventarios',
  description: 'Sistema profesional de gestión de inventarios multi-tenant. Controla tu stock, optimiza tus operaciones y haz crecer tu negocio.',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="es">
      <body className="bg-bg-primary text-white antialiased">
        {children}
      </body>
    </html>
  )
}