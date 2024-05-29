# RegistroLog
El modelo RegistroLog controla el agregar registros y ver los datos de los registros

## add_log_registro (usu_id, op, detalle)

los parametros a agregar son el identificador del usuario que ejecuta la accion,
la operacion a ejecutar o que se ejecuto y informacion mas a desgrose de lo ejecutado

```php
<?php
class RegistroLog extends Conectar{
    public function add_log_registro($usu_id,$op, $detalle){
    /**
     * Añade un registro de log al sistema.
     *
     * Esta función inserta un nuevo registro de log en la tabla `tm_reg_log` con los detalles del usuario,
     * la operación realizada y una descripción detallada de la operación.
     *
     * @param int $usu_id El ID del usuario que realizó la operación.
     * @param string $op La operación realizada por el usuario.
     * @param string $detalle Una descripción detallada de la operación.
     * @return bool Devuelve `true` si la inserción fue exitosa, `false` en caso contrario.
     */
        $conectar = parent::conexion();
        parent::set_names();
        $sql= "INSERT INTO tm_reg_log(usu_id,op,detalle) VALUES (:usu_id, :op,:detalle)";
        $query = $conectar->prepare($sql);
        $query->bindParam(':usu_id',$usu_id);
        $query->bindParam(':op',$op);
        $query->bindParam(':detalle',$detalle);
        $query->execute();

         if ($query->rowCount() > 0) {
             return true;
         } else {
             return false;
        }
    }
```
### Donde se hacen registros log

los registros log deben estar relacionados mas a los controladores del directorio/carpeta `controller` mas que nada en los procesos que manipulan la base de dato
ademas de que se debe tener un op identificable y unico
### que se registra 

- [models usuario login](../models/usuario.md#login): se registran los inicios de sesion
- [controller categoria](../controllers/categoria.md):

## get registros log
la funcion optiene todas los detalles y en caso de que alguna informacion sea `NULL` o no este especificada dira Desconocido,
la tabla que predomina en la consulta siempre sera la de `tm_reg_log` y nos devuelve un `array` con los datos ya preparados
solamente si hay al menos 1 dato o registro.

```php
<?php
public function get_registros_log(){
        /**
        * consulta los datos del logs
        * 
        * Esta consulta optienes los datos del usuario que ejecuta cada operacion registrada
        *
        * @return array|false Retorna un array con los registros o `false` si hay un error.
        */
        $conectar = parent::conexion();
        parent::set_names();
        $sql= "SELECT 
                log.log_id     as 'id',  
                IFNULL(usu.usu_nom   ,'Desconocido') as 'nombre', 
                IFNULL(usu.usu_ape   ,'Desconocido') as 'apellido',
                IFNULL(usu.usu_correo,'Desconocido') as 'correo',
                IFNULL(usu.usu_name  ,'Desconocido') as 'usaurio',
                log.fecha      as 'fecha',
                log.op         as 'operacion',
                log.detalle    as 'detalle' 
               FROM tm_reg_log AS log 
               left JOIN tm_usuario AS usu
               ON(usu.usu_id=log.usu_id);";
        $query = $conectar->prepare($sql);
        $query->execute();
        if ($query->rowCount() > 0) {
            $resultado = $query->fetchAll(PDO::FETCH_ASSOC); 
            return $resultado;
         } else {
             return false;
        }
    }
```
