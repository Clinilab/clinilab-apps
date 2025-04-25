# Imagen base PHP 7.4
FROM php:7.4-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libzip-dev libonig-dev libxml2-dev libicu-dev \
    zlib1g-dev libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_pgsql intl iconv zip gd ctype

# Activar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html/

# Copiar todo el proyecto
COPY . .

# Variables necesarias para Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias Symfony (backend)
RUN composer install --working-dir=/var/www/html/backend --no-interaction --no-scripts

# Limpiar caché de Symfony
RUN php backend/bin/console cache:clear

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html/backend/var

# Exponer puerto
EXPOSE 80
