
## `Dockerfile`

### Descripción
Este Dockerfile define la configuración para construir una imagen de Docker que utiliza PHP con Apache y habilita la extensión para conectarse a bases de datos MySQL.

### Parámetros

- La imagen base de PHP con Apache.
- La instalación de paquetes y extensiones necesarias para el soporte de MySQL.

### Retorno

Este Dockerfile no retorna valores directos, pero crea una imagen de Docker lista para ejecutar aplicaciones PHP con soporte para bases de datos MySQL.


### Comportamiento

1. **Utiliza la imagen base de PHP Apache**:
   - `FROM php:8-apache`: Establece la imagen base como PHP 8 con Apache, que proporciona un entorno de servidor web con PHP preinstalado.

2. **Instala los paquetes necesarios**:
   - `RUN apt-get update && apt-get install -y default-mysql-client`: Actualiza la lista de paquetes y luego instala el cliente de MySQL, que es necesario para la interacción con bases de datos MySQL desde PHP.

3. **Instala la extensión de PHP para MySQL**:
   - `RUN docker-php-ext-install pdo_mysql`: Instala la extensión `pdo_mysql` para PHP, que permite a PHP conectarse y manejar bases de datos MySQL a través de PDO.

4. **Expone el puerto 80**:
   - `EXPOSE 80`: Indica que la imagen de Docker expondrá el puerto 80, que es el puerto estándar para el servidor web Apache. Esto permite que el contenedor sea accesible a través de este puerto.

### Código original

```Dockerfile
# Utiliza la imagen base de PHP Apache
FROM php:8-apache

# Instala los paquetes necesarios para instalar las extensiones PHP MySQL
RUN apt-get update && apt-get install -y \
    default-mysql-client

RUN docker-php-ext-install pdo_mysql

# Expone el puerto 80 para el servidor web Apache
EXPOSE 80
```

En resumen, este Dockerfile configura un entorno de contenedor para ejecutar aplicaciones PHP con soporte para MySQL, basándose en la imagen base de PHP con Apache y añadiendo los paquetes y extensiones necesarios.
