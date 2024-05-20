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
}
