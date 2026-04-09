<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña - StockWolf</title>
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
        .ignore {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 30px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>Stock<span>Wolf</span></h1>
        </div>
        
        <h2>Hola {{ $userName }},</h2>
        
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>StockWolf</strong>.</p>
        
        <p>Para crear una nueva contraseña, haz clic en el siguiente botón:</p>
        
        <center>
            <a href="{{ $resetUrl }}" class="button">Restablecer Contraseña</a>
        </center>
        
        <p>O copia y pega este enlace en tu navegador:</p>
        <p style="word-break: break-all; background: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 14px;">
            {{ $resetUrl }}
        </p>
        
        <div class="expiration">
            <p><strong>⚠️ Este enlace expira en {{ $expiresIn }}</strong></p>
        </div>
        
        <div class="ignore">
            <p><strong>¿No solicitaste este cambio?</strong></p>
            <p>Si no fuiste tú quien solicitó restablecer la contraseña, puedes ignorar este correo. Tu contraseña actual seguirá siendo válida.</p>
        </div>
        
        <div class="footer">
            <p>© 2026 StockWolf - Sistema ERP de Gestión de Inventarios</p>
            <p>Un producto de <a href="https://anibru300.github.io/cj-consultoria-web/">CJ Consultoría</a></p>
        </div>
    </div>
</body>
</html>
