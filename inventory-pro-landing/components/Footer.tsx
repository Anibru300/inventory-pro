'use client'

import Link from 'next/link'

export default function Footer() {
  const currentYear = new Date().getFullYear()

  const footerLinks = {
    producto: [
      { name: 'Funciones', href: '#funciones' },
      { name: 'Planes', href: '#planes' },
      { name: 'Demo', href: 'https://inventory-pro-9ef8.onrender.com/#/login' },
      { name: 'Actualizaciones', href: '#' },
    ],
    empresa: [
      { name: 'Sobre Nosotros', href: '#' },
      { name: 'CJ Consultoría', href: 'https://cjconsultoria.com' },
      { name: 'Blog', href: '#' },
      { name: 'Carreras', href: '#' },
    ],
    soporte: [
      { name: 'Centro de Ayuda', href: '#' },
      { name: 'Documentación', href: '#' },
      { name: 'API', href: '#' },
      { name: 'Contacto', href: '#contacto' },
    ],
    legal: [
      { name: 'Términos de Servicio', href: '#' },
      { name: 'Política de Privacidad', href: '#' },
      { name: 'Seguridad', href: '#' },
    ],
  }

  return (
    <footer id="contacto" className="bg-cj-navy-dark border-t border-white/5">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Main Footer */}
        <div className="py-16 grid grid-cols-2 md:grid-cols-6 gap-8">
          {/* Brand Column */}
          <div className="col-span-2">
            <Link href="/" className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-gradient-to-br from-cj-gold to-cj-gold-dark rounded-lg flex items-center justify-center">
                <svg className="w-6 h-6 text-cj-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
              </div>
              <div>
                <span className="text-xl font-heading font-bold text-white">Inventory</span>
                <span className="text-xl font-heading font-bold text-cj-gold">Pro</span>
              </div>
            </Link>
            <p className="text-cj-silver-dark text-sm mb-6 max-w-xs">
              Sistema profesional de gestión de inventarios diseñado para impulsar el crecimiento de tu negocio.
            </p>
            <p className="text-xs text-cj-silver-dark/60">
              Una solución de <span className="text-cj-gold">CJ Consultoría</span>
            </p>
            
            {/* Social Links */}
            <div className="flex gap-4 mt-6">
              {[
                { name: 'LinkedIn', icon: 'M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z' },
                { name: 'Twitter', icon: 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z' },
                { name: 'YouTube', icon: 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z' },
              ].map((social) => (
                <a
                  key={social.name}
                  href="#"
                  className="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center text-cj-silver-dark hover:bg-cj-gold/10 hover:text-cj-gold transition-colors"
                  aria-label={social.name}
                >
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d={social.icon} />
                  </svg>
                </a>
              ))}
            </div>
          </div>

          {/* Links Columns */}
          <div>
            <h4 className="text-white font-heading font-semibold mb-4">Producto</h4>
            <ul className="space-y-3">
              {footerLinks.producto.map((link) => (
                <li key={link.name}>
                  <Link href={link.href} className="text-sm text-cj-silver-dark hover:text-cj-gold transition-colors">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="text-white font-heading font-semibold mb-4">Empresa</h4>
            <ul className="space-y-3">
              {footerLinks.empresa.map((link) => (
                <li key={link.name}>
                  <Link href={link.href} className="text-sm text-cj-silver-dark hover:text-cj-gold transition-colors">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="text-white font-heading font-semibold mb-4">Soporte</h4>
            <ul className="space-y-3">
              {footerLinks.soporte.map((link) => (
                <li key={link.name}>
                  <Link href={link.href} className="text-sm text-cj-silver-dark hover:text-cj-gold transition-colors">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="text-white font-heading font-semibold mb-4">Legal</h4>
            <ul className="space-y-3">
              {footerLinks.legal.map((link) => (
                <li key={link.name}>
                  <Link href={link.href} className="text-sm text-cj-silver-dark hover:text-cj-gold transition-colors">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </div>

        {/* Contact Bar */}
        <div className="py-6 border-t border-white/5">
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <div className="flex items-center gap-6">
              <a href="mailto:soporte@cjconsultoria.com" className="flex items-center gap-2 text-sm text-cj-silver-dark hover:text-cj-gold transition-colors">
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                soporte@cjconsultoria.com
              </a>
              <a href="tel:+521234567890" className="flex items-center gap-2 text-sm text-cj-silver-dark hover:text-cj-gold transition-colors">
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                +52 123 456 7890
              </a>
            </div>
            <p className="text-sm text-cj-silver-dark/60">
              © {currentYear} CJ Consultoría. Todos los derechos reservados.
            </p>
          </div>
        </div>
      </div>
    </footer>
  )
}