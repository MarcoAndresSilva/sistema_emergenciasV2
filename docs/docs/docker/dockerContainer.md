#DockerContainer

Este archivo `docker-compose.yml` define varios servicios que trabajan juntos para crear un entorno de desarrollo completo para un sistema de emergencia de forma local permitiendo que el equipo de desarrollo tenga el mismo entorno de trabajo.

Version: Especifica la versión de Docker Compose que se está utilizando.

Services: Define los diferentes contenedores que se ejecutarán como servicios.

- **PHP**: Este servicio crea un contenedor a partir del Dockerfile especificado, exponiendo el puerto `80` para acceder al servidor web PHP Apache. También monta el directorio local `../../sistema_emergencia` en `/var/www/html` en el contenedor para que los archivos del sistema de emergencia estén disponibles en el servidor web.

- **MySQL**: Este servicio utiliza la imagen `MySQL 8.4.0`, exponiendo el puerto `3306` para acceder a la base de datos MySQL. Define las variables de entorno necesarias para configurar la contraseña de root y el nombre de la base de datos, y monta el archivo SQL local `../../sistema_emergencia/db_emergencia.sql` en el contenedor para inicializar la base de datos.

- **PHPMyAdmin**: Este servicio utiliza la imagen phpMyAdmin para proporcionar una interfaz web para administrar la base de datos MySQL. Expone el puerto `8080` y define las variables de entorno para configurar el host de MySQL, el usuario y la contraseña.

- **MKDocs**: Este servicio utiliza la imagen de Python 3.8 para ejecutar [MkDocs]('https://www.mkdocs.org/'), un generador de sitios estáticos. Monta el directorio local `../../sistema_emergencia/docs` en `/docs` en el contenedor y ejecuta el comando para instalar MkDocs y servir el sitio de documentación en el puerto `8000`.

- **Networks**: Define una red personalizada llamada `mi_red` que permite que los contenedores se comuniquen entre sí.

## Codigo DockerContainer

```yml
version: '3.8'

services:
  php:
    build:
      context: .
      dockerfile: ./Dockerfile
    ports:
      - "80:80"
    volumes:
      - ../../sistema_emergencia:/var/www/html
    networks:
      - mi_red

  mysql:
    image: mysql:8.4.0
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: 'tu'
      MYSQL_DATABASE: emergencia_db
    volumes:
      - ../../sistema_emergencia/db_emergencia.sql:/docker-entrypoint-initdb.d/db_emergencia.sql
    networks:
      - mi_red

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    ports:
      - "8080:80"
    environment:
      PMA_HOST: mysql
      PMA_USER: root
      PMA_PASSWORD: 'tu'
    networks:
      - mi_red

  mkdocs:
    image: python:3.8
    volumes:
      - ../../sistema_emergencia/docs:/docs
    working_dir: /docs
    command: bash -c "pip install mkdocs mkdocs-with-pdf && mkdocs serve --dev-addr=0.0.0.0:8000"
    ports:
      - "8000:8000"
    networks:
      - mi_red
networks:
  mi_red:
```
