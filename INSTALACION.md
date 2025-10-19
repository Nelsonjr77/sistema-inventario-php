# Guía de Instalación Completa
## Sistema de Inventario, Facturación y Ventas

---

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **XAMPP** (versión 7.4 o superior)
  - Descarga desde: https://www.apachefriends.org/
  - Incluye: Apache, MySQL y PHP

---

## 📦 Paso 1: Copiar Archivos del Sistema

1. Descarga o copia la carpeta completa `sistema-inventario`

2. Pega la carpeta en el directorio de XAMPP:
   ```
   C:\xampp\htdocs\
   ```

3. La ruta final debe quedar así:
   ```
   C:\xampp\htdocs\sistema-inventario\
   ```

---

## 🚀 Paso 2: Iniciar Servicios de XAMPP

1. Abre el **Panel de Control de XAMPP**
   - En Windows: Busca "XAMPP Control Panel"
   - O ejecuta: `C:\xampp\xampp-control.exe`

2. Inicia los siguientes servicios haciendo clic en "Start":
   - ✅ **Apache** (servidor web)
   - ✅ **MySQL** (base de datos)

3. Verifica que ambos servicios muestren fondo verde y digan "Running"

**Problemas comunes:**
- Si Apache no inicia, probablemente el puerto 80 esté ocupado por Skype o IIS
- Si MySQL no inicia, puede que el puerto 3306 esté en uso
- Solución: Cambia los puertos desde el botón "Config" en XAMPP

---

## 💾 Paso 3: Crear la Base de Datos

### Opción A: Importar el archivo SQL (Recomendado)

1. Abre tu navegador web (Chrome, Firefox, Edge, etc.)

2. Navega a phpMyAdmin:
   ```
   http://localhost/phpmyadmin
   ```

3. En el panel izquierdo, haz clic en **"Nueva"** o **"New"**

4. Crea la base de datos:
   - Nombre: `sistema_inventario`
   - Cotejamiento: `utf8mb4_general_ci`
   - Haz clic en **"Crear"**

5. Selecciona la base de datos recién creada en el panel izquierdo

6. Haz clic en la pestaña **"Importar"** en el menú superior

7. Haz clic en **"Seleccionar archivo"** o **"Choose File"**

8. Busca y selecciona el archivo:
   ```
   C:\xampp\htdocs\sistema-inventario\database\schema.sql
   ```

9. Desplázate hacia abajo y haz clic en **"Continuar"** o **"Go"**

10. Deberías ver un mensaje de éxito: ✅ "Importación finalizada correctamente"

### Opción B: Ejecutar el SQL manualmente

1. Abre phpMyAdmin: `http://localhost/phpmyadmin`

2. Haz clic en la pestaña **"SQL"** en el menú superior

3. Abre el archivo `database/schema.sql` con un editor de texto (Notepad, Notepad++, etc.)

4. Copia TODO el contenido del archivo

5. Pega el código en el área de texto de phpMyAdmin

6. Haz clic en **"Continuar"** o **"Go"**

---

## ⚙️ Paso 4: Verificar la Configuración

1. Abre el archivo de configuración:
   ```
   C:\xampp\htdocs\sistema-inventario\config\database.php
   ```

2. Verifica que los datos de conexión sean correctos:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sistema_inventario');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Por defecto está vacío en XAMPP
   ```

3. **IMPORTANTE:** Si configuraste una contraseña para MySQL, actualiza `DB_PASS`

---

## 🌐 Paso 5: Acceder al Sistema

1. Abre tu navegador web

2. Navega a:
   ```
   http://localhost/sistema-inventario
   ```

3. Deberías ver el **Dashboard** con las estadísticas del sistema

4. ¡Listo! El sistema está funcionando correctamente ✅

---

## 📂 Estructura de Carpetas

```
sistema-inventario/
│
├── database/              # Scripts SQL
│   └── schema.sql        # Estructura de base de datos
│
├── config/               # Configuración
│   └── database.php     # Conexión a BD
│
├── uploads/              # Imágenes de productos
│   ├── .htaccess        # Seguridad
│   └── README.txt       # Información
│
├── includes/             # Plantillas comunes
│   ├── header.php       # Cabecera
│   └── footer.php       # Pie de página
│
├── modules/              # Módulos del sistema
│   ├── productos/       # Gestión de inventario
│   ├── facturacion/     # Sistema de facturación
│   └── ventas/          # Histórico de ventas
│
├── assets/               # Recursos estáticos
│   ├── css/             # Estilos
│   └── js/              # Scripts JavaScript
│
├── index.php             # Página principal
└── README.md             # Documentación
```

---

## 🎯 Funcionalidades del Sistema

### 1. Módulo de Productos (Inventario)
- ➕ Agregar nuevos productos
- 📝 Editar productos existentes
- ❌ Eliminar productos
- 🖼️ Subir imágenes de productos
- 📊 Ver inventario completo

### 2. Módulo de Facturación
- 🧾 Crear nuevas facturas
- 🛒 Seleccionar múltiples productos
- 🧮 Cálculo automático de totales e IVA
- 🖨️ Imprimir facturas
- 👤 Registrar datos del cliente

### 3. Módulo de Ventas
- 📈 Ver historial completo de ventas
- 📊 Estadísticas de productos más vendidos
- 💰 Total de ventas y promedios
- 🔍 Detalle de cada factura

---

## 🛠️ Solución de Problemas

### Error: "No se puede conectar a la base de datos"
**Solución:**
1. Verifica que MySQL esté corriendo en XAMPP (luz verde)
2. Confirma que la base de datos `sistema_inventario` existe
3. Verifica las credenciales en `config/database.php`

### Error: "No se pueden subir imágenes"
**Solución:**
1. Verifica que la carpeta `uploads/` tenga permisos de escritura
2. En Windows, clic derecho → Propiedades → Seguridad → Editar
3. Asegúrate de que "Usuarios" tenga permisos de "Modificar"

### La página se muestra sin estilos
**Solución:**
1. Verifica que Apache esté corriendo
2. Revisa la consola del navegador (F12) para errores
3. Confirma que la carpeta `assets/` esté completa

### Las imágenes no se muestran
**Solución:**
1. Verifica que las imágenes estén en `uploads/`
2. Confirma que la URL base en `config/database.php` sea correcta
3. Revisa los permisos de la carpeta `uploads/`

---

## 📞 Soporte y Contacto

Si encuentras problemas durante la instalación:

1. Revisa esta guía paso a paso
2. Verifica los logs de error:
   - Apache: `C:\xampp\apache\logs\error.log`
   - MySQL: `C:\xampp\mysql\data\mysql_error.log`
3. Consulta la documentación de XAMPP: https://www.apachefriends.org/faq.html

---

## 🔒 Seguridad

**Para uso en producción:**

1. Cambia las credenciales de MySQL
2. Agrega autenticación de usuarios
3. Implementa validación adicional en el servidor
4. Usa HTTPS en lugar de HTTP
5. Actualiza regularmente XAMPP y PHP

---

## 📝 Notas Importantes

- Este sistema está diseñado para **entornos locales** con XAMPP
- El IVA está configurado al 13% (puedes modificarlo en el código)
- Las imágenes se guardan en la carpeta `uploads/`
- Por defecto, se incluyen 5 productos de ejemplo en la base de datos
- Los datos de ejemplo puedes eliminarlos desde el módulo de productos

---

**¡Disfruta del sistema! 🚀**
