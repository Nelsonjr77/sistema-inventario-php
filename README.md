# 📦🧾📊 Sistema de Inventario, Facturación y Ventas

Sistema web completo desarrollado con **PHP**, **MySQL**, **HTML5**, **CSS3** y **JavaScript** para gestión integral de inventario, facturación e histórico de ventas.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

---

## ✨ Características Principales

### 📦 Gestión de Inventario
- ➕ Crear, editar y eliminar productos
- 🖼️ Subir y administrar imágenes de productos
- 💰 Control de precios y stock
- 📝 Descripciones detalladas
- 🎨 Interfaz visual tipo galería

### 🧾 Sistema de Facturación
- 📄 Generación automática de facturas
- 🛒 Selección múltiple de productos
- 🧮 Cálculo automático de subtotales, IVA y totales
- 👤 Registro de datos del cliente
- 🖨️ Vista imprimible profesional
- 📋 Números de factura únicos

### 📊 Histórico y Estadísticas
- 📈 Historial completo de ventas
- 🏆 Top 5 productos más vendidos
- 💵 Estadísticas de ingresos totales
- 📉 Promedio de ventas
- 🔍 Detalles de cada transacción

---

## 🚀 Instalación Rápida

### Requisitos Previos
- ✅ **XAMPP** (Apache + MySQL + PHP 7.4+)
- ✅ Navegador web moderno (Chrome, Firefox, Edge)

### Pasos de Instalación

**1. Copiar archivos**
```bash
C:\xampp\htdocs\sistema-inventario
```

**2. Iniciar servicios XAMPP**
- Abre XAMPP Control Panel
- Inicia **Apache** y **MySQL**

**3. Crear base de datos**
- Accede a: `http://localhost/phpmyadmin`
- Crea una nueva base de datos: `sistema_inventario`
- Importa el archivo: `database/schema.sql`

**4. Acceder al sistema**
```
http://localhost/sistema-inventario
```

📖 **Para instalación detallada, consulta:** [INSTALACION.md](INSTALACION.md)

---

## 🎯 Guía de Uso

### Módulo de Productos
1. Ve a **"Productos"** en el menú de navegación
2. Haz clic en **"+ Nuevo Producto"**
3. Completa los campos (nombre, descripción, precio, stock)
4. Opcionalmente sube una imagen del producto
5. Haz clic en **"Guardar Producto"**

### Crear una Factura
1. Ve a **"Facturación"** en el menú
2. Completa los datos del cliente (opcional)
3. Haz clic en **"+ Agregar Producto"**
4. Selecciona productos y especifica cantidades
5. El sistema calculará automáticamente los totales
6. Haz clic en **"Generar Factura"**
7. La factura se abrirá en una vista imprimible

### Ver Histórico de Ventas
1. Ve a **"Ventas"** en el menú
2. Visualiza todas las facturas generadas
3. Consulta estadísticas y productos más vendidos
4. Haz clic en **"Ver"** para ver detalles de cualquier factura

---

## Estructura del Proyecto

```
sistema-inventario/
├── database/          # Scripts SQL
├── config/           # Configuración de base de datos
├── uploads/          # Imágenes de productos
├── includes/         # Cabecera y pie de página
├── modules/          # Módulos del sistema
│   ├── productos/    # Gestión de inventario
│   ├── facturacion/  # Sistema de facturación
│   └── ventas/       # Histórico y estadísticas
├── assets/           # CSS y JavaScript
└── index.php         # Página principal
```

## Funcionalidades

### Módulo de Inventario
- Registrar productos con nombre, descripción, precio e imagen
- Visualizar productos en galería
- Editar información de productos
- Eliminar productos

### Módulo de Facturación
- Seleccionar productos del inventario
- Especificar cantidades
- Calcular total automáticamente
- Generar facturas
- Imprimir facturas

### Módulo de Ventas
- Visualizar histórico completo de facturas
- Ver detalles de cada venta
- Estadísticas de productos más vendidos
- Resumen de ventas totales

## Soporte
Si tienes problemas con la instalación, verifica:
- Que Apache y MySQL estén ejecutándose en XAMPP
- Que la base de datos esté creada correctamente
- Que la carpeta `uploads/` tenga permisos de escritura

## Tecnologías
- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- JavaScript (Vanilla)
