# backend/Dockerfile
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Set document root
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Copy files
COPY ./api /var/www/html/api
COPY ./config /var/www/html/config

# Set permissions
RUN chown -R www-data:www-data /var/www/html
