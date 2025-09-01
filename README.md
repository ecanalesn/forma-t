# Forma-T: Plataforma de Formación Musical

## Descripción
Forma-T es una aplicación web que se ha diseñado para aprender música de manera autodidacta a través de vídeos de YouTube. La plataforma permite a los usuarios registrarse, visualizar el catálogo de cursos y solicitar acceso a los contenidos.

## Características Principales
- Sistema de autenticación de usuarios (administrador y estudiantes)
- Catálogo de cursos musicales
- Sistema de solicitud de matrícula
- Contenido multimedia (videos, imágenes)
- Gestión de cursos y usuarios

## Estructura del Proyecto
```
forma-t/
├── controllers/     # Controladores de la aplicación
├── functions/       # Funciones auxiliares
├── models/         # Modelos de datos
├── public/         # Archivos públicos y recursos
├── Dockerfile      # Configuración de Docker
├── docker-compose.yml # Configuración local con Docker
├── render.yaml     # Configuración para Render.com
└── format_db.sql   # Estructura de la base de datos
```

## Base de Datos
La aplicación utiliza MySQL con las siguientes tablas principales:
- `users`: Gestión de usuarios
- `courses`: Catálogo de cursos
- `course_requests`: Solicitudes de matrícula

## Cursos Disponibles
1. Iniciación Musical
2. Música y movimiento
3. Lenguaje Musical
4. Historia de la música
5. Piano Nivel I
6. Piano Nivel II

## Credenciales de Prueba

### Administrador:
- **Email:** `admin@ejemplo.com`
- **Contraseña:** `123admin`
- **Rol:** Administrador completo (gestión de cursos, usuarios, solicitudes)

### Estudiante 1:
- **Email:** `estudiante1@ejemplo.com`
- **Contraseña:** `123estudiante1`
- **Rol:** Estudiante (ver cursos, solicitar matrícula, ver notificaciones)

### Estudiante 2:
- **Email:** `estudiante2@ejemplo.com`
- **Contraseña:** `123estudiante2`
- **Rol:** Estudiante (ver cursos, solicitar matrícula, ver notificaciones)

> **Nota:** Estas credenciales son para **pruebas y demostraciones**. En producción, cada usuario debería tener su propia cuenta.

### Prueba Rápida de la Aplicación

1. **Accede a la aplicación** (local o en Render.com)
2. **Haz clic en "Login"** en el menú
3. **Usa una de las credenciales** de arriba
4. **Explora las funcionalidades** según tu rol:
   - **Administrador:** Gestiona cursos, usuarios y solicitudes
   - **Estudiante:** Explora cursos, solicita matrícula, revisa notificaciones

### Funcionalidades por Rol

#### Como Administrador:
- Ver dashboard con estadísticas
- Gestionar cursos (crear, editar, eliminar)
- Gestionar usuarios (crear, editar, eliminar)
- Aprobar/rechazar solicitudes de matrícula
- Ver todas las solicitudes pendientes

#### Como Estudiante:
- Ver catálogo completo de cursos
- Solicitar matrícula a cursos
- Ver estado de solicitudes
- Recibir notificaciones de aprobación/rechazo
- Acceder a cursos aprobados

## Requisitos Técnicos
- PHP 8.1 o superior
- MySQL 8.0 o superior
- Apache con mod_rewrite habilitado
- Extensiones PHP: gd, pdo, pdo_mysql

## Instalación y Desarrollo

### Opción 1: Desarrollo Local con Docker (Recomendado)

1. **Instalar Docker Desktop**
   - [Windows](https://docs.docker.com/desktop/install/windows/)
   - [macOS](https://docs.docker.com/desktop/install/mac/)
   - [Linux](https://docs.docker.com/desktop/install/linux/)

2. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/forma-t.git
   cd forma-t
   ```

3. **Ejecutar con Docker Compose**
   ```bash
   docker-compose up -d
   ```

4. **Acceder a la aplicación**
   - Web: http://localhost:8080
   - Base de datos: localhost:3306

5. **Importar base de datos**
   - Usar cualquier cliente MySQL
   - Crear base de datos `format_db`
   - Importar `format_db.sql`
6. **Probar la aplicación** usando las credenciales de la sección "Credenciales de Prueba"

### Opción 2: Instalación Local con XAMPP

1. Instalar XAMPP desde [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Iniciar los servicios de Apache y MySQL desde el Panel de Control de XAMPP
3. Clonar este repositorio en la carpeta `htdocs` de XAMPP
4. Importar la base de datos:
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Crear una nueva base de datos llamada `format_db`
   - Importar el archivo `format_db.sql`
5. Acceder a la aplicación a través de: http://localhost/forma-t
6. **Probar la aplicación** usando las credenciales de la sección "Credenciales de Prueba"

## Despliegue en Producción

### Render.com (Recomendado para principiantes)

1. **Preparar el repositorio**
   ```bash
   git add .
   git commit -m "Preparar para despliegue"
   git push origin main
   ```

2. **Crear cuenta en [Render.com](https://render.com)**

3. **Desplegar automáticamente**
   - Conectar repositorio de GitHub
   - Render detectará el archivo `render.yaml`
   - Desplegar como "Blueprint"

4. **Configurar base de datos**
   - Crear servicio MySQL en Render
   - Importar `format_db.sql`
   - Configurar variables de entorno

**Ver guía completa en [DEPLOYMENT.md](DEPLOYMENT.md)**

## Fecha
14/04/2025

## Autora
Estefanía Canales