# Usuario

el models usuario es una clase que extends desde [Conectar](../config/conectar.md)
tambien necesita el models [RegistroLog](./RegistroLog.md)

```php
<?php
require_once 'RegistroLog.php';
    class Usuario extends Conectar {
```
## Login

Esta función maneja el proceso de inicio de sesión de usuarios. Verifica las credenciales ingresadas por el usuario (`usu_name` y `usu_pass`), valida su estado activo (`estado=1`), y guarda la sesión del usuario si las credenciales son válidas. Además, registra un registro de inicio de sesión utilizando la clase `RegistroLog`.

#### Parámetros
La función no recibe parámetros explícitos, pero depende de los datos enviados a través de `$_POST`.

#### Comportamiento
1. Establece una conexión con la base de datos y asegura el uso de caracteres UTF-8.
2. Verifica si se ha enviado el formulario de inicio de sesión (`$_POST["enviar"]`).
3. Obtiene y limpia las variables `$_POST["usu_name"]` y `$_POST["usu_pass"]`.
4. Instancia un objeto de la clase `RegistroLog` para el registro de eventos.
5. Obtiene la dirección IP del cliente utilizando la función `GetIpCliente`.
6. Verifica si los campos de nombre de usuario y contraseña están vacíos. Si lo están, redirige a la página de inicio con un mensaje de error.
7. Ejecuta una consulta SQL para seleccionar un usuario de la tabla `tm_usuario` donde el nombre de usuario (`usu_name`) y la contraseña (`usu_pass`, cifrada con `md5`) coincidan, y el estado del usuario (`estado`) sea activo (`estado=1`).
8. Utiliza `bindValue` para vincular los parámetros de la consulta SQL.
9. Ejecuta la consulta preparada y utiliza `fetch()` para obtener el resultado de la consulta como un array asociativo.
10. Si se encuentra un usuario válido, inicia sesión guardando sus datos en `$_SESSION` y redirige al usuario a la página de inicio.
11. Registra un mensaje de registro de inicio de sesión exitoso utilizando el objeto `RegistroLog`.
12. Si no se encuentra un usuario válido, registra un mensaje de intento de inicio de sesión fallido y redirige de vuelta a la página de inicio con un mensaje de error.

```php
<?php
 public function login() {
     $conectar = parent::conexion();
     parent::set_names();
     if (isset($_POST["enviar"])) {
         $name = $_POST["usu_name"];
         $pass = $_POST["usu_pass"];
         $log= new RegistroLog;
         $ipCliente = $this->GetIpCliente();
         if (empty($name) and empty($pass) and empty($usu_tipo)) {
             header("Location:".conectar::ruta()."index.php?m=2");
             exit();
         }else{
             $sql ="SELECT * FROM tm_usuario WHERE usu_name= ? and usu_pass= ? and estado=1 ";
             $stmt=$conectar->prepare($sql);
             $stmt->bindValue(1, $name);
             $stmt->bindValue(2, md5($pass)); // cifrando a md5 la pass
             $stmt->execute();
             //se agrega variable para almacenar el usuario
             $resultado = $stmt->fetch();
             if (is_array($resultado) and count($resultado) > 0) {
                 $_SESSION["usu_id"] = $resultado["usu_id"];
                 $_SESSION["usu_nom"] = $resultado["usu_nom"];
                 $_SESSION["usu_ape"] = $resultado["usu_ape"];
                 $_SESSION["usu_tipo"] = $resultado["usu_tipo"];
                 header("Location:".Conectar::ruta()."view/Home/");
                 $mensaje="el usuario {$_SESSION['usu_nom']} {$_SESSION['usu_ape']} inició sesión desde la IP: $ipCliente";
                 $log->add_log_registro( $_SESSION["usu_id"],'Inicio sesion',$mensaje); 
                 exit();
              }else{ 
                 $mensaje="el usuario {$_POST['usu_name']} intento iniciar sesion, ip: $ipCliente";
                 $log->add_log_registro( 0,'Inicio sesion',$mensaje); 
                 header("Location:".Conectar::ruta()."index.php?m=1");
                 exit();
             }
         
         }
     }
 }
```
## GetIpCliente 
Esta función obtiene la dirección IP del cliente que está accediendo al servidor web. Utiliza varias fuentes posibles para determinar la dirección IP, incluyendo `$_SERVER['HTTP_CLIENT_IP']`, `$_SERVER['HTTP_X_FORWARDED_FOR']` y `$_SERVER['REMOTE_ADDR']`.

#### Retorna
- `string`: La dirección IP del cliente que realiza la solicitud.

#### Comportamiento

1. Primero verifica si `$_SERVER['HTTP_CLIENT_IP']` contiene una dirección IP. Si es así, utiliza esa IP.
2. Si `$_SERVER['HTTP_CLIENT_IP']` no tiene una IP definida, verifica si `$_SERVER['HTTP_X_FORWARDED_FOR']` contiene una lista de direcciones IP separadas por comas (a menudo usada para proxies y balanceadores de carga). Si es así, utiliza la primera dirección de la lista.
3. Si ninguna de las fuentes anteriores proporciona una dirección IP, utiliza `$_SERVER['REMOTE_ADDR']`, que generalmente contiene la dirección IP del cliente según el servidor web.

```php
private function GetIpCliente() {
    /**
    * Obtener la dirección IP del cliente.
    *
    * Esta función verifica varias fuentes posibles para obtener la dirección IP del cliente,
    * incluyendo $_SERVER['HTTP_CLIENT_IP'], $_SERVER['HTTP_X_FORWARDED_FOR'] y $_SERVER['REMOTE_ADDR'].
    *
    * @return string La dirección IP del cliente.
    */
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
```
## get tipo

Esta función obtiene el tipo de usuario correspondiente a un usuario específico, identificado por su `usu_id`.

### Parámetros
- `$usu_id`: Identificador único del usuario para el cual se desea obtener el tipo de usuario.

### Retorna
- Un array con la información del usuario correspondiente al `usu_id`, que incluye el tipo de usuario (`usu_tipo`).

### Comportamiento
1. Establece una conexión con la base de datos y asegura el uso de caracteres UTF-8.
2. Prepara una consulta SQL para seleccionar todos los campos de la tabla `tm_usuario` donde el `usu_id` coincida con el valor proporcionado.
3. Ejecuta la consulta preparada vinculando el parámetro `usu_id`.
4. Utiliza `fetchAll()` para obtener todos los resultados de la consulta.
5. Verifica si se encontraron resultados (`$resultado` es un array y tiene más de 0 elementos). Si se encontraron resultados, retorna el array con la información del usuario.
6. Si no se encontraron resultados, retorna `false` para indicar que no se encontró información del usuario con ese `usu_id`.

```php
public function get_tipo($usu_id){
    $conectar = parent::conexion();
    parent::set_names();
    $sql ="SELECT * FROM tm_usuario where usu_id = ? ";
    $stmt=$conectar->prepare($sql);
    $stmt->bindValue(1, $usu_id);
    $stmt->execute();
    //se agrega variable para almacenar el usuario
    $resultado = $stmt->fetchAll();
    if (is_array($resultado) and count($resultado) > 0) {
        return $resultado;
    }else {
        return false;
    }
}
```
## get todos usuarios

Esta función obtiene todos los tipos de usuarios disponibles en la base de datos.

#### Parámetros
La función no recibe parámetros explícitos.

#### Retorna
- Un array con todos los tipos de usuarios disponibles.

#### Comportamiento
1. Establece una conexión con la base de datos y asegura el uso de caracteres UTF-8.
2. Ejecuta una consulta SQL para seleccionar todos los registros de la tabla `tm_udu_tipo`, que contiene los tipos de usuario.
3. Ejecuta la consulta preparada y obtiene todos los resultados utilizando `fetchAll()`.
4. Verifica si se encontraron resultados (`$resultado` es un array y tiene más de 0 elementos). Si se encontraron resultados, los retorna.
5. Si no se encontraron resultados, muestra un mensaje o realiza un manejo de errores según sea necesario para indicar que no hay tipos de usuario registrados.


```php
public function get_todos_usuarios(){ 
    $conectar = parent::conexion();
    parent::set_names();
    $sql = "SELECT * FROM tm_udu_tipo";
    $sql = $conectar->prepare($sql);
    $sql->execute();
    $resultado = $sql ->fetchAll();
    if(is_array($resultado) and count($resultado) > 0){
        return $resultado;
    }else {
        ?> <script>console.log("No se encontraron Eventos")</script><?php
        return 0;
    }
} 
```

## add usuario

Esta función permite agregar un nuevo usuario a la base de datos con los datos proporcionados, asegurando que no exista otro usuario con el mismo nombre de usuario (`usu_name`).

#### Parámetros
- `$usu_nom`: Nombre del usuario.
- `$usu_ape`: Apellido del usuario.
- `$usu_correo`: Correo electrónico del usuario.
- `$usu_name`: Nombre de usuario único para identificación.
- `$usu_pass`: Contraseña del usuario (se almacenará cifrada).
- `$fecha_crea`: Fecha de creación del usuario.
- `$estado`: Estado del usuario (activo, inactivo, etc.).
- `$usu_tipo`: Tipo de usuario (administrador, usuario regular, etc.).
- `$usu_telefono`: Número de teléfono del usuario.

#### Retorna
Un array asociativo con:

- `status`: Estado de la operación (`success`, `error`, `warning`).
- `message`: Mensaje descriptivo de la operación realizada.

#### Comportamiento
1. Establece una conexión con la base de datos y asegura el uso de caracteres UTF-8.
2. Verifica si ya existe un usuario con el mismo nombre de usuario (`usu_name`). Si existe, devuelve un mensaje de advertencia.
3. Si no existe un usuario con el mismo nombre de usuario, procede a insertar los datos del nuevo usuario en la tabla `tm_usuario`.
4. Cifra la contraseña proporcionada (`$usu_pass`) utilizando el algoritmo MD5 antes de almacenarla en la base de datos.
5. Ejecuta la consulta preparada para insertar los datos del nuevo usuario.
6. Verifica si se pudo insertar correctamente el usuario en la base de datos. Si la inserción es exitosa, devuelve un mensaje de éxito. Si no se pudo agregar el usuario, devuelve un mensaje de error.

``` php
<?php
public function add_usuario($usu_nom, $usu_ape, $usu_correo, $usu_name, $usu_pass, $fecha_crea, $estado, $usu_tipo, $usu_telefono) {
    try {
        $conectar = parent::conexion();
        parent::set_names();

        $sql_check = "SELECT usu_name FROM tm_usuario WHERE usu_name = :usu_name";
        $consulta_check = $conectar->prepare($sql_check);
        $consulta_check->bindParam(':usu_name', $usu_name);
        $consulta_check->execute();

        if ($consulta_check->rowCount() > 0) {
            return array('status' => 'warning', 'message' => 'El usuario ya existe con ese nombre de usuario');
        }

        $sql = "INSERT INTO tm_usuario (usu_nom, usu_ape, usu_correo, usu_name, usu_pass, fecha_crea, estado, usu_tipo, usu_telefono) VALUES (:usu_nom, :usu_ape, :usu_correo, :usu_name, :usu_pass, :fecha_crea, :estado, :usu_tipo, :usu_telefono)";

        $pass_cifrado = md5($usu_pass);
        $consulta = $conectar->prepare($sql);
        $consulta->bindParam(':usu_nom', $usu_nom);
        $consulta->bindParam(':usu_ape', $usu_ape);
        $consulta->bindParam(':usu_correo', $usu_correo);
        $consulta->bindParam(':usu_name', $usu_name);
        $consulta->bindParam(':usu_pass', $pass_cifrado);
        $consulta->bindParam(':fecha_crea', $fecha_crea);
        $consulta->bindParam(':estado', $estado);
        $consulta->bindParam(':usu_tipo', $usu_tipo);
        $consulta->bindParam(':usu_telefono', $usu_telefono);

        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            return array('status' => 'success', 'message' => 'Usuario agregado correctamente');
        } else {
            return array('status' => 'error', 'message' => 'No se pudo agregar el usuario');
        }
    } catch (Exception $e) {
        return array('status' => 'error', 'message' => 'Error al agregar el usuario');
    }
}
```

## update password

Esta función permite actualizar la contraseña de un usuario en la base de datos, verificando la contraseña antigua y asegurando que la nueva contraseña sea distinta de la anterior.

#### Parámetros
- `$old_pass`: Contraseña antigua del usuario.
- `$new_pass`: Nueva contraseña que se va a asignar al usuario.
- `$usu_id`: Identificador único del usuario cuya contraseña se va a actualizar.

#### Retorna
Un array asociativo con:

- `status`: Estado de la operación (`success`, `warning`, `info`).
- `message`: Mensaje descriptivo de la operación realizada.

#### Comportamiento
1. Establece una conexión con la base de datos y asegura el uso de caracteres UTF-8.
2. Convierte la contraseña antigua (`$old_pass`) a su hash MD5 correspondiente (`$hashed_old_pass`) y ejecuta una consulta SQL para verificar si coincide con la contraseña almacenada en la base de datos para el usuario especificado.
3. Si la contraseña antigua no coincide, devuelve un mensaje de advertencia indicando que la contraseña antigua es incorrecta.
4. Verifica si la nueva contraseña (`$new_pass`) es idéntica a la contraseña antigua almacenada en forma de hash MD5. Si son iguales, devuelve un mensaje informativo indicando que la nueva contraseña debe ser diferente.
5. Si la verificación es exitosa, actualiza la contraseña del usuario a su hash MD5 correspondiente (`$hashed_new_pass`) en la base de datos.
6. Verifica si la contraseña se actualizó correctamente (`$consulta->rowCount() == 1`). Si se realizó la actualización, devuelve un mensaje de éxito. Si no se realizó ningún cambio, devuelve un mensaje informativo.

``` php
<?php
public function update_password($old_pass, $new_pass, $usu_id){
    $conectar = parent::conexion();
    parent::set_names();

    $hashed_old_pass = md5($old_pass);
    $sql = "SELECT usu_pass FROM tm_usuario WHERE usu_id = :usu_id AND usu_pass = :old_pass";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->bindParam(':old_pass', $hashed_old_pass);
    $consulta->execute();
    $user = $consulta->fetch();

    // Verificar si la contraseña antigua es correcta
    if (!$user) {
        return array('status' => 'warning', 'message' => 'La contraseña antigua no coincide');
    }

    // Verificar si la nueva contraseña es igual a la antigua
    if ($user['usu_pass'] == md5($new_pass)) {
        return array('status' => 'info', 'message' => 'La nueva contraseña debe ser distinta a la antigua');
    }

    // Actualizar la contraseña
    $hashed_new_pass = md5($new_pass); // Almacenar el resultado de md5($new_pass) en una variable
    $sql = "UPDATE tm_usuario SET usu_pass = :new_pass WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':new_pass', $hashed_new_pass); // Pasar la variable a bindParam
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    // Verificar si la contraseña se actualizó correctamente
    if ($consulta->rowCount() == 1) {
        return array('status' => 'success', 'message' => 'Contraseña actualizada con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}
```

## update phone

Esta función actualiza el número de teléfono de un usuario en la base de datos, verificando y limpiando el formato del número de teléfono antes de actualizarlo.

#### Parámetros
- `$new_phone`: Nuevo número de teléfono que se va a asignar al usuario.
- `$usu_id`: Identificador único del usuario cuyo número de teléfono se va a actualizar.

#### Retorna
Un array asociativo con:

- `status`: Estado de la operación (`success`, `warning`, `info`).
- `message`: Mensaje descriptivo de la operación realizada.

#### Comportamiento
1. Establece una conexión con la base de datos y asegura el uso de caracteres UTF-8.
2. Limpia el número de teléfono (`$new_phone`) eliminando todos los caracteres no numéricos utilizando una expresión regular.
3. Verifica que la longitud del número de teléfono limpio sea de exactamente 9 dígitos. Si no es así, devuelve un mensaje de advertencia indicando que la longitud no es correcta.
4. Ejecuta una consulta SQL que actualiza el número de teléfono (`usu_telefono`) en la tabla `tm_usuario` para el usuario especificado (`$usu_id`).
5. Verifica si el número de teléfono se actualizó correctamente (`$consulta->rowCount() == 1`). Si se realizó la actualización, devuelve un mensaje de éxito. Si no se realizó ningún cambio, devuelve un mensaje informativo.

``` php
<?php
public function update_phone($new_phone, $usu_id){
    $conectar = parent::conexion();
    parent::set_names();

    // Limpiar el número de teléfono y dejar solo los números
    $clean_phone = preg_replace('/\D/', '', $new_phone);

    // Verificar si la longitud del número de teléfono es correcta
    if (strlen($clean_phone) != 9) {
        return array('status' => 'warning', 'message' => 'La longitud del número de teléfono no es correcta');
    }

    $sql = "UPDATE tm_usuario SET usu_telefono = :new_phone WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':new_phone', $clean_phone);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    // Verificar si el número de teléfono se actualizó correctamente
    if ($consulta->rowCount() == 1) {
        return array('status' => 'success', 'message' => 'Número de teléfono actualizado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}
```

## get info usuario

Esta función obtiene la información específica de un usuario basada en su ID.

#### Parámetros
- `$usu_id`: Identificador único del usuario del cual se desea obtener la información.

#### Retorna
Un array asociativo con:

- `status`: Estado de la operación (`success`, `error`).
- `message`: Mensaje descriptivo de la operación realizada.
- `result`: Un array asociativo que contiene la información del usuario especificado, incluyendo campos como nombre, apellido, tipo de usuario, teléfono, correo y nombre de usuario.

#### Comportamiento
1. Establece una conexión con la base de datos y asegura el uso de caracteres UTF-8.
2. Ejecuta una consulta SQL que selecciona varios campos específicos de la tabla `tm_usuario` y realiza un JOIN con la tabla `tm_udu_tipo` para obtener el nombre del tipo de usuario.
3. Filtra los resultados para obtener solo la información del usuario con el ID proporcionado.
4. Verifica si se encontró exactamente un resultado (`rowCount() == 1`). Si no se encontró exactamente un resultado, devuelve un mensaje de error.
5. Si se encontró el resultado, devuelve un mensaje de éxito junto con los datos obtenidos.

``` php
public function get_info_usuario($usu_id){
    $conectar = parent::conexion();
    parent::set_names();
    $sql = 'SELECT 
            	usu.usu_nom as "Nombre",
                usu.usu_ape as "Apellido",
                tp.usu_tipo_nom as "Tipo",
                usu.usu_telefono as "Telefono",
                usu.usu_correo as "Correo",
                usu.usu_name as "Usuario"
            FROM `tm_usuario` as usu
            JOIN tm_udu_tipo as tp
            ON(tp.usu_tipo_id=usu.usu_tipo)
            WHERE usu.usu_id=:usu_id;';
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':usu_id',$usu_id);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    if ($consulta->rowCount()!=1){
        return array('status' => 'error', 'message' => 'No se puede optener los datos');
    }
    return array('status'=> 'success', 'message' =>  'se optienen los datos', 'result'=> $resultado);
}
```

## get full usuarios
Esta función obtiene todos los datos completos de usuarios junto con su tipo de usuario desde la base de datos.

#### Retorna
Un array asociativo con:

- `status`: Estado de la operación (`success`, `error`).
- `message`: Mensaje descriptivo de la operación realizada.
- `result`: Un array de arrays asociativos que contiene los datos completos de usuarios, incluyendo campos como ID, estado, nombre, apellido, tipo de usuario, teléfono, correo y nombre de usuario.

#### Comportamiento
1. Establece una conexión con la base de datos y asegura el uso de caracteres UTF-8.
2. Ejecuta una consulta SQL que selecciona varios campos de la tabla `tm_usuario` y realiza un JOIN con la tabla `tm_udu_tipo` para obtener el nombre del tipo de usuario.
3. Ejecuta la consulta preparada y obtiene todos los resultados utilizando `fetchAll(PDO::FETCH_ASSOC)`.
4. Verifica si se encontraron filas (`rowCount() > 0`). Si no se encontraron resultados, devuelve un mensaje de error.
5. Si se encontraron resultados, devuelve un mensaje de éxito junto con los datos obtenidos.

``` php
public function get_full_usuarios(){
    $conectar = parent::conexion();
    parent::set_names();
    $sql = 'SELECT 
            	usu.usu_id as "usu_id",
            	usu.estado as "estado",
            	usu.usu_nom as "Nombre",
                usu.usu_ape as "Apellido",
                tp.usu_tipo_nom as "Tipo",
                tp.usu_tipo_id as "id_tipo",
                usu.usu_telefono as "Telefono",
                usu.usu_correo as "Correo",
                usu.usu_name as "Usuario"
            FROM `tm_usuario` as usu
            JOIN tm_udu_tipo as tp
            ON(tp.usu_tipo_id=usu.usu_tipo);';
    $consulta = $conectar->prepare($sql);
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if ($consulta->rowCount()<=0){
        return array('status' => 'error', 'message' => 'No se puede optener los datos');
    }
    return array('status'=> 'success', 'message' =>  'se optienen los datos', 'result'=> $resultado);
    }
```
## disable usuario

Esta función desactiva (deshabilita) un usuario en la base de datos, estableciendo el estado del usuario como inactivo y registrando la fecha y hora de desactivación.

### Parámetros
- `$usu_id`: Identificador único del usuario que se va a desactivar.

### Retorna
Un array asociativo con:

- `status`: Estado de la operación (`success`, `info`).
- `message`: Mensaje descriptivo de la operación realizada.

### Comportamiento
1. Obtiene la fecha y hora actual para establecer como `fecha_elim`, indicando el momento en que el usuario fue desactivado.
2. Establece el estado del usuario como inactivo (`estado = 0`).
3. Actualiza estos valores en la tabla `tm_usuario` para el usuario con el ID `$usu_id`.
4. Retorna un mensaje indicando si se desactivó el usuario exitosamente o si no se realizó ningún cambio.


``` php
public function disable_usuario($usu_id){
    $conectar = parent::conexion();
    parent::set_names();

    $fecha_elim = date('Y-m-d H:i:s'); // Get current date and time
    $estado = 0; // Assuming 0 is the value for 'disabled'

    $sql = "UPDATE tm_usuario SET estado = :estado, fecha_elim = :fecha_elim WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':estado', $estado);
    $consulta->bindParam(':fecha_elim', $fecha_elim);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    if ($consulta->rowCount() == 1) {
        return array('status' => 'success', 'message' => 'Usuario desactivado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}
```

## enable usuario

Esta función activa (habilita) un usuario en la base de datos, estableciendo el estado del usuario como activo y eliminando la fecha de eliminación si existe.

### Parámetros
- `$usu_id`: Identificador único del usuario que se va a activar.

### Retorna
Un array asociativo con:
- `status`: Estado de la operación (`success`, `info`).
- `message`: Mensaje descriptivo de la operación realizada.

### Comportamiento
1. Establece el estado del usuario como activo (`estado = 1`).
2. Establece la fecha de eliminación (`fecha_elim`) como `null`, indicando que el usuario no está marcado para ser eliminado.
3. Actualiza estos valores en la tabla `tm_usuario` para el usuario con el ID `$usu_id`.
4. Retorna un mensaje indicando si se activó el usuario exitosamente o si no se realizó ningún cambio.

``` php
public function enable_usuario($usu_id){
    $conectar = parent::conexion();
    parent::set_names();

    $estado = 1; // Assuming 1 is the value for 'enabled'
    $fecha_elim = null; // Set 'fecha_elim' to null

    $sql = "UPDATE tm_usuario SET estado = :estado, fecha_elim = :fecha_elim WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':estado', $estado);
    $consulta->bindParam(':fecha_elim', $fecha_elim);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    if ($consulta->rowCount() == 1) {
        return array('status' => 'success', 'message' => 'Usuario activado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}
```

## update usuario

Esta función actualiza los datos de un usuario en la base de datos si se cumplen ciertas condiciones de validación.

### Parámetros
- `$usu_id`: Identificador único del usuario que se va a actualizar.
- `$usu_nom`: Nuevo valor del nombre del usuario.
- `$usu_ape`: Nuevo valor del apellido del usuario.
- `$usu_correo`: Nuevo valor del correo electrónico del usuario.
- `$usu_telefono`: Nuevo valor del número de teléfono del usuario.
- `$usu_name`: Nuevo nombre de usuario para el usuario.
- `$usu_tipo`: Nuevo tipo de usuario (rol).

### Retorna
- Un array asociativo con dos claves:
  - `status`: Estado de la operación (`success`, `warning`, `info`).
  - `message`: Mensaje descriptivo de la operación realizada.

### Comportamiento
1. Verifica que todos los campos obligatorios (`$usu_nom`, `$usu_ape`, `$usu_correo`, `$usu_telefono`, `$usu_name`, `$usu_tipo`) no estén vacíos. Si alguno está vacío, devuelve un mensaje de advertencia indicando que todos los campos son obligatorios.
2. Verifica específicamente que el campo `$usu_nom` no esté vacío y devuelve un mensaje de advertencia si lo está.
3. Verifica si el nombre de usuario `$usu_name` ya está siendo utilizado por otro usuario en la base de datos, excepto para el usuario con el `$usu_id` proporcionado.
4. Si el nombre de usuario no está siendo utilizado por otro usuario, procede a actualizar los datos del usuario en la base de datos.
5. Si se realiza la actualización correctamente (`$consulta->rowCount() > 0`), devuelve un mensaje de éxito. Si no se realizan cambios (`$consulta->rowCount() == 0`), devuelve un mensaje informativo.

```php
<?php
public function update_usuario($usu_id, $usu_nom, $usu_ape, $usu_correo, $usu_telefono, $usu_name, $usu_tipo){
    if(empty($usu_nom) || empty($usu_ape) || empty($usu_correo) || empty($usu_telefono) || empty($usu_name) || empty($usu_tipo)) {
        return array('status' => 'warning', 'message' => 'Todos los campos son obligatorios');
    }

    if(empty($usu_nom)) {
        return array('status' => 'warning', 'message' => 'El campo nombre no puede estar vacío');
    }

    $conectar = parent::conexion();
    parent::set_names();

    // Check if the username is being used by another user
    $sql_check = "SELECT * FROM tm_usuario WHERE usu_name = :usu_name AND usu_id != :usu_id";
    $consulta_check = $conectar->prepare($sql_check);
    $consulta_check->bindParam(':usu_name', $usu_name);
    $consulta_check->bindParam(':usu_id', $usu_id);
    $consulta_check->execute();

    if($consulta_check->rowCount() > 0) {
        return array('status' => 'warning', 'message' => 'El nombre de usuario ya está siendo utilizado por otro usuario');
    }

    // Proceed with the update if username is not being used by another user
    $sql = "UPDATE tm_usuario SET usu_nom = :usu_nom, usu_ape = :usu_ape, usu_correo = :usu_correo, usu_telefono = :usu_telefono, usu_name = :usu_name, usu_tipo = :usu_tipo WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':usu_nom', $usu_nom);
    $consulta->bindParam(':usu_ape', $usu_ape);
    $consulta->bindParam(':usu_correo', $usu_correo);
    $consulta->bindParam(':usu_telefono', $usu_telefono);
    $consulta->bindParam(':usu_name', $usu_name);
    $consulta->bindParam(':usu_tipo', $usu_tipo);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    if ($consulta->rowCount() > 0) {
        return array('status' => 'success', 'message' => 'Usuario actualizado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}
```
## update usuario tipo

Esta función actualiza el tipo de usuario de un registro específico en la base de datos.

### Parámetros
- `$usu_id`: Identificador único del usuario cuyo tipo se va a actualizar.
- `$usu_tipo`: Nuevo tipo de usuario que se va a asignar.

### Retorna
Un array asociativo con:
- `status`: Estado de la operación (`success`, `warning`, `info`).
- `message`: Mensaje descriptivo de la operación realizada.

### Comportamiento
1. Verifica que el tipo de usuario `$usu_tipo` no esté vacío.
2. Actualiza el tipo de usuario en la tabla `tm_usuario` para el usuario con el ID `$usu_id`.
3. Retorna un mensaje indicando si se realizó el cambio exitosamente o no.

``` php
<?php
public function update_usuario_tipo($usu_id, $usu_tipo){
    if(empty($usu_tipo)) {
        return array('status' => 'warning', 'message' => 'El tipo de usuario es obligatorio');
    }

    $conectar = parent::conexion();
    parent::set_names();

    $sql = "UPDATE tm_usuario SET usu_tipo = :usu_tipo WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':usu_tipo', $usu_tipo);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    if ($consulta->rowCount() > 0) {
        return array('status' => 'success', 'message' => 'Tipo de usuario actualizado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}

```
