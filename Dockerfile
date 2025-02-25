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
    && docker-php-ext-install intl pdo pdo_pgsql pgsql zip opcache

# Instalar Composer globalmente
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Activar mod_rewrite para Symfony
RUN a2enmod rewrite

# Configurar Apache para usar el directorio /public como DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copiar el código del proyecto al contenedor
COPY . /var/www/html/

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Configurar permisos
RUN mkdir -p /var/www/html/var /var/www/html/vendor /var/www/html/public \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/var /var/www/html/vendor /var/www/html/public

# Instalar dependencias de PHP con Composer (sin scripts automáticos)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Limpiar caché de Symfony en producción
RUN php bin/console cache:clear --env=prod \
    && chmod -R 777 /var/www/html/var

# Eliminar las variables redundantes, Symfony usará las del archivo .env
# No sobrescribir APP_ENV ni DATABASE_URL

# Exponer el puerto 80
EXPOSE 80

# Configurar el punto de entrada
CMD ["apache2-foreground"]
