# 📱 07. Módulo del Cliente y Menú Digital
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Proporciona la experiencia digital para los clientes y visitantes del centro recreacional. Permite consultar la carta y el menú digital interactivo desde cualquier dispositivo móvil o computadora, explorar fotografías y precios de los platos disponibles, y revisar su historial personal de compras y pedidos realizados.

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-11** | Menú Digital con Filtro por Categorías y Precios | ✅ Completado |
| **HU-12** | Consulta de Estado y Progreso del Pedido | ✅ Completado |
| **HU-13** | Historial Personal de Pedidos y Consumos | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controlador:
- [ClienteController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Cliente/ClienteController.php)
  - `menu()`: Lista los platillos activos agrupados por categoría.
  - `historial()`: Muestra los pedidos finalizados del cliente autenticado con desglose y fecha.
  - `seguimiento()`: Permite ver el progreso de su pedido actual.

### Rutas (`routes/web.php`):
- `GET /cliente/dashboard` ➔ `cliente.dashboard`
- `GET /cliente/menu` ➔ `cliente.menu`
- `GET /cliente/historial` ➔ `cliente.historial`
- `GET /cliente/pedido/{pedido}` ➔ `cliente.pedido.detalle`

### Vistas y Estilos:
- `resources/views/cliente/dashboard.blade.php`
- `resources/views/cliente/menu.blade.php`
- `resources/views/cliente/historial.blade.php`

---

## 🌟 4. Ventajas para el Cliente
1. **Transparencia en Precios:** Precios claros y fotografías reales de los platillos.
2. **Disponibilidad en Tiempo Real:** Los platos marcados como agotados en administración no permiten ser ordenados.
3. **Control de Consumo:** Acceso a facturas anteriores y detalle de todo lo consumido durante su estancia.
