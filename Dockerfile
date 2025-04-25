# Imagen base PHP 7.4 con Apache
FROM php:7.4-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libzip-dev libonig-dev libxml2-dev libicu-dev \
    zlib1g-dev libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_pgsql intl iconv zip gd ctype

# Activar mod_rewrite de Apache
RUN a2enmod rewrite

# Evitar el warning de ServerName en Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configurar Apache para servir el frontend como DocumentRoot
# Configurar Apache para servir el frontend como DocumentRoot
RUN bash -c 'cat > /etc/apache2/sites-available/000-default.conf <<EOF
<VirtualHost *:80>
    DocumentRoot /var/www/html/frontend
    <Directory /var/www/html/frontend>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF'

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html/

# Copiar todo el proyecto
COPY . .

# Variables necesarias para Composer y Symfony
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV SYMFONY_SKIP_AUTO_RUN=1

# Instalar dependencias Symfony sin ejecutar scripts automáticos
RUN composer install --working-dir=/var/www/html/backend --no-interaction --no-scripts \
    && composer require symfony/browser-kit:^4.3 --working-dir=/var/www/html/backend --no-scripts

# Ajustar permisos si el directorio existe
RUN [ -d /var/www/html/backend/var ] && chown -R www-data:www-data /var/www/html/backend/var || true

# Exponer el puerto por defecto de Apache
EXPOSE 80
