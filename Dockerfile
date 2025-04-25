# Imagen base PHP 7.4 con Apache
FROM php:7.4-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libzip-dev libonig-dev libxml2-dev libicu-dev \
    zlib1g-dev libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_pgsql intl iconv zip gd ctype

# Activar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html/

# Copiar el proyecto completo
COPY . .

# Variables necesarias para Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias del backend
RUN composer install --working-dir=/var/www/html/backend --no-interaction --no-scripts \
    && composer require symfony/browser-kit:^4.3 --working-dir=/var/www/html/backend

# Opción: puedes dejar esta línea si estás seguro que la app está bien configurada
# RUN php backend/bin/console cache:clear

# Establecer permisos correctos para Symfony
RUN chown -R www-data:www-data /var/www/html/backend/var

# Exponer el puerto 80
EXPOSE 80
