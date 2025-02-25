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

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www/symfony

# Copiar los archivos del proyecto
COPY . .

# Instalar dependencias de Symfony
RUN composer install --no-scripts --no-autoloader

# Crear el directorio var/ manualmente si no existe
RUN mkdir -p var && chmod -R 777 var/

# Configurar Symfony para desarrollo
ENV APP_ENV=dev

# Configurar Volumes para cambios en caliente
VOLUME ["/var/www/symfony"]

# Exponer el puerto (usando el servidor embebido de PHP)
EXPOSE 8000

# Ejecutar la instalación y levantar el servidor
CMD composer install && php -S 0.0.0.0:8000 -t public
