# 🎵 Forma-T: Plataforma de Formación Musical

[![Render](https://img.shields.io/badge/Render-frontend%20y%20API-46e3b7)](https://forma-t.onrender.com)
[![Supabase](https://img.shields.io/badge/Supabase-PostgreSQL-3ecf8e)](https://supabase.com)
![Laravel 12](https://img.shields.io/badge/Laravel-12-ff2d20)
![PHP 8.2](https://img.shields.io/badge/PHP-8.2-777bb4)
![React 19](https://img.shields.io/badge/React-19-61dafb)
![Vite 7](https://img.shields.io/badge/Vite-7-646cff)
![Tailwind CSS 4](https://img.shields.io/badge/Tailwind%20CSS-4-06b6d4)
![PostgreSQL](https://img.shields.io/badge/Base%20de%20datos-PostgreSQL-4169e1)

> 🌐 [**Ver la demo**](https://forma-t.onrender.com): frontend y API (Docker) en Render, base de datos PostgreSQL en Supabase. Como la API está en un plan gratuito, el primer inicio de sesión puede tardar 1-2 minutos. Se puede entrar con los [usuarios de prueba](#-usuarios-de-prueba).

## 📋 Descripción del Proyecto
Forma-T es una aplicación web completa diseñada para el aprendizaje musical autodidacta a través de vídeos de YouTube. La plataforma permite a los usuarios registrarse, visualizar el catálogo de cursos y solicitar matrícula a los cursos que les interesan, mientras que un administrador gestiona usuarios, cursos y aprueba o rechaza esas solicitudes.

> [!NOTE]
> Esta versión es una evolución del proyecto original del ciclo formativo, inicialmente desarrollado en PHP y JavaScript vanilla. En esta nueva iteración, la aplicación ha sido refactorizada y migrada hacia una arquitectura moderna y escalable, apoyada en un ecosistema de frameworks de alto rendimiento.

## ✨ Características Principales
- 🔐 **Sistema de Autenticación**: Roles diferenciados para administradores y estudiantes, con autorización comprobada tanto en frontend como en backend.
- 📚 **Catálogo de Cursos**: Gestión dinámica de contenidos musicales (Teoría, Instrumento, Historia).
- 🎓 **Matriculación**: Sistema integrado para solicitar y gestionar el acceso a los cursos.
- 🎞️ **Contenido Multimedia**: Vídeo de YouTube embebido por curso.
- 👤 **Perfil de Usuario**: Cada usuario puede editar sus propios datos (nombre, teléfono, foto, contraseña).
- 📱 **Diseño Responsive**: Interfaz adaptada a todos los dispositivos para estudiar en cualquier lugar.

## 🛠️ Tecnologías Utilizadas
- **Backend**: Laravel 12 (PHP 8.2+), REST API, autenticación con Laravel Sanctum (tokens)
- **Base de Datos**: PostgreSQL (Supabase) en producción; SQLite o MySQL en local
- **Frontend**: React 19, Vite, React Router Dom 7, Axios
- **Estilos**: Tailwind CSS, CSS3 y Framer Motion para animaciones
- **Despliegue**: Render (frontend estático y API con Docker) y Supabase (base de datos)
- **Control de versiones**: Git

## 📦 Requisitos Previos
- PHP 8.2+ y Composer
- Node.js 18+ y npm
- SQLite (por defecto en `.env`), MySQL o PostgreSQL
- Git

## 📁 Estructura del Proyecto
```text
forma-t/
├── render.yaml          # Blueprint de despliegue en Render (API + frontend)
├── backend/             # API y lógica de negocio (Laravel)
│   ├── app/             # Modelos, controladores y middleware
│   ├── database/        # Migraciones, factories y seeders
│   └── routes/          # Endpoints de la API
└── frontend/            # Interfaz de usuario (React)
    ├── src/             # Componentes, páginas, contexto y servicios
    └── public/          # Assets estáticos
```

## 🗄️ Base de Datos
La aplicación utiliza PostgreSQL (alojado en Supabase) con las siguientes tablas principales:

- **users**: Gestión de usuarios (clave primaria `user_id`)
- **courses**: Catálogo de cursos (clave primaria `course_id`)
- **course_requests**: Solicitudes de matrícula, con estado `pending`/`approved`/`rejected`

## 👥 Usuarios de Prueba
Tras ejecutar `php artisan migrate --seed`, la aplicación incluye usuarios configurados para facilitar el acceso inicial:

**Administrador**
- Email: `admin@ejemplo.com`
- Contraseña: `1234admin`
- Rol: Admin (Acceso completo a gestión de cursos, usuarios y solicitudes)

**Estudiantes**
- Usuario 1: `estudiante1@ejemplo.com` (Contraseña: `1234estudiante1`)
- Usuario 2: `estudiante2@ejemplo.com` (Contraseña: `1234estudiante2`)

## 🚀 Instalación y Ejecución

### 1. Clonar el repositorio
```bash
git clone https://github.com/ecanalesn/forma-t.git
cd forma-t
```

### 2. Configuración del Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
# Configurar la conexión a la base de datos en el archivo .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
API disponible en `http://127.0.0.1:8000`.

### 3. Configuración del Frontend (React + Vite)
```bash
cd frontend
npm install
npm run dev
```
Frontend disponible en `http://localhost:5173`.

### 4. Ejecutar tests del backend
```bash
cd backend
php artisan test
```

## ☁️ Despliegue
- **Base de datos — Supabase**: proyecto de Supabase con PostgreSQL. Laravel se conecta mediante el *Session pooler* (compatible con IPv4) usando la variable `DB_URL`. Las migraciones activan Row Level Security en todas las tablas, de modo que la API REST automática de Supabase no da acceso a los datos: solo se accede a través de la API de Laravel.
- **API — Render (Docker)**: al arrancar, el contenedor ejecuta las migraciones y el seeder (que solo inserta los datos de ejemplo si la base de datos está vacía).
- **Frontend — Render (sitio estático)**: build de Vite con `VITE_API_URL` apuntando a la API.

Ambos servicios de Render están definidos en [`render.yaml`](render.yaml) (Render > New > Blueprint). Variables que hay que rellenar en el panel:

| Servicio | Variable | Valor |
|---|---|---|
| `forma-t-api` | `APP_KEY` | Resultado de `php artisan key:generate --show` |
| `forma-t-api` | `DB_URL` | Cadena de conexión del *Session pooler* de Supabase |

**Desarrollado por**: Estefanía Canales
**Fecha**: 30/07/2026
