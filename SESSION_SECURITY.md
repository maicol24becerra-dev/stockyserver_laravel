# 🔒 Sistema de Seguridad de Sesión - StockyServer

## Descripción General

El sistema destruye automáticamente la sesión del usuario cuando intenta navegar hacia atrás usando el botón del navegador. Esto es una medida de seguridad para restaurantes de punto de venta donde no debe permitirse que los empleados vuelvan atrás en el navegador.

## ¿Cómo Funciona?

### 1. **Prevención de Navegación Hacia Atrás**
```javascript
history.pushState({loadCount: loadCount}, null, location.href);
```
Cuando el usuario carga una página del dashboard, se agrega un punto al historial que previene que pueda volver a la página anterior.

### 2. **Detección del Intento de Ir Atrás**
```javascript
window.addEventListener('popstate', function(event) {
    handleBackButtonAttempt();
});
```
Si el usuario intenta hacer click en el botón atrás del navegador, se dispara el evento `popstate`.

### 3. **Destrucción de Sesión**
Cuando se detecta que intentó ir atrás:

1. **Se muestra inmediatamente** una pantalla modal con:
   - 🔒 Sesión Cerrada
   - Mensaje explicativo
   - Botón "Ir a Login"

2. **Se envía un POST a `/logout`** para:
   - Destruir la sesión en servidor
   - Invalidar el token CSRF
   - Regenerar el token de sesión

3. **Se redirige a `/login`** después de 3 segundos

### 4. **Verificación Periódica de Sesión**
```javascript
setInterval(checkSession, config.checkInterval); // Cada 10 segundos
```
Cada 10 segundos se verifica que la sesión siga activa con un GET a `/check-session`.

Si la sesión expiró (401), se muestra la pantalla de sesión cerrada y se redirige a login.

## Archivos Involucrados

### Frontend
- `public/js/session-destroyer.js` - Script principal que maneja todo
- `resources/views/layouts/admin.blade.php` - Carga el script
- `resources/views/mesero/dashboard.blade.php` - Carga el script
- `resources/views/cocinero/dashboard.blade.php` - Carga el script
- `resources/views/cliente/dashboard.blade.php` - Carga el script

### Backend
- `app/Http/Controllers/AuthController.php` - Ruta `logout()` destruye la sesión
- `routes/web.php` - Rutas `/logout` y `/check-session`

## Rutas API

### GET `/check-session`
Verifica si la sesión actual es válida.

**Respuesta 200 (OK)**
```json
{"authenticated": true}
```

**Respuesta 401 (No Autorizado)**
```json
{"authenticated": false}
```

### POST `/logout`
Destruye la sesión actual del usuario.

**Headers requeridos**
```
X-CSRF-TOKEN: <token>
Content-Type: application/json
```

**Respuesta**
Redirige a `/login` con headers anti-caché.

## Flujo Completo

```
1. Usuario accede a /admin/dashboard
   ↓
2. Se carga session-destroyer.js
   ↓
3. Se cuenta la sesión en sessionStorage
   ↓
4. Se agrega pushState al historial
   ↓
5. Se inicia verificación periódica cada 10s
   ↓
6. Usuario hace click en botón atrás
   ↓
7. Se dispara popstate event
   ↓
8. Se muestra modal "🔒 Sesión Cerrada"
   ↓
9. Se envía POST a /logout
   ↓
10. Se redirige a /login (después de 3s)
   ↓
11. Usuario debe hacer login nuevamente
```

## Seguridad

✅ **Protege contra:**
- Navegación hacia atrás para acceder a páginas anteriores sin autorización
- Acceso a URLs de dashboard sin sesión válida
- Sesiones expiradas que se mantienen activas
- Ataques CSRF (validación con X-CSRF-TOKEN)

✅ **Características:**
- No permite modificar el historial del navegador
- Verifica sesión cada 10 segundos
- Token CSRF regenerado en cada logout
- Headers anti-caché en respuesta de logout
- Mensaje claro al usuario cuando se cierra sesión

## Testing

### Prueba 1: Bloqueo del Botón Atrás
1. Login como cualquier usuario
2. Click en botón atrás del navegador
3. **Esperado:** Se muestra modal "🔒 Sesión Cerrada"
4. **Esperado:** Redirige a login automáticamente

### Prueba 2: Acceso Directo a Dashboard sin Sesión
1. Cierra sesión normalmente
2. Intenta acceder a `/admin/dashboard` directamente
3. **Esperado:** Redirige a `/login`

### Prueba 3: Sesión Expirada
1. Login y espera 10 segundos
2. Simula sesión expirada (borrar cookie en DevTools)
3. **Esperado:** Después de 10 segundos, se muestra modal y redirige a login

## Notas

- El script se carga en todos los dashboards (admin, mesero, cocinero, cliente)
- Cada dashboard tiene su propio contador de carga en sessionStorage
- La verificación de sesión es no-bloqueante (usa fetch sin await)
- El logout es immediato pero el POST se envía en paralelo
- No afecta la navegación normal dentro del dashboard
