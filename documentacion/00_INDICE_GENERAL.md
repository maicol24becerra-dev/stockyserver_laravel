# 📚 DOCUMENTACIÓN TÉCNICA Y FUNCIONAL
## Proyecto: StockyServe — Centro Vacacional y Recreacional El Cielo

Bienvenido a la documentación oficial y modular de **StockyServe**. Cada funcionalidad del sistema ha sido desglosada en su propio archivo independiente con sus historias de usuario, controladores, modelos, rutas y vistas asociadas.

---

## 📂 Mapa de Documentación

| Archivo | Funcionalidad / Módulo | Descripción Principal |
| :--- | :--- | :--- |
| [01_AUTENTICACION_Y_SEGURIDAD.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/01_AUTENTICACION_Y_SEGURIDAD.md) | **Autenticación y Seguridad** | Login por roles, bloqueo por intentos, registro público, recuperación de contraseña y middleware de sesión. |
| [02_ADMINISTRACION_Y_USUARIOS.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/02_ADMINISTRACION_Y_USUARIOS.md) | **Administración y Usuarios** | CRUD completo de empleados y clientes, asignación de roles, validaciones y permisos. |
| [03_MENU_Y_PLATILLOS.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/03_MENU_Y_PLATILLOS.md) | **Menú y Platillos** | Gestión de platillos, precios, imágenes, categorías independientes y conmutador de disponibilidad. |
| [04_INVENTARIO_Y_MATERIA_PRIMA.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/04_INVENTARIO_Y_MATERIA_PRIMA.md) | **Inventario y Materia Prima** | Insumos, unidades de medida, stock mínimo, control de recetas y movimientos. |
| [05_MODULO_MESERO_Y_PEDIDOS.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/05_MODULO_MESERO_Y_PEDIDOS.md) | **Módulo del Mesero** | Toma de pedidos por mesa, adición de ítems en vivo, notas especiales y seguimiento de órdenes activas. |
| [06_MODULO_COCINA_KDS.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/06_MODULO_COCINA_KDS.md) | **Módulo de Cocina (KDS)** | Pantalla de cocina ordenada por antigüedad, transiciones de estado (*Pendiente ➔ En preparación ➔ Listo*). |
| [07_MODULO_CLIENTE_Y_MENU_DIGITAL.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/07_MODULO_CLIENTE_Y_MENU_DIGITAL.md) | **Módulo del Cliente** | Menú digital interactivo, consulta de disponibilidad e historial personal de consumo. |
| [08_FACTURACION_Y_PAGOS.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/08_FACTURACION_Y_PAGOS.md) | **Facturación y Pagos** | Generación de facturas, desglose de ítems, cálculo de impuestos, métodos de pago y cierre de cuentas. |
| [09_REPORTES_Y_ESTADISTICAS.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/09_REPORTES_Y_ESTADISTICAS.md) | **Reportes y Estadísticas** | Análisis de ventas, platos más vendidos, filtros por fecha y métricas de desempeño. |
| [10_PLANIFICACION_JIRA_TRELLO.md](file:///c:/laragon/www/stockyserver_laravel/documentacion/10_PLANIFICACION_JIRA_TRELLO.md) | **Planificación Jira & Trello** | Backlog completo de 21 Historias de Usuario, fechas de sprints, asignaciones y truco de importación masiva. |

---

## 🛠️ Stack Tecnológico del Proyecto

- **Backend:** Laravel 11 / PHP 8.2+
- **Base de Datos:** MySQL / MariaDB (Relacional con claves foráneas e integridad referencial)
- **Frontend:** Blade Templates, CSS3 Vanilla modularizado, JavaScript moderno
- **Arquitectura:** Modelo-Vista-Controlador (MVC) con Form Requests y Middleware de autorización
- **Servidor Local:** Laragon / Apache
