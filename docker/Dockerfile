# Utiliza la imagen base de PHP Apache
FROM php:8-apache

# Instala los paquetes necesarios para instalar las extensiones PHP MySQL
RUN apt-get update && apt-get install -y \
    default-mysql-client

RUN docker-php-ext-install pdo_mysql


# Expone el puerto 80 para el servidor web Apache
EXPOSE 80
