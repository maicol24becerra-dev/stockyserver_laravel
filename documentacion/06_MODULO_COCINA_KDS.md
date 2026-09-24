# 👨‍🍳 06. Módulo de Cocina (Kitchen Display System - KDS)
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Es la interfaz visual para los chefs y cocineros del restaurante. Sustituye las comandas impresas en papel por una pantalla digital interactiva donde los pedidos aparecen en orden cronológico (del más antiguo al más reciente) para garantizar que los comensales sean atendidos con el menor tiempo de espera posible.

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-18** | Pantalla Comanda de Cocina Ordenada por Antigüedad | ✅ Completado |
| **HU-19** | Transición de Estados del Pedido (*Pendiente ➔ Preparación ➔ Listo*) | ✅ Completado |
| **HU-21** | Identificación de Prioridad y Resaltado de Espera | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controladores:
- [CocineroController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Cocinero/CocineroController.php)  
  *Carga la pantalla de cocina con pedidos en estados activos (`pendiente`, `en preparación`).*
- [PedidoController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/PedidoController.php)  
  *Método `updateEstado()` que procesa el cambio de estatus del pedido.*

### Rutas (`routes/web.php`):
- `GET   /cocinero/dashboard` ➔ Vista KDS de cocina
- `PATCH /cocinero/pedidos/{pedido}/estado` ➔ Actualización de estado del pedido

### Vistas y Estilos:
- `resources/views/cocinero/dashboard.blade.php`
- `public/css/cocinero/dashboard.css`

---

## ⏱️ 4. Flujo de Preparación en Cocina

```
[ NUEVO PEDIDO ]  ➔  🟡 Estado: "Pendiente"
        │
        ▼  (El cocinero pulsa "Iniciar Preparación")
[ EN COCCIÓN ]   ➔  🟠 Estado: "En preparación"
        │
        ▼  (Platillos listos para emplatar y servir)
[ PARA ENTREGA ] ➔  🟢 Estado: "Listo" (Notifica al mesero)
```

### Características Visuales:
- **Reloj de Tiempo Transcurrido:** Muestra cuántos minutos lleva cada orden esperando.
- **Detalle de Notas:** Resalta instrucciones especiales (ej. "Alergia a mariscos", "Sin cebolla").
- **Agrupación por Mesa:** Permite a la cocina despachar todos los platos de una misma mesa al tiempo.
