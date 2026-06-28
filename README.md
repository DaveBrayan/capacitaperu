# Yati LMS - Documentación Técnica y Despliegue

**Versión del Sistema**: 1.0.0
**Frameworks**: Laravel 12 (Backend), TailwindCSS 4 (Frontend)
**Url de Producción**: [yati-pro.com](https://yati-pro.com)

Este documento detalla la estructura técnica, configuración y despliegue del sistema de gestión de aprendizaje (LMS) **Yati**. Está diseñado para proporcionar una visión completa a cualquier desarrollador que necesite mantener o escalar la plataforma.

---

## � 1. Estructura de Directorios

A continuación se detalla la función de cada directorio y archivo clave en la raíz del proyecto:

### Directorios Raíz

- **`/app`**
  El núcleo de la aplicación. Contiene toda la lógica de negocio.
  - **`Http/Controllers`**: Controladores que manejan las peticiones HTTP.
    - _Admin_: Lógica para el panel administrativo (Cursos, Estudiantes, Reportes).
    - _Student_: Lógica para la experiencia del alumno (Aula, Perfil).
    - _Front_: Controladores públicos (Catálogo, Landing).
  - **`Models`**: Modelos Eloquent (`User`, `Course`, `Lesson`, etc.) que interactúan con la base de datos.
  - **`Middleware`**: Filtros de peticiones HTTP (Autenticación, Roles).

- **`/bootstrap`**
  Scripts de arranque del framework. Contiene `app.php` que inicializa el sistema.

- **`/config`**
  Archivos de configuración del sistema (Base de datos, Mail, Servicios, etc.). No se deben modificar directamente en producción; usar `.env`.

- **`/database`**
  Gestión de la base de datos.
  - **`migrations`**: Historial de cambios en la estructura de la base de datos (DDL).
  - **`seeders`**: Datos de prueba o iniciales para poblar la base de datos.

- **`/public`**
  El directorio público accesible desde el navegador.
  - **`index.php`**: El punto de entrada de todas las peticiones (Front Controller).
  - **`build`**: Archivos compilados de CSS y JS (generados por Vite).
  - **`storage`**: Enlace simbólico a archivos subidos por usuarios (imágenes, documentos).

- **`/resources`**
  Archivos sin compilar y vistas.
  - **`views`**: Plantillas Blade (HTML).
  - **`css` / `js`**: Archivos fuente de estilos y scripts.

- **`/routes`**
  Definición de rutas del sistema.
  - **`web.php`**: Rutas para la interfaz web (Navegador).
  - **`console.php`**: Comandos personalizados de Artisan.

- **`/storage`**
  Almacenamiento de archivos generados por el sistema.
  - **`logs`**: Registros de errores y actividad (`laravel.log`).
  - **`app/public`**: Archivos subidos por los usuarios (Avatares, Recursos de cursos).
  - **`framework`**: Caché de sesiones y vistas compiladas.

- **`/tests`**
  Pruebas automatizadas (Unitarias y de Integración) usando PHPUnit/Pest.

- **`/vendor`**
  Librerías de terceros instaladas vía Composer (Laravel, DomPDF, etc.).

### Archivos Clave en Raíz

- **`.env`**: (Importante) Contiene las credenciales y configuración sensible (Base de datos, Claves API). **No subir al repositorio**.
- **`artisan`**: Interfaz de línea de comandos de Laravel.
- **`composer.json`**: Define las dependencias de PHP del proyecto.
- **`package.json`**: Define las dependencias de Javascript (Tailwind, Vite).
- **`vite.config.js`**: Configuración del empaquetador de assets.

---

## 🛠 2. Stack Tecnológico y Librerías

El sistema utiliza tecnologías modernas para garantizar rendimiento y escalabilidad:

### Backend (Servidor)
- **Lenguaje**: PHP ^8.2
- **Framework**: Laravel 12.x
- **Dependencias Clave**:
  - `barryvdh/laravel-dompdf`: Generación de Certificados en PDF.
  - `laravel/tinker`: Debugging interactivo.
  - `laravel/sanctum` / `fortify`: (Si aplica) Gestión de autenticación.

### Frontend (Cliente)
- **Estilos**: TailwindCSS v4.0 (Utility-first CSS).
- **Build Tool**: Vite v7.0 (Compilación ultra rápida).
- **HTTP Client**: Axios (Peticiones asíncronas).
- **JavaScript**: ES Modules nativos.

---

## � 3. Rutas y Módulos del Sistema

El sistema divide su lógica en tres áreas principales definidas en `routes/web.php`:

### A. Área Pública (`/`)
Accesible para cualquier visitante sin autenticación.
- **Landing Page**: Portada con cursos destacados y búsqueda.
- **Catálogo (`/catalogo`)**: Listado completo de cursos con filtros.
- **Detalles de Curso**: Información, temario y precios.
- **Validación**: Verificación de certificados auténticos mediante código QR/UUID.

### B. Área del Estudiante (`/estudiante`)
Requiere inicio de sesión.
- **Mi Aprendizaje**: Dashboard personal con cursos inscritos y progreso.
- **Aula Virtual (`/clase/{curso}`)**: Interfaz de consumo de contenido (Video, Quizzes).
- **Perfil**: Configuración de cuenta y seguridad.
- **Certificados**: Descarga de diplomas obtenidos.

### C. Área Administrativa (`/admin`)
Panel de gestión total. Requiere rol de Administrador o Staff.
- **Académico**: ABM de Cursos, Módulos, Lecciones, Instructores.
- **Usuarios**: Gestión de Estudiantes y Staff, reseteo de claves.
- **Ventas/Inscripciones**: Control manual de accesos.
- **Reportes**: Visualización de métricas clave.

---

## 🚀 4. Información de Despliegue y Servidor

Esta sección detalla dónde se encuentra alojado el sistema actualmente y cómo acceder a la infraestructura.

### Entorno de Producción
- **Dominio Principal**: [yati-pro.com](https://yati-pro.com)
- **Proveedor de Hosting**: [ilimitadohost.com](https://ilimitadohost.com)
- **Tipo de Servidor**: Alojamiento Compartido / cPanel.
- **Servidor Web**: Apache / LiteSpeed (según configuración del proveedor).

### Base de Datos
- **Motor**: MySQL / MariaDB.
- **Gestión**: phpMyAdmin (vía cPanel).
- **Nombre de Base de Datos**: `yati_lms` (o el prefijo asignado por el hosting).

### Credenciales y Acceso
Para realizar mantenimiento, copias de seguridad o gestión del servidor:

> ⚠️ **IMPORTANTE**: Las credenciales de acceso al **cPanel** (Panel de Control del Hosting) y **Base de Datos** están bajo custodia del **Ingeniero Reynaldo Sirpa**.

Por favor contactar directamente con el Ing. Sirpa para solicitar:
1. Acceso al Administrador de Archivos.
2. Acceso a phpMyAdmin.
3. Configuración de correos corporativos.
4. Renovación de Dominio/Hosting.

---

**© 2026 Yati LMS**. Todos los derechos reservados.
Documentación generada para uso interno del equipo de desarrollo.
