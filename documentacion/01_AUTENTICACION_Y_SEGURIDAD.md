# 🔐 01. Módulo de Autenticación y Seguridad
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Gestiona el acceso seguro a la plataforma para los 4 roles del sistema (Administrador, Mesero, Cocinero y Cliente). Implementa control de intentos fallidos contra ataques de fuerza bruta, redirección automática según rol, registro público para comensales y recuperación de contraseñas.

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-01** | Inicio de Sesión y Autenticación con Redirección por Rol | ✅ Completado |
| **HU-09** | Registro Público de Clientes | ✅ Completado |
| **HU-10** | Recuperación de Contraseña con Validación de Token | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controladores:
- [AuthController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/AuthController.php)  
  *Maneja `showLogin()`, `login()` con rate limiting de 3 intentos / 15 min, `redirectByRole()` y `logout()` con invalidación de sesión.*
- [RegistroController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/RegistroController.php)  
  *Maneja `create()` y `store()` asignando automáticamente el rol 'Cliente'.*
- [PasswordResetController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/PasswordResetController.php)  
  *Maneja la solicitud de reseteo, generación de token temporal y actualización de contraseña.*

### Rutas (`routes/web.php`):
- `GET  /login` ➔ `login` (Formulario de login)
- `POST /login` ➔ `login.store` (Procesamiento de credenciales)
- `POST /logout` ➔ `logout` (Cierre seguro de sesión)
- `GET  /register` ➔ `register` (Registro de cliente)
- `POST /register` ➔ `register.store` (Creación de cliente)
- `GET  /forgot-password` ➔ Formulario de recuperación
- `POST /forgot-password` ➔ Envío de enlace de restablecimiento

### Vistas:
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`

---

## 🔒 4. Políticas de Seguridad Implementadas
1. **Rate Limiting:** Bloqueo temporal tras 3 intentos fallidos por IP y correo.
2. **Hash Seguro:** Encriptación de contraseñas con algoritmo `Bcrypt`.
3. **Invalidación de Sesión:** `session()->invalidate()` y `session()->regenerateToken()` en cada cierre de sesión.
4. **Mensajes Genéricos:** No se revela si un correo existe o no en caso de fallo, evitando enumeración de usuarios.
