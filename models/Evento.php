<?php

class Evento extends Conectar {

    public function get_evento() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_evento tme inner join tm_categoria tmc on tmc.cat_id=tme.cat_id inner join tm_ev_niv ten on tme.ev_niv=ten.ev_niv_id inner join tm_estado te on te.est_id=tme.ev_est ORDER BY ev_id desc";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Eventos")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> 
            <script>console.log("Error catch     get_evento")</script>
            <?php
            throw $e;
        }

    }

    

    //get_evento segun su estado
    public function get_evento_nivel($ev_niv) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_evento tme inner join tm_categoria tmc on tmc.cat_id=tme.cat_id inner join tm_ev_niv ten on tme.ev_niv=ten.ev_niv_id inner join tm_estado te on te.est_id=tme.ev_est where tme.ev_niv= ". $ev_niv ."  ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();

            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Eventos")</><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> 
            <script>console.log("Error catch     get_evento_nivel")</script>
            <?php
            throw $e;
        }

    }



    public function get_eventos_por_dia() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT DAY(ev_inicio) as dia, COUNT(*) as cantidad FROM tm_evento GROUP BY DAY(ev_inicio)";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            return $resultado;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function get_cantidad_eventos_por_nivel($ev_niv_array, $fecha_actual, $fecha_mes_anterior) {
        try {
            $conectar = parent::conexion();
    
            // Construir la condición para los niveles de emergencia
            $ev_niv_condition = implode(',', $ev_niv_array);
    
            // Consulta SQL para obtener la cantidad de eventos por nivel
            $sql = "SELECT ev_niv, COUNT(*) AS cantidad FROM tm_evento WHERE ev_niv IN ($ev_niv_condition) AND ev_inicio BETWEEN :fecha_inicio AND :fecha_fin GROUP BY ev_niv";
    
            $sql = $conectar->prepare($sql);
            $sql->bindParam(':fecha_inicio', $fecha_mes_anterior, PDO::PARAM_STR);
            $sql->bindParam(':fecha_fin', $fecha_actual, PDO::PARAM_STR);
            $sql->execute();
            $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
    
            // Inicializar el array de datos
            $datos = array(
                'total' => 0,
                'porcentaje' => 0
            );
    
            // Procesar los resultados
            foreach ($resultados as $resultado) {
                $nivel = $resultado['ev_niv'];
                $cantidad = $resultado['cantidad'];
    
                // Sumar la cantidad total
                $datos['total'] += $cantidad;
    
                // Asignar la cantidad al nivel correspondiente en el array de datos
                $datos["cantidad$nivel"] = $cantidad;
            }
    
            // Devolver los datos
            return $datos;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function get_eventos_por_rango($fecha_actual, $fecha_desde_mes_anterior) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT DATE(ev_inicio) AS fecha, COUNT(*) AS cantidad, MAX(ev_est) AS ev_est
                FROM tm_evento tme 
                INNER JOIN tm_categoria tmc ON tmc.cat_id = tme.cat_id 
                INNER JOIN tm_ev_niv ten ON tme.ev_niv = ten.ev_niv_id 
                INNER JOIN tm_estado te ON te.est_id = tme.ev_est 
                WHERE ev_inicio BETWEEN :fecha_inicio AND :fecha_fin 
                GROUP BY DATE(ev_inicio) 
                ORDER BY DATE(ev_inicio) ";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(':fecha_inicio', $fecha_desde_mes_anterior, PDO::PARAM_STR);
            $sql->bindValue(':fecha_fin', $fecha_actual, PDO::PARAM_STR);
            $sql->execute();
            $resultado = $sql->fetchAll();
            return $resultado;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_eventos_por_rango_sin_cantidad($fecha_actual, $fecha_desde_mes_anterior) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT DATE(ev_inicio) as fecha, ev_est FROM tm_evento tme 
            inner join tm_categoria tmc on tmc.cat_id=tme.cat_id 
            inner join tm_ev_niv ten on tme.ev_niv=ten.ev_niv_id 
            inner join tm_estado te on te.est_id=tme.ev_est 
            WHERE ev_inicio BETWEEN :fecha_inicio AND :fecha_fin ORDER BY ev_inicio";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(':fecha_inicio', $fecha_desde_mes_anterior, PDO::PARAM_STR);
            $sql->bindValue(':fecha_fin', $fecha_actual, PDO::PARAM_STR);
            $sql->execute();
            $resultado = $sql->fetchAll();
            return $resultado;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function datos_eventos_por_rango($fecha_actual, $fecha_desde_mes_anterior) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT *
            FROM tm_evento 
            WHERE ev_inicio BETWEEN :fecha_inicio AND :fecha_fin";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(':fecha_inicio', $fecha_desde_mes_anterior, PDO::PARAM_STR);
            $sql->bindValue(':fecha_fin', $fecha_actual, PDO::PARAM_STR);
            $sql->execute();
            $resultado = $sql->fetchAll();
            return $resultado;
        } catch (Exception $e) {
            throw $e;
        }
    }
        
    public function add_evento($ev_nom, $ev_apellido, $ev_mail, $ev_desc, $ev_est, $ev_inicio, $ev_direc, $cat_id, $ev_niv, $ev_img, $ev_telefono) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_evento (ev_nom, ev_apellido, ev_mail, ev_desc, ev_est, ev_inicio, ev_final, ev_direc, cat_id, ev_niv, ev_img, ev_telefono) 
            VALUES (:ev_nom, :ev_apellido, :ev_mail, :ev_desc, :ev_est, :ev_inicio, NULL, :ev_direc, :cat_id, :ev_niv, :ev_img, :ev_telefono)";
            $consulta  = $conectar->prepare($sql);
            $consulta->bindParam(':ev_nom', $ev_nom);
            $consulta->bindParam(':ev_apellido', $ev_apellido);
            $consulta->bindParam(':ev_mail', $ev_mail);
            $consulta->bindParam(':ev_desc', $ev_desc);
            $consulta->bindParam(':ev_est', $ev_est);
            $consulta->bindParam(':ev_inicio', $ev_inicio);
            $consulta->bindParam(':ev_direc', $ev_direc);
            $consulta->bindParam(':cat_id', $cat_id);
            $consulta->bindParam(':ev_niv', $ev_niv);
            $consulta->bindParam(':ev_img', $ev_img);
            $consulta->bindParam(':ev_telefono', $ev_telefono);
            try {
                $consulta->execute();
            } catch (PDOException $e) {
                echo "Error al ejecutar la consulta: " . $e->getMessage();
            }            
            if ($consulta->rowCount() > 0) {
                return true;
            } else { 
                return false;
            }
        } catch (Exception $e) {
            echo "Error catch add_evento: " . $e->getMessage();
            throw $e;
        }
    }

    //update_imagen_evento segun id
	public function update_imagen_evento($ev_id, $ev_img) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_evento SET  ev_img = :ev_img WHERE ev_id = :ev_id ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':ev_id',$ev_id);
            $consulta->bindParam(':ev_img',$ev_img);
			
			if ($consulta->execute()) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar la imagen del evento")</script><?php
                return 0;
            }
        } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_imagen_evento")</script>
            <?php
            throw $e;
        }
        
	}
    
    //update_nivelpeligro_evento segun id
	public function update_nivelpeligro_evento($ev_id, $ev_niv) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_evento SET  ev_niv=:ev_niv WHERE ev_id = " . $ev_id . " ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':ev_niv',$ev_niv);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar la asignacion del evento")</script><?php
                return 0;
            }
        } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_asignacion_evento")</script>
            <?php
            throw $e;
        }
	}

    //get_evento_id segun disponibilidad
    public function get_evento_id($ev_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_evento where ev_id = '". $ev_id ."' ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Eventos")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     get_evento_id")</script> <?php
            throw $e;
        }

    }

    public function obtener_usuario_id($nombre, $apellido) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT usu_id FROM tm_usuario WHERE usu_nom = :nombre AND usu_ape = :apellido";
            $consulta = $conectar->prepare($sql);
            $consulta->bindValue(':nombre', $nombre);
            $consulta->bindValue(':apellido', $apellido);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    
            if ($resultado) {
                return $resultado['usu_id'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "<script>console.log('Error catch obtener_usuario_id')</script>";
            throw $e;
        }
    }
    
    public function cerrar_evento($ev_id, $ev_final, $ev_est, $detalle_cierre, $motivo_cierre, $usu_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
    
            // Iniciar transacción
            $conectar->beginTransaction();
    
            // Actualizar la tabla tm_evento
            $sql_evento = "UPDATE tm_evento SET ev_final = :ev_final, ev_est = :ev_est WHERE ev_id = :ev_id";
            $consulta_evento = $conectar->prepare($sql_evento);
            $consulta_evento->bindValue(':ev_final', $ev_final);
            $consulta_evento->bindValue(':ev_est', $ev_est);
            $consulta_evento->bindValue(':ev_id', $ev_id);
            $consulta_evento->execute();
    
            // Insertar en la tabla tm_ev_cierre
            $sql_cierre = "INSERT INTO tm_ev_cierre (usu_id, ev_id, detalle, motivo) VALUES (:usu_id, :ev_id, :detalle, :motivo)";
            $consulta_cierre = $conectar->prepare($sql_cierre);
            $consulta_cierre->bindValue(':usu_id', $usu_id);
            $consulta_cierre->bindValue(':ev_id', $ev_id);
            $consulta_cierre->bindValue(':detalle', $detalle_cierre);
            $consulta_cierre->bindValue(':motivo', $motivo_cierre);
            $consulta_cierre->execute();
    
            // Confirmar transacción
            $conectar->commit();
    
            return true;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conectar->rollBack();
            echo "<script>console.log('Error catch cerrar_evento')</script>";
            throw $e;
        }
    }

    public function get_evento_where($where){
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT tme.ev_id, tme.ev_nom, tme.ev_mail, tme.ev_desc, te.est_nom, tme.ev_inicio, tme.ev_final, tme.ev_direc, tmc.cat_nom, ten.ev_niv_nom,tme.ev_est,tme.cat_id,tmc.cat_id, ten.ev_niv_id,GROUP_CONCAT(tu.unid_nom SEPARATOR ' - ') AS unidades
            FROM tm_evento tme
            INNER JOIN tm_ev_tm_unid temu ON tme.ev_id = temu.ev_id 
            INNER JOIN tm_unidad tu ON tu.unid_id = temu.unid_id
            INNER JOIN tm_categoria tmc ON tmc.cat_id = tme.cat_id 
            INNER JOIN tm_ev_niv ten ON tme.ev_niv = ten.ev_niv_id 
            INNER JOIN tm_estado te ON te.est_id = tme.ev_est 
            WHERE 
            tme.ev_id LIKE :where or
            tme.ev_nom LIKE :where or 
            tme.ev_mail LIKE :where or 
            tme.ev_desc LIKE :where or 
            te.est_nom LIKE :where or 
            tu.unid_nom LIKE :where or
            tme.ev_inicio LIKE :where or 
            tme.ev_final LIKE :where or
            tme.ev_direc LIKE :where or 
            tmc.cat_nom Like :where or
            ten.ev_niv_nom LIKE :where
            GROUP BY tme.ev_id, tme.ev_nom, tme.ev_mail, tme.ev_desc, te.est_nom, tme.ev_inicio, tme.ev_final, tme.ev_direc, tmc.cat_nom, ten.ev_niv_nom";
            $sql = $conectar->prepare($sql);
            $whereValue = '%' . $where . '%';
            $sql->bindValue(":where", $whereValue);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            ?> 
            <script>console.log("Error catch     get_evento")</script>
            <?php
            throw $e;
        }
    }

    public function get_id_ultimo_evento(){
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT ev_id FROM tm_evento ORDER BY ev_id DESC LIMIT 1";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchColumn();

            if ($resultado !== false) {
                return $resultado;
            }else{
                ?> <script>console.log("No logro obtener el ultimo ID")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> 
            <script>console.log("Error catch     get_evento")</script>
            <?php
            throw $e;
        }
    }
}