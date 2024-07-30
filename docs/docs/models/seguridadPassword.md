# SeguridadPassword 

La clase SeguridadPassword extiende de la clase [Conectar](../config/conexion.md) y se encarga de gestionar la seguridad de las contraseñas en el sistema.
Utiliza métodos para verificar, actualizar e insertar configuraciones de seguridad relacionadas con las contraseñas.

```php
<?php
class SeguridadPassword extends Conectar {
```

## add_password_info

Esta función agrega información sobre la seguridad de una contraseña para un usuario específico en la base de datos. Primero, verifica la seguridad de la contraseña, luego obtiene el ID del usuario basado en su correo electrónico y nombre de usuario, y finalmente inserta la información de seguridad de la contraseña en la tabla `tm_rob_pass`.

### **Parámetros:**

- `$email` (string): El correo electrónico del usuario.
- `$usu_name` (string): El nombre de usuario del usuario.
- `$pass` (string): La contraseña que se va a evaluar y almacenar.

### **Retorno:**

- `true` si la información de la contraseña se inserta correctamente en la base de datos.
- `false` si la inserción falla o no se realiza ninguna modificación.

### **Comportamiento:**

1. Verifica la seguridad de la contraseña usando el método `PasswordSegura`.
2. Obtiene el ID del usuario basado en el correo electrónico y el nombre de usuario.
3. Inserta la información de seguridad de la contraseña en la tabla `tm_rob_pass`.
4. Devuelve `true` si se realizó una inserción exitosa, de lo contrario, devuelve `false`.

#### **Código:**

```php
<?php
public function add_password_info($email, $usu_name, $pass) {
    $conectar = parent::conexion();
    parent::set_names();
    
    // Verificar la seguridad de la contraseña
    $seguridad = $this->PasswordSegura($pass);
    
    // Obtener el ID del usuario basado en el correo electrónico y el nombre de usuario
    $sql_get_id = "SELECT usu_id FROM tm_usuario WHERE usu_correo = :email AND usu_name = :usu_name";
    $consulta_get_id = $conectar->prepare($sql_get_id);
    $consulta_get_id->bindParam(':email', $email);
    $consulta_get_id->bindParam(':usu_name', $usu_name);
    $consulta_get_id->execute();
    
    // Obtener el ID del usuario
    $fila = $consulta_get_id->fetch(PDO::FETCH_ASSOC);
    $usu_id = $fila['usu_id'];

    $sql_insert_pass = "INSERT INTO tm_rob_pass(usu_id, mayuscula, minuscula, especiales, numeros, largo) VALUES (:usu_id, :mayuscula, :minuscula, :especiales, :numeros, :largo)";
    
    $consulta_insert_pass = $conectar->prepare($sql_insert_pass);

    // Bind de los parámetros
    $consulta_insert_pass->bindParam(':usu_id', $usu_id);
    $consulta_insert_pass->bindParam(':mayuscula', $seguridad['mayuscula'], PDO::PARAM_BOOL);
    $consulta_insert_pass->bindParam(':minuscula', $seguridad['minuscula'], PDO::PARAM_BOOL);
    $consulta_insert_pass->bindParam(':especiales', $seguridad['especiales'], PDO::PARAM_BOOL);
    $consulta_insert_pass->bindParam(':numeros', $seguridad['numero'], PDO::PARAM_BOOL);
    $consulta_insert_pass->bindParam(':largo', $seguridad['largo'], PDO::PARAM_BOOL);

    $consulta_insert_pass->execute();
    
    if ($consulta_insert_pass->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}
```

## PasswordSegura

### Descripción
Verifica la seguridad de una contraseña devolviendo un array con valores booleanos que indican si la contraseña cumple con ciertos requisitos de seguridad.

### Parámetros
- **$pass** (string): La contraseña que se va a evaluar.

### Retorno

- `array`: Un array asociativo con los siguientes valores booleanos:
  - **mayuscula**: `true` si la contraseña contiene al menos una letra mayúscula; `false` en caso contrario.
  - **minuscula**: `true` si la contraseña contiene al menos una letra minúscula; `false` en caso contrario.
  - **numero**: `true` si la contraseña contiene al menos un número; `false` en caso contrario.
  - **especiales**: `true` si la contraseña contiene al menos un carácter especial; `false` en caso contrario.
  - **largo**: `true` si la longitud de la contraseña es mayor a 7 caracteres; `false` en caso contrario.

### Comportamiento

1. Verifica si la contraseña contiene al menos una letra mayúscula.
2. Verifica si la contraseña contiene al menos una letra minúscula.
3. Verifica si la contraseña contiene al menos un número.
4. Verifica si la contraseña contiene al menos un carácter especial.
5. Verifica si la longitud de la contraseña es mayor a 7 caracteres.

#### Código

```php
<?php
// Función para verificar la seguridad de la contraseña
function PasswordSegura($pass) {
    // Objetivo es retornar un array con valores booleanos indicando si la contraseña cumple con los requisitos de seguridad
    $jsonPass = array(
        "mayuscula" => preg_match('@[A-Z]@', $pass) ? true : false,
        "minuscula" => preg_match('@[a-z]@', $pass) ? true : false,
        "numero" => preg_match('@[0-9]@', $pass) ? true : false,
        "especiales"  => preg_match('@[^\w]@', $pass) ? true : false,
        "largo" => strlen($pass) > 7 ? true : false
    );
    return $jsonPass;
}
```
## getCriteriosSeguridadPorUnidad

Obtiene los criterios de seguridad de una unidad específica desde la base de datos.
Devuelve un array con los criterios de seguridad basados en la unidad proporcionada.
En caso de error o si no se encuentran criterios, devuelve valores predeterminados.

### Parámetros
- **$unidad** (int): El identificador de la unidad para la cual se deben obtener los criterios de seguridad. Por defecto es `0`.

### Retorno
- `array`: Un array asociativo con los siguientes valores:
  - **mayuscula** (bool): `true` si se requiere al menos una letra mayúscula; `false` en caso contrario.
  - **minuscula** (bool): `true` si se requiere al menos una letra minúscula; `false` en caso contrario.
  - **numero** (bool): `true` si se requiere al menos un número; `false` en caso contrario.
  - **especiales** (bool): `true` si se requiere al menos un carácter especial; `false` en caso contrario.
  - **largo** (int): La longitud mínima de la contraseña requerida.

### Comportamiento
1. Ejecuta una consulta SQL para obtener los criterios de seguridad de la unidad especificada.
2. Si se encuentran criterios, los convierte a tipos apropiados y los devuelve.
3. Si no se encuentran criterios o si ocurre un error, devuelve valores predeterminados:
   - **mayuscula**: `true`
   - **minuscula**: `false`
   - **numero**: `false`
   - **especiales**: `false`
   - **largo**: `8`

### Código

```php
<?php
public function getCriteriosSeguridadPorUnidad($unidad = 0) {
    try {
        $sql = "SELECT mayuscula, minuscula, numeros, especiales, largo FROM tm_rob_unidad WHERE usu_unidad = :unidad";
        $params = [":unidad" => $unidad];

        $resultado = $this->ejecutarConsulta($sql, $params);

        if ($resultado) {
            $criterios = $resultado[0];
            return [
                "mayuscula" => (bool) $criterios['mayuscula'],
                "minuscula" => (bool) $criterios['minuscula'],
                "numero" => (bool) $criterios['numeros'],
                "especiales" => (bool) $criterios['especiales'],
                "largo" => (int) $criterios['largo']
            ];
        } else {
            return [
                "mayuscula" => True,
                "minuscula" => false,
                "numero" => false,
                "especiales" => false,
                "largo" => 8
            ];
        }
    } catch (PDOException $e) {
        error_log('Error en getCriteriosSeguridadPorUnidad(): ' . $e->getMessage());
        return [
            "mayuscula" => True,
            "minuscula" => false,
            "numero" => false,
            "especiales" => false,
            "largo" => 8
        ];
    }
}
```

## cumpleCriteriosSeguridad

Verifica si una contraseña cumple con los criterios de seguridad establecidos para una unidad específica. Utiliza los criterios de seguridad obtenidos desde la base de datos y evalúa la contraseña proporcionada contra estos criterios.

### Parámetros

- **$unidad** (int): El identificador de la unidad para la cual se deben obtener los criterios de seguridad.
- **$passNoCifrado** (string): La contraseña sin cifrar que se va a evaluar.

### Retorno

- `bool`: `true` si la contraseña cumple con todos los criterios de seguridad establecidos para la unidad; `false` en caso contrario o si ocurre un error.

### Comportamiento

1. Obtiene los criterios de seguridad para la unidad especificada usando `getCriteriosSeguridadPorUnidad`.
2. Verifica que los criterios obtenidos sean válidos y completos.
3. Evalúa la contraseña proporcionada contra los criterios de seguridad usando `PasswordSegura`.
4. Asegura que la contraseña tenga una longitud mínima.
5. Compara cada criterio activo con la información de seguridad de la contraseña y retorna `true` si todos los criterios se cumplen; de lo contrario, retorna `false`.

#### Código

```php
<?php
public function cumpleCriteriosSeguridad($unidad, $passNoCifrado) {
    try {
        // Obtener los criterios de seguridad de la unidad desde la base de datos
        $criterios = $this->getCriteriosSeguridadPorUnidad($unidad);

        // Si no se obtuvieron criterios válidos, retornar false
        if (empty($criterios) || !isset($criterios['mayuscula']) || !isset($criterios['minuscula']) ||
            !isset($criterios['numero']) || !isset($criterios['especiales']) || !isset($criterios['largo'])) {
            return false;
        }

        // Evaluar la contraseña con los criterios activos
        $passSegura = $this->PasswordSegura($passNoCifrado);

        // Asegurar que el criterio 'largo' siempre se cumpla
        $largoCumple = strlen($passNoCifrado) >= $criterios['largo'];

        // Comparar los criterios activos con la contraseña proporcionada
        return (
            (!$criterios['mayuscula'] || $passSegura['mayuscula']) &&
            (!$criterios['minuscula'] || $passSegura['minuscula']) &&
            (!$criterios['numero'] || $passSegura['numero']) &&
            (!$criterios['especiales'] || $passSegura['especiales']) &&
            $largoCumple
        );
    } catch (PDOException $e) {
        // Manejo de errores
        error_log('Error en cumpleCriteriosSeguridad(): ' . $e->getMessage());
        return false;
    }
}
```

## update_password_info

Actualiza la información de seguridad de la contraseña para un usuario específico en la base de datos.
La actualización incluye los criterios de seguridad de la contraseña y la fecha de modificación.

### Parámetros
- **$usu_id** (int): El identificador del usuario cuya información de contraseña se va a actualizar.
- **$pass** (string): La contraseña que se va a evaluar y usar para actualizar la información de seguridad.

### Retorno

- `bool`: `true` si la actualización se realiza correctamente; `false` en caso contrario o si ocurre un error.

### Comportamiento

1. Evalúa la seguridad de la contraseña proporcionada usando `PasswordSegura`.
2. Prepara una consulta SQL para actualizar la información de seguridad de la contraseña y la fecha de modificación.
3. Ejecuta la consulta con los parámetros adecuados.
4. Devuelve `true` si la actualización es exitosa; de lo contrario, devuelve `false`.

#### Código 

```php
<?php
public function update_password_info($usu_id, $pass) : bool {
    try {
        $seguridad = $this->PasswordSegura($pass);
        $sql = "UPDATE tm_rob_pass SET mayuscula=:mayuscula, minuscula=:minuscula, especiales=:especiales, numeros=:numeros, largo=:largo, fecha_modi=:fecha_modi WHERE usu_id = :usu_id";
        $fechaModi = date('Y-m-d H:i:s');
        
        // Parámetros para la consulta
        $params = [
            ':usu_id' => $usu_id,
            ':mayuscula' => $seguridad['mayuscula'],
            ':minuscula' => $seguridad['minuscula'],
            ':especiales' => $seguridad['especiales'],
            ':numeros' => $seguridad['numero'],
            ':largo' => $seguridad['largo'],
            ':fecha_modi' => $fechaModi
        ];

        // Ejecutar la acción
        $success = $this->ejecutarAccion($sql, $params);

        return $success;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log('Error en update_password_info(): ' . $e->getMessage());
        return false;
    }
}
```

## get_usuarios_status_passwords

Obtiene la información sobre las contraseñas de los usuarios,
incluyendo parámetros de robustez y otros detalles relacionados con el estado de las contraseñas.

### Parámetros  
- Ninguno

### Retorno  
- `array`: Un array con la siguiente información para cada usuario:
  - **nombre** (string): Nombre del usuario.
  - **apellido** (string): Apellido del usuario.
  - **correo** (string): Correo electrónico del usuario.
  - **fecha** (int): Número de días desde la última modificación de la contraseña.
  - **mayuscula** (bool): Indica si se requiere al menos una letra mayúscula.
  - **minuscula** (bool): Indica si se requiere al menos una letra minúscula.
  - **numero** (bool): Indica si se requiere al menos un número.
  - **especiales** (bool): Indica si se requiere al menos un carácter especial.
  - **largo** (int): Longitud mínima de la contraseña requerida.
  - **dias_cambio** (int): Número de días que debe pasar antes de que se requiera un cambio de contraseña.
  - **unidad** (string): Nombre de la unidad a la que pertenece el usuario.

### Comportamiento  
1. Ejecuta una consulta SQL para obtener la información de las contraseñas y parámetros de robustez de todos los usuarios.
2. Verifica si la consulta devuelve resultados.
3. Retorna un array con los datos de los usuarios si se encuentran resultados; de lo contrario, devuelve un array vacío.
4. En caso de error, se lanza una excepción.

#### Código Original
```php
<?php
public function get_usuarios_status_passwords(){
    /**
     * Retorna un array con la información de las password de los usuarios y los parámetros de robustez que cumplen
     * @autor: Nelson Navarro
     * @return array
     */
    
    try {
        $sql = "SELECT 
                    usu.usu_nom as 'nombre', 
                    usu.usu_ape as 'apellido',
                    usu.usu_correo as 'correo',
                    DATEDIFF(NOW(), rb.fecha_modi) AS 'fecha',
                    rb.mayuscula as 'mayuscula',
                    rb.minuscula as 'minuscula',
                    rb.numeros as 'numero',
                    rb.especiales as 'especiales',
                    rb.largo as 'largo',
                    runi.camb_dias AS 'dias_cambio',
                    uni.unid_nom as 'unidad'
                FROM tm_rob_pass AS rb
                JOIN tm_usuario AS usu
                ON (usu.usu_id = rb.usu_id)
                JOIN tm_rob_unidad AS runi
                ON (runi.rob_id = usu.usu_unidad)
                JOIN tm_unidad as uni
                on (uni.unid_id = usu.usu_unidad)
                WHERE  usu.fecha_elim IS NULL;";
        $userAll = $this->ejecutarConsulta($sql);

        if(is_array($userAll) && count($userAll) > 0){
            return $userAll;
        } else {
            return array(); // Devuelve un array vacío si no se encuentran usuarios con contraseñas
        }
    } catch (Exception $e) {
        ?> <script>console.log("Error al obtener usuarios con contraseñas")</script><?php
        throw $e;
    }
}
```

## get_robuste_seguridad_unidad

Obtiene todos los registros de robustez de seguridad de la tabla `tm_rob_unidad`.
Esta función recupera todos los parámetros de robustez de seguridad definidos para las unidades.

### Parámetros  
- Ninguno

### Retorno  
- `array`: Un array con todos los registros de robustez de seguridad de la tabla `tm_rob_unidad`. Cada registro contiene todos los campos de la tabla.

### Comportamiento  
1. Ejecuta una consulta SQL para seleccionar todos los registros de la tabla `tm_rob_unidad`.
2. Retorna el resultado de la consulta, que es un array con los registros de robustez de seguridad.

#### Código 

```php
<?php
public function get_robuste_seguridad_unidad() {
    $query_rob_unidad = "select * from tm_rob_unidad;";
    $result = $this->ejecutarConsulta($query_rob_unidad);
    return $result;
}
```

## update_robuste_unidad

Actualiza los parámetros de robustez de seguridad para una unidad específica en la base de datos.
Esto incluye criterios de seguridad como mayúsculas, minúsculas, números, caracteres especiales, longitud mínima y días para cambio de contraseña.

### Parámetros
- **$rob_id** (int): El identificador del registro de robustez de seguridad que se va a actualizar.
- **$usu_unidad** (int): El identificador de la unidad de usuario.
- **$mayuscula** (bool): Indica si se requiere al menos una letra mayúscula.
- **$minuscula** (bool): Indica si se requiere al menos una letra minúscula.
- **$especiales** (bool): Indica si se requiere al menos un carácter especial.
- **$numeros** (bool): Indica si se requiere al menos un número.
- **$largo** (int): Longitud mínima de la contraseña requerida.
- **$camb_dias** (int): Número de días que debe pasar antes de que se requiera un cambio de contraseña.

### Retorno
- `bool`: `true` si la actualización se realiza correctamente; `false` en caso contrario o si ocurre un error.

### Comportamiento
1. Prepara una consulta SQL para actualizar los parámetros de robustez de seguridad de una unidad específica.
2. Establece los parámetros necesarios para la consulta.
3. Ejecuta la consulta y retorna `true` si la actualización es exitosa; de lo contrario, retorna `false`.

#### Código 
```php
<?php
public function update_robuste_unidad($rob_id,$usu_unidad,$mayuscula,$minuscula,$especiales,$numeros,$largo,$camb_dias) {
    try {
        $sql = "UPDATE tm_rob_unidad SET
                  usu_unidad=:usu_unidad,
                  mayuscula=:mayuscula,
                  minuscula=:minuscula,
                  especiales=:especiales,
                  numeros=:numeros,
                  largo=:largo,
                  camb_dias=:camb_dias,
                  fecha_modi=:fecha_modi
              WHERE rob_id = :rob_id";

        $fechaModi = date('Y-m-d H:i:s');
        // Parámetros para la consulta
        $params = [
            ':rob_id' => $rob_id,
            ':usu_unidad' => $usu_unidad,
            ':mayuscula' => $mayuscula,
            ':minuscula' => $minuscula,
            ':especiales' => $especiales,
            ':numeros' => $numeros,
            ':largo' => $largo,
            ':camb_dias' => $camb_dias,
            ':fecha_modi' => $fechaModi
        ];

        // Ejecutar la acción
        $success = $this->ejecutarAccion($sql, $params);

        return $success;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log('Error en update_unidad_info(): ' . $e->getMessage());
        return false;
    }

}
```

## create_password_Configuracion_a_ultima_unidad

Inserta una configuración de robustez de seguridad para la última unidad agregada en la tabla `tm_unidad`. La configuración predeterminada incluye ciertos criterios de seguridad como mayúsculas, minúsculas, caracteres especiales, números, longitud mínima y días para cambio de contraseña.

### Parámetros
- Ninguno

### Retorno
- `bool`: `true` si la inserción se realiza correctamente; `false` en caso contrario.

### Comportamiento
1. Ejecuta una consulta SQL para obtener la última unidad agregada en la tabla `tm_unidad`.
2. Extrae el identificador de la unidad obtenida.
3. Prepara una consulta SQL para insertar una nueva configuración de robustez de seguridad para la unidad.
4. Establece los parámetros necesarios para la consulta.
5. Ejecuta la consulta de inserción y retorna `true` si la operación es exitosa; de lo contrario, retorna `false`.

#### Código
```php
public function create_password_Configuracion_a_ultima_unidad() {
    $query_unidad = "SELECT * FROM tm_unidad ORDER BY unid_id DESC LIMIT 1";
    $unidad = $this->ejecutarConsulta($query_unidad);
    $unidad_id = $unidad[0]['unid_id'];

    $sql = "INSERT INTO tm_rob_unidad (usu_unidad, mayuscula, minuscula, especiales, numeros, largo, camb_dias, fecha_modi)
          VALUES (:usu_unidad, :mayuscula, :minuscula, :especiales, :numeros, :largo, :camb_dias, :fecha_modi)";

    $fecha_modi = date('Y-m-d H:i:s');

    $params = [
        ':usu_unidad' => $unidad_id,
        ':mayuscula' => 1,
        ':minuscula' => 0,
        ':especiales' => 0,
        ':numeros' => 0,
        ':largo' => 8,
        ':camb_dias' => 90,
        ':fecha_modi' => $fecha_modi
    ];
    $success = $this->ejecutarAccion($sql, $params);
    return $success;
}
```
