# Usa la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zlib1g-dev \
    libxml2-dev \
    libonig-dev \
    libssl-dev \
    && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql pgsql zip opcache

# Instalar Composer globalmente
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Configuración de Apache (activar mod_rewrite para Symfony)
RUN a2enmod rewrite

# Copiar el código del proyecto al contenedor
COPY . /var/www/html/

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Crear las carpetas necesarias si no existen y cambiar permisos
RUN mkdir -p /var/www/html/var /var/www/html/vendor /var/www/html/public \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/var /var/www/html/vendor /var/www/html/public

# Instalar dependencias de PHP con Composer (sin scripts automáticos)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Limpiar caché de Symfony manualmente en producción
RUN php bin/console cache:clear --env=prod

# Configuración de entorno (ajustar APP_ENV y APP_DEBUG según sea necesario)
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Variables de entorno para PostgreSQL
ENV DATABASE_URL="pgsql://user:password@postgres:5432/dbname"

# Exponer el puerto 80 para Apache
EXPOSE 80

# Configurar el punto de entrada (entrypoint)
CMD ["apache2-foreground"]
