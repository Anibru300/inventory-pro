<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo - StockWolf</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #0B1F3A;
            font-size: 28px;
            margin: 0;
        }
        .logo span {
            color: #2E7DE8;
        }
        h2 {
            color: #0B1F3A;
            font-size: 22px;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #2E7DE8 0%, #1e6ad1 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }
        .button:hover {
            background: linear-gradient(135deg, #1e6ad1 0%, #2E7DE8 100%);
        }
        .expiration {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .expiration p {
            margin: 0;
            color: #856404;
        }
        .benefits {
            background-color: #e8f4fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .benefits h3 {
            color: #0B1F3A;
            margin-top: 0;
            font-size: 16px;
        }
        .benefits ul {
            color: #555;
            padding-left: 20px;
        }
        .benefits li {
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #999;
            font-size: 14px;
        }
        .footer a {
            color: #2E7DE8;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>Stock<span>Wolf</span></h1>
        </div>
        
        <h2>¡Bienvenido a StockWolf, {{ $userName }}!</h2>
        
        <p>Gracias por registrarte en <strong>StockWolf</strong>, tu sistema ERP de gestión de inventarios.</p>
        
        <p>Para completar tu registro y activar todas las funcionalidades de tu cuenta, por favor verifica tu correo electrónico haciendo clic en el siguiente botón:</p>
        
        <center>
            <a href="{{ $verifyUrl }}" class="button">Verificar mi correo</a>
        </center>
        
        <p>O copia y pega este enlace en tu navegador:</p>
        <p style="word-break: break-all; background: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 14px;">
            {{ $verifyUrl }}
        </p>
        
        <div class="expiration">
            <p><strong>⏰ Este enlace expira en {{ $expiresIn }}</strong></p>
        </div>
        
        <div class="benefits">
            <h3>✨ Al verificar tu correo podrás:</h3>
            <ul>
                <li>Gestionar múltiples almacenes</li>
                <li>Generar reportes avanzados</li>
                <li>Exportar datos a Excel y PDF</li>
                <li>Recibir alertas de stock bajo</li>
                <li>Acceder al soporte técnico prioritario</li>
            </ul>
        </div>
        
        <p style="font-size: 14px; color: #666;">
            Si no creaste esta cuenta, puedes ignorar este correo. El enlace expirará automáticamente.
        </p>
        
        <div class="footer">
            <p>© 2026 StockWolf - Sistema ERP de Gestión de Inventarios</p>
            <p>Un producto de <a href="https://anibru300.github.io/cj-consultoria-web/">CJ Consultoría</a></p>
        </div>
    </div>
</body>
</html>
