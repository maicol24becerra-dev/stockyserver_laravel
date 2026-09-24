# 📦 04. Módulo de Inventario y Materia Prima
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Administra las existencias de ingredientes, insumos y materia prima utilizada en la cocina y bar del restaurante. Permite registrar entradas de compras, unidades de medida (kg, litros, unidades), fijar umbrales de stock mínimo para evitar desabastecimiento y vincular insumos a recetas de preparación.

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-05** | Fichas de Insumos y Proveedores (Unidad, Costo, Mínimo) | ✅ Completado |
| **HU-06** | Control de Stock y Registro de Entradas / Salidas de Inventario | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controlador:
- [InventarioController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/InventarioController.php)
  - `index()`: Listado de insumos con semáforo de colores según nivel de stock (Normal, Bajo, Crítico).
  - `store()`: Registro de nueva materia prima.
  - `update()`: Ajuste manual y actualización de existencias.
  - `destroy()`: Eliminación de materias primas sin dependencias activas.

### Modelos y Tablas:
- `app/Models/MateriaPrima.php`
- `app/Models/Receta.php` (si aplica para composición de platos)
- Tabla `materia_prima` (campos: `nombre`, `cantidad_disponible`, `unidad_medida`, `stock_minimo`, `precio_unitario`).

### Rutas (`routes/web.php`):
- `GET    /admin/inventario` ➔ `admin.inventario.index`
- `POST   /admin/inventario` ➔ `admin.inventario.store`
- `PUT    /admin/inventario/{id}` ➔ `admin.inventario.update`
- `DELETE /admin/inventario/{id}` ➔ `admin.inventario.destroy`

### Vistas:
- `resources/views/admin/inventario/index.blade.php`

---

## ⚠️ 4. Alertas y Semáforo de Existencias
- 🟢 **Verde (Normal):** Cantidad disponible superior al stock mínimo.
- 🟡 **Amarillo (Alerta preventiva):** Cantidad cercana al límite del stock mínimo.
- 🔴 **Rojo (Crítico):** Stock igual o menor al mínimo configurado, requiriendo compra inmediata a proveedores.
