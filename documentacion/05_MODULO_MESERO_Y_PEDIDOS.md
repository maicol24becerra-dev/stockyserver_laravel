# 🛎️ 05. Módulo del Mesero y Toma de Pedidos
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Es una de las interfaces operativas más importantes del sistema. Permite a los meseros tomar pedidos directamente en las mesas de los clientes, seleccionar platillos disponibles, especificar cantidades y notas especiales ("sin sal", "bien cocido"), enviar comandas a cocina y consultar el estado en vivo de sus órdenes atendidas.

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-14** | Toma y Registro de Pedidos por Mesa y Asignación de Cliente | ✅ Completado |
| **HU-15** | Dashboard Operativo y Monitoreo de Pedidos del Mesero | ✅ Completado |
| **HU-17** | Modificación y Adición de Ítems a Pedidos Pendientes | ✅ Completado |
| **HU-20** | Registro y Visualización de Notas e Instrucciones Especiales | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controlador:
- [PedidoController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/PedidoController.php)
  - `store()`: Crea un nuevo pedido con estado `'pendiente'`, asociando la mesa y el mesero autenticado.
  - `addItem()`: Agrega productos adicionales a un pedido antes de que se finalice.
  - `dashboardMesero()`: Filtra los pedidos pertenecientes al mesero en turno.

### Modelos y Tablas:
- `app/Models/Pedido.php`
- `app/Models/ItemPedido.php`
- Tablas: `pedidos` (id, id_usuario, mesa, estado, total, created_at) y `item_pedido` (id_pedido, id_plato, cantidad, precio_unitario, notas_especiales).

### Rutas (`routes/web.php`):
- `GET  /mesero/dashboard` ➔ Panel principal del mesero
- `POST /mesero/pedidos` ➔ `pedidos.store` (Creación de comanda)
- `POST /mesero/pedidos/{pedido}/items` ➔ `pedidos.addItem` (Añadir platillos)
- `GET  /mesero/factura/{pedido}` ➔ Vista de precuenta

### Vistas y Estilos:
- `resources/views/mesero/dashboard.blade.php`
- `resources/views/mesero/factura.blade.php`
- `public/css/mesero/mesero-dashboard.css`

---

## 🔄 4. Ciclo de Vida del Pedido en Mesa
1. **Apertura:** El mesero selecciona el número de mesa y los platillos solicitados por el cliente.
2. **Envío a Cocina:** El pedido se registra con estado `pendiente` y aparece inmediatamente en la pantalla del cocinero.
3. **Adiciones:** Si los clientes piden rondas adicionales, el mesero añade ítems al pedido existente sin duplicar la orden.
4. **Alerta de Entrega:** Cuando cocina marca el plato como `listo`, el mesero retira y entrega en la mesa.
