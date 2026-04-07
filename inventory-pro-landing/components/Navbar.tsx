'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'

export default function Navbar() {
  const [isScrolled, setIsScrolled] = useState(false)
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false)

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20)
    }
    window.addEventListener('scroll', handleScroll)
    return () => window.removeEventListener('scroll', handleScroll)
  }, [])

  const navLinks = [
    { name: 'Inicio', href: '#inicio' },
    { name: 'Funciones', href: '#funciones' },
    { name: 'Planes', href: '#planes' },
    { name: 'Contacto', href: '#contacto' },
  ]

  return (
    <nav
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isScrolled
          ? 'bg-cj-navy/95 backdrop-blur-xl border-b border-cj-gold/10 shadow-lg shadow-black/20'
          : 'bg-transparent'
      }`}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <Link href="/" className="flex items-center gap-3 group">
            <div className="w-10 h-10 bg-gradient-to-br from-cj-gold to-cj-gold-dark rounded-lg flex items-center justify-center shadow-lg shadow-cj-gold/20 group-hover:shadow-cj-gold/40 transition-shadow">
              <svg className="w-6 h-6 text-cj-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div>
              <span className="text-xl font-heading font-bold text-white">Inventory</span>
              <span className="text-xl font-heading font-bold text-cj-gold">Pro</span>
              <p className="text-[10px] text-cj-silver-dark tracking-wider uppercase">by CJ Consultoría</p>
            </div>
          </Link>

          {/* Desktop Navigation */}
          <div className="hidden md:flex items-center gap-8">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                href={link.href}
                className="text-sm font-medium text-cj-silver-dark hover:text-cj-gold transition-colors relative group"
              >
                {link.name}
                <span className="absolute -bottom-1 left-0 w-0 h-0.5 bg-cj-gold transition-all group-hover:w-full" />
              </Link>
            ))}
          </div>

          {/* CTA Buttons */}
          <div className="hidden md:flex items-center gap-4">
            <a
              href="https://inventory-pro-9ef8.onrender.com/#/login"
              className="text-sm font-medium text-cj-silver-dark hover:text-white transition-colors"
            >
              Iniciar Sesión
            </a>
            <a
              href="https://inventory-pro-9ef8.onrender.com/#/register"
              className="px-5 py-2.5 bg-gradient-to-r from-cj-gold to-cj-gold-dark text-cj-navy font-heading font-semibold text-sm rounded-lg hover:shadow-lg hover:shadow-cj-gold/25 transition-all hover:-translate-y-0.5"
            >
              Comenzar Gratis
            </a>
          </div>

          {/* Mobile Menu Button */}
          <button
            className="md:hidden p-2 text-cj-silver-dark hover:text-white"
            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
          >
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              {isMobileMenuOpen ? (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
              ) : (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
              )}
            </svg>
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      {isMobileMenuOpen && (
        <div className="md:hidden bg-cj-navy/98 backdrop-blur-xl border-t border-cj-gold/10">
          <div className="px-4 py-6 space-y-4">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                href={link.href}
                className="block text-lg font-medium text-cj-silver-dark hover:text-cj-gold transition-colors"
                onClick={() => setIsMobileMenuOpen(false)}
              >
                {link.name}
              </Link>
            ))}
            <div className="pt-4 border-t border-white/10 space-y-3">
              <a
                href="https://inventory-pro-9ef8.onrender.com/#/login"
                className="block text-center py-3 text-cj-silver-dark hover:text-white transition-colors"
              >
                Iniciar Sesión
              </a>
              <a
                href="https://inventory-pro-9ef8.onrender.com/#/register"
                className="block text-center py-3 bg-gradient-to-r from-cj-gold to-cj-gold-dark text-cj-navy font-heading font-semibold rounded-lg"
              >
                Comenzar Gratis
              </a>
            </div>
          </div>
        </div>
      )}
    </nav>
  )
}