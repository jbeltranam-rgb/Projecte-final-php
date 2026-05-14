FROM php:8.2-apache
WORKDIR /var/www/html
# Instal·la PDO, pdo_mysql I mysqli
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
EXPOSE 80