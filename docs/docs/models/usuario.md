# Usuario

el models usuario es una clase que extends desde [Conectar](../config/conectar.md)
tambien necesita el models [RegistroLog](./RegistroLog.md)

```php
<?php
require_once 'RegistroLog.php';
    class Usuario extends Conectar {
```
## Login
la funcion login se conecta ala base de dato para buscar un uusario que tenga el nombre de usuario y la contraseña iguales,
esta contraseña es pasada al formato de cifrado MD5, ademas de que por cada caso exitoso como fallido se registra en el `tm_reg_log` guardando
la direccion ip y el posible nombre del usuario con el que se intento iniciar

```php
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
esta funcion esta diseñada para capturar las ip de los usuarios/cliente tiene tres direcciones ip para capturar

1. **Client IP (IP del cliente)**: Es la dirección IP del dispositivo desde el cual un usuario accede a un servidor web. En PHP, puedes obtener esta dirección utilizando la variable `$_SERVER['REMOTE_ADDR']`. Esta IP identifica el dispositivo específico que hace la solicitud al servidor.

2. **Forwarder for (Reenviador para)**: En ocasiones, especialmente cuando hay proxies o balanceadores de carga involucrados, el servidor web puede recibir la IP del proxy o balanceador de carga en lugar de la IP del cliente real. En este caso, el encabezado `X-Forwarded-For` (o similar) se utiliza para transmitir la IP real del cliente al servidor. En PHP, puedes acceder a esta información a través de `$_SERVER['HTTP_X_FORWARDED_FOR']`.

3. **Remote Addr (Dirección remota)**: En el contexto de PHP, se refiere a la dirección IP del cliente tal como es conocida por el servidor web. Puede ser la IP del cliente real o la IP del proxy/balanceador de carga, dependiendo de la configuración del servidor y los encabezados que se incluyan en la solicitud. Puedes acceder a esta información utilizando `$_SERVER['REMOTE_ADDR']`.
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
## get tipo (usu_id)

esta funcion bustael tipo de usuario enbase al identificaro unico `usu_id`, esta consulta se hace al tabla `tm_usuario`
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

esta funcion retorna o nos da como resultado todos los tipos de usuarios que existen

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

Funcion que nos permite agregar nuevos usuarios, esta funcion tiene atributos a recibir

- `usu_nom`: Nombre
- `usu_ape`: Apellido  
- `usu_correo`: Correo del usuario
- `usu_name`: Nombre de usuario 
- `usu_pass`: La contraseña del usuario
- `fecha_crea`: La fecha en la que se crea esta usuario
- `estado`: Estado del usuario
- `usu_tipo`: Tipo de usuario 

``` php
public function add_usuario($usu_nom,$usu_ape,$usu_correo,$usu_name,$usu_pass,$fecha_crea,$estado,$usu_tipo) {
    try {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_usuario (usu_nom, usu_ape, usu_correo, usu_name,usu_pass, fecha_crea, estado,usu_tipo) VALUES (:usu_nom, :usu_ape, :usu_correo, :usu_name,:usu_pass, :fecha_crea, :estado,:usu_tipo)";  
        $consulta = $conectar->prepare($sql);  
        $consulta->bindParam(':usu_nom',$usu_nom);
        $consulta->bindParam(':usu_ape',$usu_ape);
        $consulta->bindParam(':usu_correo',$usu_correo);
        $consulta->bindParam(':usu_name',$usu_name);
        $consulta->bindParam(':usu_pass',md5($usu_pass));
        $consulta->bindParam(':fecha_crea',$fecha_crea);
        $consulta->bindParam(':estado',$estado);
        $consulta->bindParam(':usu_tipo',$usu_tipo);  
        $consulta->execute();
       
        if ($consulta->rowCount() > 0) {
            return true;
        } else {
            ?> <script>console.log("No se agrego el usuario ". $usu_nom ." ")</script><?php
            return 0;
        } 
    } catch (Exception $e) {
        ?> <script> console.log("Error catch    add_usuario") </script>  <?php
        throw $e;
    }   
  } 
```
