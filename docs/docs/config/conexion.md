
Clase que gestiona la conexión a una base de datos utilizando PDO. Proporciona métodos para establecer la conexión, configurar la codificación de caracteres y ejecutar consultas SQL.

### Parámetros
- **$dbh** (PDO): Instancia de la conexión PDO a la base de datos.

### Retorno
No aplica; esta clase proporciona métodos para la gestión de conexiones y consultas.

### Comportamiento
1. **`Conexion`**: Establece la conexión a la base de datos mediante PDO y maneja diferentes configuraciones para entornos locales o en Docker.
2. **`set_names`**: Configura la codificación de caracteres a UTF-8 para la conexión.
3. **`ejecutarConsulta`**: Ejecuta una consulta SQL y recupera los resultados.
4. **`ejecutarAccion`**: Ejecuta una acción SQL (INSERT, UPDATE, DELETE) y retorna el estado de la ejecución.

### Código Original
```php
<?php
    session_start();

    class Conectar {
        protected $dbh;
```
## Conexion

Establece una conexión con la base de datos utilizando PDO y maneja las excepciones en caso de error.

### Parámetros
- No requiere parámetros.

### Retorno
- `PDO`: La instancia de la conexión PDO a la base de datos.

### Comportamiento
1. Intenta establecer una conexión con la base de datos utilizando PDO.
2. Configura el modo de error de PDO para que lance excepciones (`PDO::ERRMODE_EXCEPTION`).
3. Retorna la instancia de PDO (`$this->dbh`) si la conexión es exitosa.
4. Si ocurre una excepción durante la conexión, imprime un mensaje de error y detiene la ejecución del script.

### Código 
```php
<?php
protected function Conexion() {
    try {
        // Host
        // $conectar = $this->dbh = new PDO("mysql:host=localhost;dbname=","","");
        // return $conectar;

        // docker
        $this->dbh = new PDO("mysql:host=mysql;dbname=emergencia_db", "root", "tu");
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->dbh;

        // xampp
        // $conectar = $this->dbh = new PDO("mysql:host=localhost;dbname=admem_db_emergencia","root","");
        // return $conectar;

    } catch (Exception $e) {
        print "隆Error DB !: ".$e->getMessage()."<br/>";
        die();
    }
}
```

## set_names

Configura la codificación de caracteres de la conexión de la base de datos a UTF-8.

### Parámetros
- No requiere parámetros.

### Retorno
- `PDOStatement`: El resultado de la consulta `SET NAMES 'utf8'`.

### Comportamiento
1. Ejecuta una consulta SQL para establecer la codificación de caracteres de la conexión a UTF-8.
2. Retorna el resultado de la consulta.

### Código

```php
<?php
public function set_names() {
    return $this->dbh->query("SET NAMES 'utf8'");
}
```

## ruta

Proporciona la URL base de la aplicación.

### Parámetros
- No requiere parámetros.

### Retorno
- `string`: La URL base de la aplicación (`"http://localhost/"`).

### Comportamiento
1. Retorna la URL base de la aplicación como una cadena de texto.

### Código 
```php
<?php
public function ruta() {
    return "http://localhost/";
}
```


## ejecutarConsulta

### Descripción
Ejecuta una consulta SQL utilizando parámetros opcionales y devuelve los resultados según el modo de recuperación especificado.

### Parámetros
- **$sql** (string): La consulta SQL a ejecutar.
- **$params** (array, opcional): Un array de parámetros a vincular con la consulta. Por defecto es un array vacío.
- **$fetchAll** (bool, opcional): Indica si se deben recuperar todos los resultados (`true`) o solo uno (`false`). Por defecto es `true`.

### Retorno
- `array`: Un array de resultados. Devuelve un array asociativo si `$fetchAll` es `true`, o un solo array asociativo si `$fetchAll` es `false`.

### Comportamiento
1. Establece una conexión a la base de datos mediante el método `conexion()`.
2. Configura la codificación de caracteres de la conexión a UTF-8 usando el método `set_names()`.
3. Prepara la consulta SQL con `prepare()`.
4. Vincula los parámetros con la consulta usando `bindParam()`.
5. Ejecuta la consulta con `execute()`.
6. Recupera y retorna los resultados:
   - Si `$fetchAll` es `true`, retorna todos los resultados como un array asociativo.
   - Si `$fetchAll` es `false`, retorna un solo resultado como un array asociativo.

### Código 
```php
<?php
protected function ejecutarConsulta($sql, $params = [], $fetchAll = true) {
    $conexion = $this->conexion();
    $this->set_names();
    $consulta = $conexion->prepare($sql);

    foreach ($params as $key => &$val) {
        $consulta->bindParam($key, $val);
    }

    $consulta->execute();

    if ($fetchAll) {
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}
```

## ejecutarAccion

Ejecuta una acción SQL (como una inserción, actualización o eliminación) utilizando parámetros opcionales y retorna el estado de la ejecución.

### Parámetros
- **$sql** (string): La acción SQL a ejecutar (INSERT, UPDATE, DELETE).
- **$params** (array, opcional): Un array de parámetros a vincular con la acción SQL. Por defecto es un array vacío.

### Retorno
- `bool`: `true` si la acción se ejecutó exitosamente (es decir, afectó al menos una fila), de lo contrario `false`.

### Comportamiento
1. Establece una conexión a la base de datos mediante el método `conexion()`.
2. Configura la codificación de caracteres de la conexión a UTF-8 usando el método `set_names()`.
3. Prepara la acción SQL con `prepare()`.
4. Vincula los parámetros con la acción usando `bindParam()`.
5. Ejecuta la acción con `execute()`.
6. Retorna `true` si `rowCount()` indica que se afectaron filas (mayor que 0), de lo contrario `false`.

### Código 
```php
<?php
protected function ejecutarAccion($sql, $params = []) {
    $conexion = $this->conexion();
    $this->set_names();
    $consulta = $conexion->prepare($sql);

    foreach ($params as $key => &$val) {
        $consulta->bindParam($key, $val);
    }

    $consulta->execute();

    return $consulta->rowCount() > 0;
}
```
