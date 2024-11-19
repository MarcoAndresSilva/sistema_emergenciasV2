# Utiliza la imagen base de PHP Apache
FROM php:8-apache

# Instala los paquetes necesarios para instalar las extensiones PHP MySQL
RUN apt-get update && apt-get install -y \
    default-mysql-client

RUN docker-php-ext-install pdo_mysql
RUN  apt-get install -y ssmtp
COPY ./etc/ssmtp.conf /etc/ssmtp/ssmtp.conf

COPY ./etc/php.ini /usr/local/etc/php/php.ini

# Instala PHPUnit
RUN curl -L -o /usr/local/bin/phpunit \
  https://phar.phpunit.de/phpunit-11.phar

RUN chmod +x /usr/local/bin/phpunit
RUN touch /var/log/phpmail.log && chmod +wr /var/log/phpmail.log

# Expone el puerto 80 para el servidor web Apache
EXPOSE 80
