# Usa la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias del sistema
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
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

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
    && chown -R www-data:www-data /var/www/html/var /var/www/html/vendor /var/www/html/public \
    && chmod -R 775 /var/www/html/var /var/www/html/vendor /var/www/html/public

# Cambiar permisos para Symfony
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor /var/www/html/public \
    && chmod -R 775 /var/www/html/var /var/www/html/vendor /var/www/html/public

# Instalar dependencias de PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# Configuración de entorno (ajustar APP_ENV según tu necesidad)
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Exponer el puerto 80 para Apache
EXPOSE 80

# Configurar el punto de entrada (entrypoint)
CMD ["apache2-foreground"]
