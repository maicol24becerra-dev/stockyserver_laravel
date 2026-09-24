# 💳 08. Módulo de Facturación y Cierre de Cuentas
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Gestiona la liquidación económica de los pedidos consumidos en el restaurante. Permite emitir la precuenta para los clientes en la mesa, generar la factura con el desglose exacto de cada ítem consumido, calcular subtotales e impuestos, y registrar la forma de pago (efectivo, tarjeta, transferencia) para liberar la mesa.

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-16** | Generación de Precuenta y Factura Desglosada por Mesa | ✅ Completado |
| **HU-22** | Registro de Transacción de Pago y Cierre de Orden | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controlador:
- [PagoController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/PagoController.php)
  - `factura()`: Genera la vista formal de factura para imprimir o mostrar en pantalla con totales y detalles de productos.
  - `procesarPago()`: Registra el abono o pago total, cambia el estado del pedido a `'entregado'` / `'pagado'`, y libera la mesa para nuevos comensales.

### Modelos y Tablas:
- `app/Models/Pago.php`
- `app/Models/Pedido.php`
- Tabla `pagos` (id, id_pedido, monto, metodo_pago, fecha_pago, referencia).

### Rutas (`routes/web.php`):
- `GET  /admin/pagos` ➔ `admin.pagos.index`
- `GET  /admin/pagos/{pedido}/factura` ➔ `admin.pagos.factura`
- `POST /admin/pagos/{pedido}/procesar` ➔ `admin.pagos.procesar`
- `GET  /mesero/factura/{pedido}` ➔ Vista de precuenta para mesero

### Vistas:
- `resources/views/mesero/factura.blade.php`
- `resources/views/admin/pagos/index.blade.php`
- `resources/views/admin/pagos/factura.blade.php`

---

## 🧾 4. Estructura de la Factura
Cada comprobante emitido incluye:
- Razón Social: **Centro Vacacional y Recreacional El Cielo**
- Número de Factura consecutivo y Fecha/Hora exacta
- Identificación de Mesa y Nombre del Mesero que atendió
- Tabla detallada: Cantidad | Nombre del Plato | Precio Unitario | Subtotal
- Cálculo de Total a Pagar y Medio de Pago aplicado
