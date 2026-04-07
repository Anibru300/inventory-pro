import type { Metadata } from 'next'
import { Montserrat, Open_Sans, Playfair_Display } from 'next/font/google'
import './globals.css'

const montserrat = Montserrat({
  subsets: ['latin'],
  variable: '--font-heading',
  display: 'swap',
})

const openSans = Open_Sans({
  subsets: ['latin'],
  variable: '--font-body',
  display: 'swap',
})

const playfair = Playfair_Display({
  subsets: ['latin'],
  variable: '--font-tagline',
  display: 'swap',
})

export const metadata: Metadata = {
  title: 'Inventory Pro by CJ Consultoría | Sistema de Gestión de Inventarios',
  description: 'Sistema profesional de gestión de inventarios multi-tenant. Controla tu stock, optimiza tus operaciones y haz crecer tu negocio. Plan gratuito disponible.',
  keywords: ['inventario', 'gestión de stock', 'control de inventario', 'software inventario', 'saas inventario'],
  authors: [{ name: 'CJ Consultoría' }],
  openGraph: {
    title: 'Inventory Pro - Gestión Inteligente de Inventarios',
    description: 'Sistema profesional de gestión de inventarios. Plan gratuito disponible.',
    type: 'website',
  },
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="es" className={`${montserrat.variable} ${openSans.variable} ${playfair.variable}`}>
      <body className="bg-cj-navy text-white antialiased font-body">
        {children}
      </body>
    </html>
  )
}