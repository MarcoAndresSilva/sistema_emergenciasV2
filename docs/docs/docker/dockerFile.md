# DockerFile

Está diseñado para crear un entorno de desarrollo PHP Apache con extensiones MySQL.
Comienza instalando los paquetes necesarios para interactuar con bases de datos MySQL.
Luego, instala la extensión pdo_mysql para que PHP pueda comunicarse con la base de datos. 
Finalmente, expone el puerto 80 para permitir el acceso al servidor web Apache que se ejecutará en el contenedor.
Una vez que construyas una imagen a partir de este Dockerfile, 
podrás ejecutar contenedores basados en esa imagen para desarrollar
y desplegar aplicaciones web PHP que necesiten interactuar con bases de datos MySQL.


## Codigo Dockerfile

los archivos estan en la carpeta o directorio `docker/`

!! Antes se debe ejecutar el [dockerContainer](./dockerContainer.md)!!

comando: `docker build .`

``` DockerFile
# Utiliza la imagen base de PHP Apache
FROM php:8-apache

# Instala los paquetes necesarios para instalar las extensiones PHP MySQL
RUN apt-get update && apt-get install -y \
    default-mysql-client

RUN docker-php-ext-install pdo_mysql


# Expone el puerto 80 para el servidor web Apache
EXPOSE 80
```

