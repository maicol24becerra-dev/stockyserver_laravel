# 📊 09. Módulo de Reportes, Métricas y Supervisión
**Proyecto:** StockyServe — El Cielo

---

## 📌 1. Descripción General
Ofrece a la administración una visión panorámica del rendimiento comercial y operativo del restaurante. Incluye métricas de ventas acumuladas por día, semana o mes, platos más vendidos, tiempos de despacho, ingresos totales y exportación de datos para auditorías contables.

---

## 📋 2. Historias de Usuario Asociadas

| Código | Historia de Usuario | Estado |
| :--- | :--- | :--- |
| **HU-04** | Reporte General de Ventas con Filtros por Fecha y Categoría | ✅ Completado |
| **HU-08** | Panel de Supervisión y Métricas en Tiempo Real para Administrador | ✅ Completado |

---

## 💻 3. Componentes del Código

### Controladores:
- [ReporteController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/ReporteController.php)
  - `ventas()`: Análisis de facturación, cálculo de ticket promedio y totales agrupados.
  - `platosMasVendidos()`: Ranking de demanda culinaria.
  - `exportar()`: Exportación estructurada de datos de ventas.
- [DashboardController.php](file:///c:/laragon/www/stockyserver_laravel/app/Http/Controllers/Admin/DashboardController.php)
  - `index()`: Métricas principales (ingresos del día, pedidos activos, mesas ocupadas).

### Rutas (`routes/web.php`):
- `GET /admin/dashboard` ➔ `admin.dashboard`
- `GET /admin/reportes` ➔ `admin.reportes.index`
- `GET /admin/reportes/ventas` ➔ `admin.reportes.ventas`
- `GET /admin/reportes/exportar` ➔ `admin.reportes.exportar`

### Vistas:
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/reportes/index.blade.php`

---

## 📈 4. Indicadores Clave de Desempeño (KPIs)
1. **Ticket Promedio:** Gasto medio realizado por cada mesa o comensal.
2. **Top Platillos:** Identificación de platos estrella con mayor margen y rotación.
3. **Picos de Demanda:** Horarios y días de mayor afluencia en el centro vacacional.
4. **Eficiencia en Cocina:** Tiempos promedio transcurridos entre la orden del mesero y el despacho del plato.
