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

## Requisitos Técnicos
- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 o superior
- MySQL 5.7 o superior

## Instalación en Local con XAMPP
1. Instalar XAMPP desde [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Iniciar los servicios de Apache y MySQL desde el Panel de Control de XAMPP
3. Clonar este repositorio en la carpeta `htdocs` de XAMPP
4. Importar la base de datos:
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Crear una nueva base de datos llamada `format_db`
   - Importar el archivo `format_db.sql`
5. Acceder a la aplicación a través de: http://localhost/forma-t

## Fecha
14/04/2025

##Autora
Estefanía Canales