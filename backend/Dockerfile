FROM php:8.2-cli

# pdo_pgsql: base de datos PostgreSQL de Supabase (producción)
# pdo_sqlite: base de datos local opcional para desarrollo
RUN apt-get update && apt-get install -y \
        git unzip libzip-dev libsqlite3-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Las migraciones y el seed se ejecutan en el arranque del contenedor (ver CMD),
# no en build, porque en build todavía no están las variables de entorno del
# panel (APP_KEY, DB_URL...). El seeder solo inserta datos si la BD está vacía.
EXPOSE 10000
CMD php artisan migrate --force \
    && php artisan db:seed --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
