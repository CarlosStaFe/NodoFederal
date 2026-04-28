# Sistema de Tokens API en Memoria - Laravel

## 📖 Descripción

Este sistema implementa la gestión automática de tokens de API almacenándolos **exclusivamente en memoria (cache)** para mayor seguridad. Los tokens no se persisten en la base de datos, proporcionando una capa adicional de seguridad.

## 🔐 Características de Seguridad

- ✅ **Solo en memoria**: Los tokens se almacenan únicamente en cache/memoria
- ✅ **No en base de datos**: No hay persistencia en BD para mayor seguridad  
- ✅ **Auto-expiración**: Los tokens expiran automáticamente según su TTL
- ✅ **Limpieza automática**: Se invalidan al logout del usuario
- ✅ **Renovación proactiva**: Se renuevan automáticamente 10 minutos antes del vencimiento usando refresh_token
- ✅ **Renovación inteligente**: Sistema inteligente de renovación antes del vencimiento
- ✅ **Cache inteligente**: TTL dinámico basado en expiración del token

## 🚀 Funcionamiento

### Al Login del Usuario
1. El evento `Login` dispara automáticamente el listener `ObtainApiTokensOnLogin`
2. Se obtiene un nuevo token desde la API externa
3. Se calcula la fecha de expiración: `now() + expires_in`
4. Se almacena en cache con clave: `user_token_{user_id}`
5. Se configura TTL dinámico (reducido en 60s para renovar antes)

### Al Usar la API
1. Se verifica si hay token válido en cache
2. **RENOVACIÓN PROACTIVA**: Si el token expira en menos de 10 minutos, se renueva automáticamente usando refresh_token
3. Si existe y es válido (y no está cerca de expirar), se retorna inmediatamente
4. Si está expirado, se intenta renovar con refresh_token
5. Si no hay refresh_token o falla, se obtiene uno completamente nuevo
6. El nuevo token se almacena en cache automáticamente

### Al Logout
1. El evento `Logout` dispara el listener `InvalidateTokensOnLogout`
2. Se elimina el token del cache inmediatamente
3. No queda rastro del token en el sistema

## 📁 Archivos del Sistema

### Servicios
- `app/Services/ApiTokenService.php` - Servicio principal que maneja tokens

### Listeners
- `app/Listeners/ObtainApiTokensOnLogin.php` - Obtiene tokens automáticamente al login
- `app/Listeners/InvalidateTokensOnLogout.php` - Invalida tokens al logout

### Comandos Artisan
- `app/Console/Commands/TokensInfoCommand.php` - Información del sistema
- `app/Console/Commands/TokenStatusCommand.php` - Estado de usuario específico
- `app/Console/Commands/ClearUserTokenCommand.php` - Limpiar token de usuario
- `app/Console/Commands/ClearAllTokensCommand.php` - Limpiar todos los tokens
- `app/Console/Commands/TestTokenCommand.php` - Probar el sistema
- `app/Console/Commands/TestRefreshTokenCommand.php` - Probar específicamente refresh_token

### Proveedores
- `app/Providers/AppServiceProvider.php` - Registro de servicio y listeners

## 🔧 Configuración

### Variables de Entorno Requeridas
```env
API_TOKEN=https://api.example.com/token          # URL para obtener tokens
API_USER=usuario_api                             # Usuario de la API
API_PASSWORD=password_api                        # Contraseña de la API
```

### Variables de Entorno para Renovación Automática
```env
API_REFRESH=https://api.example.com/refresh      # URL para renovar tokens con refresh_token
API_CLIENT_ID=client_id                          # ID del cliente OAuth (opcional)
API_CLIENT_SECRET=client_secret                  # Secret del cliente OAuth (opcional)
```

## 💻 Uso Programático

### Desde el Modelo User
```php
$user = Auth::user();

// Obtener token válido (automático)
$token = $user->getApiToken();

// Verificar si tiene token válido
if ($user->hasValidApiToken()) {
    // Usuario tiene token válido en cache
}

// Obtener información del token
$tokenInfo = $user->getTokenInfo();
// Retorna: ['has_token', 'is_valid', 'expires_at', 'expires_in_human', 'has_refresh_token', 'obtained_at']

// Forzar renovación del token
$user->refreshApiToken();

// Invalidar token manualmente
$user->invalidateApiToken();
```

### Desde el Servicio
```php
$tokenService = app(\App\Services\ApiTokenService::class);

// Obtener token para usuario específico
$token = $tokenService->getValidToken(Auth::user());

// Obtener token global (sin usuario)
$globalToken = $tokenService->getValidToken(null);

// Simuliar obtención en login
$tokenService->obtainTokensOnLogin($user);

// Invalidar token
$tokenService->invalidateUserToken($user);
```

### En Controladores
```php
class MiControlador extends Controller 
{
    public function consultarApi()
    {
        $user = Auth::user();
        $token = $user->getApiToken(); // ¡Automático y desde cache!
        
        if (!$token) {
            return response()->json(['error' => 'No token disponible'], 401);
        }
        
        $response = Http::withToken($token)->get('https://api.example.com/data');
        // ...
    }
}
```

## 🛠️ Comandos Artisan

### Información del Sistema
```bash
php artisan tokens:info
```
Muestra configuración, estado del cache, y estadísticas generales.

### Estado de Usuario Específico
```bash
php artisan tokens:status usuario@email.com
```
Verifica el estado del token de un usuario específico y permite acciones como invalidar o renovar.

### Limpiar Token de Usuario
```bash
php artisan tokens:clear-user usuario@email.com
```
Elimina el token de un usuario específico del cache.

### Limpiar Todos los Tokens
```bash
php artisan tokens:clear-all
# o forzar sin confirmación:
php artisan tokens:clear-all --force
```
Elimina todos los tokens del cache.

### Probar el Sistema
```bash
php artisan tokens:test usuario@email.com
```
Ejecuta una serie de pruebas completas del sistema para un usuario específico.

### Probar el Sistema de Refresh
```bash
php artisan tokens:test-refresh usuario@email.com
```
Ejecuta pruebas específicas del sistema de refresh_token y renovación proactiva.

## 🔄 Flujo de Trabajo Típico

1. **Usuario hace login** → Sistema obtiene token automáticamente y lo guarda en cache
2. **Usuario usa funcionalidades** → Token se obtiene desde cache instantáneamente  
3. **Token está por expirar** → Se renueva automáticamente con refresh_token
4. **Usuario hace logout** → Token se elimina del cache inmediatamente
5. **Próximo login** → Se obtiene un token completamente nuevo

## ⚡ Ventajas de Este Enfoque

- **Seguridad**: No hay tokens persistidos en BD
- **Performance**: Acceso instantáneo desde cache
- **Automático**: Sin intervención manual requerida
- **Limpieza**: Tokens se eliminan automáticamente al logout
- **Renovación**: Sistema inteligente de renovación antes del vencimiento
- **Escalabilidad**: Compatible con cache distribuido (Redis, Memcached)

## 🚨 Consideraciones Importantes

- **Cache Driver**: Funciona con cualquier driver de cache de Laravel
- **Pérdida de Cache**: Si se limpia el cache, los tokens se pierden (esto es intencional para seguridad)
- **Sesiones**: Los tokens están atados a la sesión del usuario indirectamente
- **Cluster**: En entornos multi-servidor, usar cache centralizado (Redis/Memcached)

## 📊 Monitoreo y Debugging

### Logs
El sistema registra todas las operaciones importantes:
```
[timestamp] INFO: Usuario autenticado: user@email.com, obteniendo tokens API en memoria...
[timestamp] INFO: Tokens API obtenidos y almacenados en memoria para user@email.com  
[timestamp] INFO: Token obtenido desde cache para usuario 123
[timestamp] INFO: Token renovado exitosamente para usuario 123
```

### Verificación Manual
```php
// En tinker o controlador de debug
$user = User::find(1);
$tokenInfo = $user->getTokenInfo();
dd($tokenInfo);

// Verificar cache directamente
$cacheKey = 'user_token_' . $user->id;
$tokenData = Cache::get($cacheKey);
dd($tokenData);
```

---

## 🎯 Conclusión

Este sistema proporciona una implementación segura, eficiente y automática para manejar tokens de API sin comprometer la seguridad almacenándolos en la base de datos. La gestión es completamente transparente para el usuario final y los desarrolladores pueden acceder a tokens válidos con una simple llamada a `$user->getApiToken()`.