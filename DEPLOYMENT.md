# Guía de Despliegue en Render.com

## Prerrequisitos
- Cuenta en [Render.com](https://render.com)
- Proyecto subido a GitHub, GitLab o Bitbucket

## Paso 1: Preparar el Repositorio

1. Asegúrate de que todos los archivos estén en tu repositorio:
   - `Dockerfile`
   - `docker-compose.yml`
   - `render.yaml`
   - `.dockerignore`
   - Todos los archivos de tu aplicación PHP

2. Haz commit y push de todos los cambios:
   ```bash
   git add .
   git commit -m "Preparar para despliegue en Render"
   git push origin main
   ```

## Paso 2: Crear Cuenta en Render.com

1. Ve a [render.com](https://render.com) y crea una cuenta
2. Conecta tu cuenta de GitHub/GitLab/Bitbucket

## Paso 3: Desplegar la Aplicación

### Opción A: Despliegue Automático con render.yaml

1. En Render.com, haz clic en "New +"
2. Selecciona "Blueprint"
3. Conecta tu repositorio
4. Render detectará automáticamente el archivo `render.yaml`
5. Haz clic en "Apply"

### Opción B: Despliegue Manual

#### 3.1 Crear Base de Datos MySQL
1. En Render.com, haz clic en "New +"
2. Selecciona "MySQL"
3. Configura:
   - **Name**: `forma-t-db`
   - **Database**: `format_db`
   - **User**: `forma_user`
   - **Password**: Genera una contraseña segura
   - **Plan**: Starter (gratis)
4. Haz clic en "Create Database"
5. Guarda la información de conexión

#### 3.2 Desplegar Aplicación Web
1. En Render.com, haz clic en "New +"
2. Selecciona "Web Service"
3. Conecta tu repositorio
4. Configura:
   - **Name**: `forma-t-web`
   - **Environment**: Docker
   - **Region**: El más cercano a ti
   - **Branch**: `main`
   - **Root Directory**: Dejar vacío
   - **Dockerfile Path**: `./Dockerfile`
   - **Docker Context**: `.`
5. Haz clic en "Create Web Service"

#### 3.3 Configurar Variables de Entorno
En tu servicio web, ve a "Environment" y agrega:
- `DB_HOST`: `forma-t-db.internal`
- `DB_PORT`: `3306`
- `DB_NAME`: `format_db`
- `DB_USER`: `forma_user`
- `DB_PASSWORD`: [La contraseña que generaste]

## Paso 4: Configurar Base de Datos

1. Ve a tu servicio MySQL en Render
2. Haz clic en "Connect"
3. Copia la cadena de conexión
4. Usa phpMyAdmin o cualquier cliente MySQL para conectarte
5. Importa el archivo `format_db.sql`

## Paso 5: Verificar el Despliegue

1. Tu aplicación estará disponible en: `https://forma-t-web.onrender.com`
2. Verifica que todas las páginas funcionen correctamente
3. Prueba el login y registro de usuarios

## Solución de Problemas Comunes

### Error de Conexión a Base de Datos
- Verifica que las variables de entorno estén configuradas correctamente
- Asegúrate de que el servicio MySQL esté funcionando

### Error 500
- Revisa los logs de Render en la pestaña "Logs"
- Verifica que todos los archivos PHP estén en el lugar correcto

### Página no encontrada
- Verifica que el archivo `.htaccess` esté presente
- Asegúrate de que `mod_rewrite` esté habilitado en el Dockerfile

## Costos

- **Plan Starter**: Gratis (con limitaciones)
  - 750 horas de ejecución por mes
  - 1GB de RAM
  - Base de datos MySQL de 1GB

## Monitoreo

- Render proporciona logs en tiempo real
- Puedes configurar alertas por email
- El dashboard muestra estadísticas de uso

## Actualizaciones

Para actualizar tu aplicación:
1. Haz cambios en tu código local
2. Haz commit y push a tu repositorio
3. Render detectará automáticamente los cambios y redeployará

## Soporte

- [Documentación de Render](https://render.com/docs)
- [Foro de la comunidad](https://community.render.com)
- [Soporte por email](https://render.com/support)
