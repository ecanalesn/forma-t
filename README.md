# Forma-T: Plataforma de Formación Musical

## Descripción
Forma-T es una aplicación web que se ha diseñado para aprender música de manera autodidacta a través de vídeos de YouTube. La plataforma permite a los usuarios registrarse, visualizar el catálogo de cursos y solicitar acceso a los contenidos.

## Características principales
- Sistema de autenticación de usuarios (administrador y estudiantes)
- Catálogo de cursos musicales
- Sistema de solicitud de matrícula
- Contenido multimedia (videos, imágenes)
- Gestión de cursos y usuarios

## Estructura del proyecto
forma-t/
├── controllers/     # Controladores de la aplicación
├── functions/       # Funciones auxiliares
├── models/          # Modelos de datos
├── public/          # Archivos públicos y recursos
└── format_db.sql    # Estructura de la base de datos

## Base de datos
La aplicación utiliza MySQL con las siguientes tablas principales:
- `users`: Gestión de usuarios
- `courses`: Catálogo de cursos
- `course_requests`: Solicitudes de matrícula

## Cursos disponibles
1. Iniciación Musical
2. Música y movimiento
3. Lenguaje Musical
4. Historia de la música
5. Piano Nivel I
6. Piano Nivel II

## Requisitos técnicos
- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 o superior
- MySQL 5.7 o superior

## Instalación en local con XAMPP
1. Instalar XAMPP desde https://www.apachefriends.org/
2. Iniciar los servicios de Apache y MySQL desde el Panel de Control de XAMPP
3. Clonar este repositorio en la carpeta htdocs de XAMPP
4. Importar la base de datos:
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Crear una nueva base de datos llamada format_db
   - Importar el archivo format_db.sql
5. Acceder a la aplicación a través de: http://localhost/forma-t

## Accesos de prueba:
Se incluyen usuarios de prueba para acceder a la plataforma:
1. Administrador
   - Usuario: admin@ejemplo.com
   - Contraseña: admin123

2. Estudiante 1
   - Usuario: estudiante1@ejemplo.com
   - Contraseña: estudiante123

3. Estudiante 2
   - Usuario: estudiante2@ejemplo.com
   - Contraseña: estudiante123

Estas credenciales están precargadas en el archivo format_db.sql.

## Fecha
14/04/2025

## Autora
Estefanía Canales
