# 🍽️ 03. Módulo de Menú y Platillos
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Permite la administración completa de la carta gastronómica del centro recreacional. Incluye la creación, edición y eliminación de platillos, asignación de precios, imágenes ilustrativas, categorías de alimentos y bebidas, y conmutadores rápidos para activar o desactivar la disponibilidad de un plato según el inventario.

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-03A** | Crear Platillos con Precio, Descripción y Foto | ✅ Completado |
| **HU-03B** | Editar Información y Precios de Platillos | ✅ Completado |
| **HU-03C** | Cambiar Estado de Disponibilidad (Activo / Agotado) | ✅ Completado |
| **HU-03D** | Categorización de Platillos (Entradas, Fuertes, Bebidas, etc.) | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controladores:
- [PlatoController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/PlatoController.php)  
  *Gestión de CRUD, subida de imágenes a `storage/app/public/platos` y conmutación de estado `disponible`.*
- [CategoriaController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/CategoriaController.php)  
  *Administración de las categorías de comida y bebidas.*

### Modelos:
- `app/Models/Plato.php`
- `app/Models/Categoria.php`

### Rutas (`routes/web.php`):
- `GET    /admin/platos` ➔ `admin.platos.index`
- `GET    /admin/platos/create` ➔ `admin.platos.create`
- `POST   /admin/platos` ➔ `admin.platos.store`
- `GET    /admin/platos/{plato}/edit` ➔ `admin.platos.edit`
- `PUT    /admin/platos/{plato}` ➔ `admin.platos.update`
- `PATCH  /admin/platos/{plato}/toggle` ➔ `admin.platos.toggle`
- `DELETE /admin/platos/{plato}` ➔ `admin.platos.destroy`
- `RESOURCE /admin/categorias` ➔ CRUD de categorías

### Vistas:
- `resources/views/admin/platos/index.blade.php`
- `resources/views/admin/platos/create.blade.php`
- `resources/views/admin/platos/edit.blade.php`

---

## 💡 4. Características Principales
- **Conmutador instantáneo:** Botón para marcar un plato como "Agotado" sin tener que borrarlo.
- **Validación de precios:** Validación estricta de valores numéricos superiores a cero.
- **Relación con Pedidos:** Si un plato está agotado, el mesero y el cliente no pueden seleccionarlo.
