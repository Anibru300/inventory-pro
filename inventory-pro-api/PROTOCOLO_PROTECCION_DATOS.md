# Protocolo de Protección de Datos - Inventory Pro

## 1. Respaldo Automático de Base de Datos

### Estrategia de Backup
- **Frecuencia**: Diaria (automática)
- **Retención**: 30 días de historial
- **Ubicación**: Almacenamiento externo seguro

### Script de Backup
```bash
#!/bin/bash
# backup.sh - Ejecutar mediante cron diariamente

BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)
DB_FILE="/var/www/html/database/database.sqlite"

# Crear backup
sqlite3 $DB_FILE ".backup '$BACKUP_DIR/backup_$DATE.sqlite'"

# Comprimir
gzip "$BACKUP_DIR/backup_$DATE.sqlite"

# Eliminar backups antiguos (>30 días)
find $BACKUP_DIR -name "backup_*.sqlite.gz" -mtime +30 -delete

echo "✅ Backup completado: backup_$DATE.sqlite.gz"
```

### Configuración en Render (Add-on)
1. Crear un "Disk" en Render para almacenar backups
2. Configurar cron job para ejecutar backup diario

---

## 2. Políticas de Seguridad

### Encriptación
- **APP_KEY**: Clave de 32 bytes para encriptación de datos sensibles
- **Contraseñas**: Hashed con Bcrypt (cost factor 12)
- **Tokens**: Sanctum con expiración de 24 horas

### Headers de Seguridad
```php
// Configurados en middleware
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000
```

### CORS Restrictivo
```php
'allowed_origins' => [
    'https://inventory-pro-z81e.onrender.com',
    'http://localhost:5173',
],
```

---

## 3. Control de Acceso

### Roles de Usuario
| Rol | Permisos |
|-----|----------|
| super_admin | Acceso total |
| admin | Gestión de inventario, reportes |
| user | Solo lectura y movimientos básicos |

### Autenticación
- Tokens de acceso con expiración
- Refresh tokens para sesiones prolongadas
- Bloqueo de cuenta tras 5 intentos fallidos

---

## 4. Auditoría y Logs

### Eventos Registrados
- Inicio/cierre de sesión
- Creación/eliminación de productos
- Movimientos de stock
- Cambios en configuración

### Almacenamiento de Logs
```php
LOG_CHANNEL=stderr
LOG_LEVEL=debug
```

---

## 5. Recuperación de Desastres

### Plan de Recuperación

1. **Detección de Fallo**
   - Monitoreo de health check cada 5 minutos
   - Alertas automáticas por email

2. **Restauración de Datos**
   ```bash
   # Descargar último backup
   scp user@backup-server:/backups/backup_latest.sqlite.gz .
   
   # Descomprimir
   gunzip backup_latest.sqlite.gz
   
   # Restaurar
   cp backup_latest.sqlite database/database.sqlite
   chmod 777 database/database.sqlite
   ```

3. **Verificación Post-Restauración**
   - Verificar conexión a base de datos
   - Comprobar integridad de datos críticos
   - Validar funcionamiento de login

---

## 6. Procedimientos de Emergencia

### Si la API está caída:
1. Verificar logs en Render Dashboard
2. Reiniciar servicio manualmente
3. Si persiste, restaurar desde backup

### Si hay pérdida de datos:
1. NO reiniciar el servicio
2. Contactar soporte de Render inmediatamente
3. Preparar backup más reciente para restauración

---

## 7. Contactos de Emergencia

- **Soporte Render**: https://render.com/support
- **Documentación Laravel**: https://laravel.com/docs

---

*Última actualización: 2026-04-07*
