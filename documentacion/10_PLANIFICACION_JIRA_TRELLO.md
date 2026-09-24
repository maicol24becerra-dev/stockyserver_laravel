# 📌 10. Planificación del Proyecto para Jira y Trello
**Proyecto:** StockyServe — El Cielo

---

## ⚡ TRUCO RÁPIDO PARA CREAR TODAS LAS TARJETAS EN TRELLO (10 segundos)

1. En tu tablero de Trello (`Proyecto en clase`), ve a la columna **"Hecho"** (o **"Lista de tareas"**).
2. Haz clic en **"Añade una tarjeta"**.
3. Copia el bloque completo de abajo y pégalo directamente en el cuadro de texto.
4. Presiona **Enter** o haz clic en **Añadir tarjeta**.
5. Trello detectará los saltos de línea y te preguntará:  
   👉 **"¿Quieres crear 22 tarjetas a la vez?"**  
   Haz clic en **"Crear tarjetas"** y listo.

```text
HU-01: Inicio de Sesión y Autenticación por Rol
HU-02A: Crear Usuario Administrator
HU-02B: Editar Usuario Administrator
HU-02C: Eliminar Usuario Administrator
HU-09: Registro Público de Clientes
HU-10: Recuperación de Contraseña
HU-13: Historial de Pedidos del Cliente
HU-14: Toma de Pedidos (Mesero)
HU-15: Dashboard y Estado de Pedidos (Mesero)
HU-16: Generación de Cuentas y Factura Desglosada
HU-18: Pantalla Comanda de Cocina por Antigüedad
HU-19: Cambio de Estados (Pendiente -> Preparación -> Listo)
HU-03: Gestión de Platillos y Categorías de Menú
HU-06: Gestión de Inventario y Materia Prima
HU-11: Menú Digital Interactivo para Clientes
HU-17: Actualizar y Agregar Ítems a Pedidos Activos
HU-20: Detalle de Pedidos e Instrucciones Especiales
HU-04: Módulo de Reportes de Ventas y Estadísticas
HU-05: Fichas de Caracterización e Insumos
HU-08: Panel de Supervisión en Tiempo Real
HU-12: Seguimiento en Tiempo Real
HU-21: Priorización de Pedidos y Comandas Urgentes
```

---

## 📅 Cronograma y Fechas de Entrega por Sprints

Para mostrar el proyecto como **100% Finalizado**, todas las tarjetas se ubican en la columna **Hecho** (*Done*) con este calendario de entregas:

| Código | Historia de Usuario | Sprint | Fecha de Vencimiento |
| :--- | :--- | :--- | :--- |
| **HU-01** | Inicio de Sesión y Autenticación por Rol | Sprint 1 (Auth) | **05 / Ago / 2026** |
| **HU-02A** | Crear Usuario Administrator | Sprint 1 (Admin) | **08 / Ago / 2026** |
| **HU-02B** | Editar Usuario Administrator | Sprint 1 (Admin) | **10 / Ago / 2026** |
| **HU-02C** | Eliminar Usuario Administrator | Sprint 1 (Admin) | **12 / Ago / 2026** |
| **HU-09** | Registro Público de Clientes | Sprint 1 (Auth) | **15 / Ago / 2026** |
| **HU-10** | Recuperación de Contraseña | Sprint 1 (Auth) | **18 / Ago / 2026** |
| **HU-13** | Historial de Pedidos del Cliente | Sprint 2 (Cliente) | **20 / Ago / 2026** |
| **HU-14** | Toma de Pedidos (Mesero) | Sprint 2 (Mesero) | **22 / Ago / 2026** |
| **HU-15** | Dashboard y Estado de Pedidos (Mesero) | Sprint 2 (Mesero) | **25 / Ago / 2026** |
| **HU-16** | Generación de Cuentas y Factura | Sprint 2 (Pagos) | **28 / Ago / 2026** |
| **HU-18** | Pantalla Comanda de Cocina | Sprint 2 (Cocina) | **30 / Ago / 2026** |
| **HU-19** | Cambio de Estados (Cocina) | Sprint 2 (Cocina) | **02 / Sep / 2026** |
| **HU-03** | Gestión de Platillos y Menú | Sprint 3 (Catálogo) | **05 / Sep / 2026** |
| **HU-06** | Gestión de Inventario y Materia Prima | Sprint 3 (Stock) | **08 / Sep / 2026** |
| **HU-11** | Menú Digital Interactivo | Sprint 3 (Cliente) | **10 / Sep / 2026** |
| **HU-17** | Actualizar Pedidos Activos | Sprint 3 (Mesero) | **12 / Sep / 2026** |
| **HU-20** | Notas e Instrucciones Especiales | Sprint 3 (Cocina) | **15 / Sep / 2026** |
| **HU-04** | Reportes de Ventas y Estadísticas | Sprint 4 (Métricas) | **18 / Sep / 2026** |
| **HU-05** | Fichas de Caracterización de Insumos | Sprint 4 (Stock) | **20 / Sep / 2026** |
| **HU-08** | Panel de Supervisión en Tiempo Real | Sprint 4 (Admin) | **22 / Sep / 2026** |
| **HU-12** | Seguimiento en Tiempo Real | Sprint 4 (Cliente) | **23 / Sep / 2026** |
| **HU-21** | Priorización de Pedidos en Cocina | Sprint 4 (Cocina) | **24 / Sep / 2026** |

---

## 🏷️ Etiquetas Recomendadas (Colores en Trello / Jira)

- 🔴 **Autenticación:** HU-01, HU-09, HU-10
- 🔵 **Administración:** HU-02A, HU-02B, HU-02C, HU-08
- 🟡 **Mesero:** HU-14, HU-15, HU-16, HU-17
- 🟠 **Cocina:** HU-18, HU-19, HU-20, HU-21
- 🟢 **Cliente:** HU-11, HU-12, HU-13
- 🟣 **Inventario & Reportes:** HU-03, HU-04, HU-05, HU-06
