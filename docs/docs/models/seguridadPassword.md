# SeguridadPassword 

esta funcion extends de [Conectar](../config/conexion.md) su objetivo general es el proceso relacionados ala tabla `tm_rob_pass`
```php
<?php
class SeguridadPassword extends Conectar {
```
##	get_usuarios_status_passwords
La funcion realiza la conexión con la base de dato y hace una consulta de los usuarios personalizada
 Se recorre la fila de los datos guardando en un array datos del usuario nombre, apellido y correo, además de que características de una contraseña se cumplen

- Comprueba si la contraseña tiene mayúsculas
- Comprueba si la contraseña tiene minúsculas
- Comprueba si la contraseña tiene números
- Comprueba si la contraseña tiene caracteres especiales
- Comprueba que la contraseña tenga más de 7 caracteres
- Comprueba que la última actualización o creación sea de al menos 3 meses

El resultado de los usuarios es retornado en un array 

```php
<?php
  public function get_usuarios_status_passwords(){
    /**
     * Retorna un array con la información de las password de los usuarios y los parámetros de robustez que cumplen
     * @autor: Nelson Navarro
     * @return array
     */
      
      try {
          $conectar = parent::conexion();
          parent::set_names();
          $sql = "SELECT 
                      usu.usu_nom as 'nombre', 
                      usu.usu_ape as 'apellido',
                      usu.usu_correo as 'correo',
                      DATEDIFF(NOW(), fecha_crea) DIV 30 AS 'fecha',
                      rb.mayuscula as 'mayuscula',
                      rb.minuscula as 'minuscula',
                      rb.numeros as 'numero',
                      rb.especiales as 'especiales',
                      rb.largo as 'largo'
                  FROM tm_rob_pass as rb
                  JOIN tm_usuario as usu
                  ON(usu.usu_id=rb.usu_id)
                  WHERE usu.fecha_elim IS NULL";
          $sql = $conectar->prepare($sql);
          $sql->execute();
          $userAll = $sql ->fetchAll(PDO::FETCH_ASSOC);
            if(is_array($userAll) && count($userAll) > 0){
              return $userAll;
          } else {
              ?> <script>console.log("No se encontraron usuarios con contraseñas")</script><?php
              return array(); // Devuelve un array vacío si no se encuentran usuarios con contraseñas
          }
      } catch (Exception $e) {
          ?> <script>console.log("Error al obtener usuarios con contraseñas")</script><?php
          throw $e;
      }
    }
```
## update password info 

La funcion actualisa los datos de la contraseña de `tm_rob_pass`

```php
<?php
function update_password_info($usu_id, $pass) : bool {
    $conectar = parent::conexion();
    parent::set_names();
    $seguridad = $this->PasswordSegura($pass);
    $sql = "UPDATE tm_rob_pass SET mayuscula=:mayuscula, minuscula=:minuscula, especiales=:especiales, numeros=:numeros, largo=:largo,fecha_modi=:fecha_modi WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->bindParam(':mayuscula', $seguridad['mayuscula'], PDO::PARAM_BOOL);
    $consulta->bindParam(':minuscula', $seguridad['minuscula'], PDO::PARAM_BOOL);
    $consulta->bindParam(':especiales', $seguridad['especiales'], PDO::PARAM_BOOL);
    $consulta->bindParam(':numeros', $seguridad['numero'], PDO::PARAM_BOOL);
    $consulta->bindParam(':largo', $seguridad['largo'], PDO::PARAM_BOOL);
    $consulta->bindParam(':fecha_modi', date('Y-m-d H:i:s'));
    $consulta->execute();
    if ($consulta->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}
```
## passwordSegura (pass)

La funcion recibe como parametro `pass` que debe ser la contraseña del usuario en texto plano
el cual reglesa una array con `true` o `false` de cada caracteristica que se comprueba

- Mayúsculas
- Minusculas
- Numero
- Caracteres especiales
- largo de 8 caracteres 

``` php
<?php
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
## add password info (email, usu_name, pass)

La funcion recibe tres parametros necesarios

- `email`: el correo del usuario
- `usu_name`: nombre de usuario o username
- `pass`: la contraseña del usuario en texto plano

esta funcion lo que hace es registrar en la tabla `tm_rob_pass` los datos del usuario
sobre que tan segura es su contraseña usando `passwordSegura`

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
        ?> <script>console.log("No se agregó la información de contraseña para el usuario con correo electrónico <?php echo $email; ?> y nombre de usuario <?php echo $usu_name; ?>")</script><?php
        return 0;
    }

```
