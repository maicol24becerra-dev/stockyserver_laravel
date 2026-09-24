# 👥 02. Módulo de Administración y Gestión de Usuarios
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Proporciona al Administrador el control total sobre los usuarios del sistema. Permite dar de alta personal (Meseros, Cocineros, Administradores), editar sus datos de contacto y rol laboral, y gestionar bajas respetando la integridad referencial (no eliminar usuarios con pedidos activos ni permitir auto-eliminación).

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-02A** | Crear Usuario en el Sistema con Validación de Correo Único | ✅ Completado |
| **HU-02B** | Editar Datos y Cambiar Rol de Usuario | ✅ Completado |
| **HU-02C** | Eliminar Usuario con Validación de Sesión y Órdenes Activas | ✅ Completado |
| **HU-07** | Asignación y Verificación de Roles y Permisos | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controlador:
- [UserController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/UserController.php)
  - `index()`: Listado paginado de usuarios con filtros por nombre y rol.
  - `create()` / `store()`: Validación de campos obligatorios, formato de correo y unicidad.
  - `edit()` / `update()`: Modificación de usuario excluyendo el ID actual en la validación de unicidad.
  - `destroy()`: Eliminación segura.

### Modelos y Migraciones:
- `app/Models/User.php`
- `database/migrations/xxxx_create_usuarios_table.php`

### Rutas (`routes/web.php`):
- `GET    /admin/usuarios` ➔ `admin.usuarios.index`
- `GET    /admin/usuarios/create` ➔ `admin.usuarios.create`
- `POST   /admin/usuarios` ➔ `admin.usuarios.store`
- `GET    /admin/usuarios/{usuario}/edit` ➔ `admin.usuarios.edit`
- `PUT    /admin/usuarios/{usuario}` ➔ `admin.usuarios.update`
- `DELETE /admin/usuarios/{usuario}` ➔ `admin.usuarios.destroy`

### Vistas:
- `resources/views/admin/usuarios/index.blade.php`
- `resources/views/admin/usuarios/create.blade.php`
- `resources/views/admin/usuarios/edit.blade.php`

---

## 🛡️ 4. Reglas de Negocio Clave
1. **Unicidad de Correo:** No pueden existir dos usuarios con el mismo correo electrónico.
2. **Protección de Sesión Activa:** Un administrador logueado no puede eliminarse a sí mismo.
3. **Integridad de Pedidos:** No se puede eliminar a un empleado si tiene pedidos pendientes o en preparación asignados.
