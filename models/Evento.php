<?php

class Evento extends Conectar {

    public function get_evento() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = 'SELECT
                        us.usu_name as "ev_nom", us.usu_ape as "ev_ape",
                        tme.ev_direc as "ev_direc",
                        tmc.cat_nom as "cat_nom",
                        tme.ev_desc as "ev_desc",
                        tme.ev_inicio as "ev_inicio",
                        tmc.cat_id as "cat_id",
                        tme.ev_direc as "ev_direc",
                        tme.ev_id as "ev_id",
                        ten.ev_niv_id as "ev_niv_id",
                        tme.ev_est as "ev_est",
                        tme.ev_final as "ev_final",
                        tme.ev_latitud as "ev_latitud",
                        tme.ev_longitud as "ev_longitud"
                    FROM
                        tm_evento tme
                    INNER JOIN tm_categoria tmc ON
                        tmc.cat_id = tme.cat_id
                    INNER JOIN tm_ev_niv ten ON
                        tme.ev_niv = ten.ev_niv_id
                    INNER JOIN tm_estado te ON
                        te.est_id = tme.ev_est
                    INNER JOIN tm_usuario us
                    on us.usu_id=tme.usu_id
                    INNER JOIN tm_seccion ts ON us.usu_seccion = ts.sec_id
                    ORDER BY
                        ev_id
                    DESC;';
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Eventos")</script><?php
                return [];
            }
        } catch (Exception $e) {
            ?> 
            <script>console.log("Error catch     get_evento")</script>
            <?php
             return [];
        }

    }

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

    public function get_cantidad_eventos_por_nivel($fecha_actual, $fecha_mes_anterior) {
        try {
           $sql = 'SELECT
                        n.ev_niv_id AS "id",
                        n.ev_niv_nom as "nombre",
                        COUNT(e.ev_niv) AS cantidad
                    FROM tm_ev_niv as n
                    LEFT JOIN tm_evento AS e
                ON(e.ev_niv = n.ev_niv_id)
                AND e.ev_inicio between :fecha_inicio and  :fecha_fin
                GROUP BY n.ev_niv_id, n.ev_niv_nom
                order by n.ev_niv_id;
                 ';

            $params = [':fecha_inicio'=> $fecha_mes_anterior ,':fecha_fin'=> $fecha_actual];
            $resultados = $this->ejecutarConsulta($sql, $params);

           foreach ($resultados as $key => $value) {
               $resultado[$value['nombre']] = [
                   'cantidad' => $value['cantidad'],
                   'id' => $value['id']
               ];
           }
           return $resultado;

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

    public function get_eventos_estadisticas_por_fecha(string $fecha_actual, string $fecha_desde_mes_anterior):array {
        try {
              // Usamos COALESCE para asegurar que los resultados sean 0 si no hay eventos
        $sql = "SELECT
                    COALESCE(COUNT(CASE WHEN ev_est = 1 THEN 1 END), 0) AS eventos_abiertos,
                    COALESCE(COUNT(CASE WHEN ev_est = 2 THEN 1 END), 0) AS eventos_cerrados,
                    COALESCE(COUNT(CASE WHEN ev_est = 3 THEN 1 END), 0) AS eventos_controlados,
                    COALESCE(COUNT(CASE WHEN ev_est NOT IN (1, 2, 3) THEN 1 END), 0) AS eventos_ext,
                    COALESCE(COUNT(*), 0) AS cantidad_total
                FROM tm_evento tme
                INNER JOIN tm_categoria tmc ON tmc.cat_id = tme.cat_id
                INNER JOIN tm_ev_niv ten ON tme.ev_niv = ten.ev_niv_id
                INNER JOIN tm_estado te ON te.est_id = tme.ev_est
                WHERE ev_inicio BETWEEN :fecha_inicio AND :fecha_fin";
            $params = [':fecha_inicio'=> $fecha_desde_mes_anterior,
                    ':fecha_fin'=> $fecha_actual];
            $resultado = $this->ejecutarConsulta($sql,$params, false);
            if ($resultado['cantidad_total'] != 0 && isset($resultado['cantidad_total'])) {
                $porcentaje_abiertas = round(($resultado['eventos_abiertos'] / $resultado['cantidad_total']) * 100, 2);
                $porcentaje_cerradas = round(($resultado['eventos_cerrados'] / $resultado['cantidad_total']) * 100, 2);
            } else {
                $porcentaje_abiertas = 0;
                $porcentaje_cerradas = 0;
            }

            // Retornamos el resultado de la consulta con los cálculos
            return array_merge($resultado, [
                'porcentaje_abiertas' => $porcentaje_abiertas,
                'porcentaje_cerradas' => $porcentaje_cerradas
            ]);
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
        
    public function add_evento($usu_id, $ev_desc, $ev_est, $ev_inicio, $ev_direc, $ev_latitud, $ev_longitud, $cat_id, $ev_niv, $ev_img) {
        if (empty($usu_id) || empty($ev_desc) || empty($ev_est) || empty($ev_inicio) || empty($ev_direc) || empty($cat_id)) {
            return [
                'status' => 'warning',
                'message' => 'Faltan datos obligatorios. Por favor, asegúrate de completar todos los campos necesarios.'
            ];
        }
    
        try {
            $conectar = parent::conexion();
            parent::set_names();
    
            $sql = "INSERT INTO tm_evento (usu_id, ev_desc, ev_est, ev_inicio, ev_final, ev_direc, ev_latitud, ev_longitud, cat_id, ev_niv, ev_img) 
            VALUES (:usu_id, :ev_desc, :ev_est, :ev_inicio, NULL, :ev_direc, :ev_latitud, :ev_longitud, :cat_id, :ev_niv, :ev_img)";
    
            $consulta = $conectar->prepare($sql);
    
            $consulta->bindParam(':usu_id', $usu_id);
            $consulta->bindParam(':ev_desc', $ev_desc);
            $consulta->bindParam(':ev_est', $ev_est);
            $consulta->bindParam(':ev_inicio', $ev_inicio);
            $consulta->bindParam(':ev_direc', $ev_direc);
            $consulta->bindParam(':ev_latitud', $ev_latitud);
            $consulta->bindParam(':ev_longitud', $ev_longitud);
            $consulta->bindParam(':cat_id', $cat_id);
            $consulta->bindParam(':ev_niv', $ev_niv);
            $consulta->bindParam(':ev_img', $ev_img);

            try {
                $consulta->execute();
                return [
                    'status' => 'success',
                    'message' => 'Evento agregado exitosamente.'
                ];
            } catch (PDOException $e) {
                return [
                    'status' => 'error',
                    'message' => 'Error al ejecutar la consulta: ' . $e->getMessage()
                ];
            }
    
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error catch add_evento: ' . $e->getMessage()
            ];
        }
    } 

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

    public function update_imagen_cierre($ev_id, $ev_img) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_ev_cierre SET  adjunto = :adjunto WHERE ev_id = :ev_id ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':ev_id',$ev_id);
            $consulta->bindParam(':adjunto',$adjunto);
			
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
    
    public function update_nivelpeligro_evento(int $ev_id, int $nivel_nuevo):array {
		try {
			$sql = "UPDATE tm_evento SET  ev_niv=:ev_niv WHERE ev_id = :ev_id ";
      $params = [':ev_id' => $ev_id, ':ev_niv' => $nivel_nuevo];
      $datosEvento = $this->get_evento_id($ev_id);
      if ($datosEvento['ev_niv'] == $nivel_nuevo) {
          return ["status" => "info", "message" => "El nivel de peligro ya es el mismo."];
      }
      $consulta = $this->ejecutarAccion($sql, $params);
			
			if ($consulta) {
          return ["status" => "success", "message" => "Nivel de peligro actualizado exitosamente."];
      } else {
          return ["status" => "warning", "message" => "No se logro actualizar el nivel de peligro del evento."];
      }
    } catch (Exception $e) {
       throw $e;
    }
	}

    public function get_evento_id($ev_id) {
        try {
                $sql = "SELECT
                e.ev_id as 'ev_id',
                e.ev_direc as 'ev_direc',
                e.ev_img as 'ev_img',
                e.ev_latitud as 'ev_latitud',
                e.ev_longitud as 'ev_longitud',
                e.ev_desc as 'ev_desc',
                e.ev_inicio as 'ev_inicio',
                e.ev_final as 'ev_final',
                e.ev_est as 'ev_est',
                c.cat_id as 'cat_id',
                c.cat_nom as 'cat_nom',
                e.ev_niv as 'ev_niv',
                n.ev_niv_nom as 'niv_nom',
                u.usu_id as 'usu_id',
                u.usu_nom as 'usu_nombre',
                u.usu_correo as 'usu_correo',
                u.usu_ape as 'usu_ape'
                FROM tm_evento as e
                inner JOIN tm_usuario as u
                 on (u.usu_id = e.usu_id)
                inner join tm_categoria as c
                on (c.cat_id = e.cat_id)
                inner join tm_ev_niv as n
                on (n.ev_niv_id = e.ev_niv)
                where ev_id = :ev_id ";
            $params = [':ev_id' => $ev_id];
            $resultado = $this->ejecutarConsulta($sql, $params, false);
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                return [$ev_id];
            }
        } catch (Exception $e) {
            throw $e;
        }

    }
    public function informacion_evento_completa($ev_id) {
      $sql = 'SELECT
                cat.cat_nom as "cat_nom",
                usr.usu_nom as "usu_nom",
                usr.usu_ape as "usu_ape",
                est.est_nom as "est_nom",
                eve.ev_desc as "ev_desc",
                nv.ev_niv_nom as "ev_niv_nom",
                eve.ev_latitud as "eve_latitud",
                eve.ev_longitud as "eve_longitud",
                eve.ev_id as "id_evento",
                eve.ev_direc as "ev_direc"
              FROM tm_evento as eve
              JOIN tm_categoria as cat
              ON(cat.cat_id=eve.cat_id)
              JOIN tm_usuario as usr
              ON(usr.usu_id=eve.usu_id)
              JOIN tm_estado as est
              ON(est.est_id=eve.ev_est)
              JOIN tm_ev_niv as nv
              ON(nv.ev_niv_id=eve.ev_niv)
              WHERE eve.ev_id=:id_evento';

    $params = [':id_evento' => $ev_id];
    $resultado = $this->ejecutarConsulta($sql, $params,false);

    if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
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

    public function cerrar_evento($ev_id, $ev_final, $ev_est, $detalle_cierre, $motivo_cierre, $usu_id, $adjunto) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
    
            // Verificar que todos los valores no estén vacíos
            if (empty($ev_id) || empty($ev_final) || empty($ev_est) || empty($detalle_cierre) || empty($motivo_cierre) || empty($usu_id)) {
                return "Error: Datos incompletos";
            }
    
            // Iniciar transacción
            $conectar->beginTransaction();
    
            // Actualizar la tabla tm_evento
            $sql_evento = "UPDATE tm_evento SET ev_final = :ev_final, ev_est = :ev_est WHERE ev_id = :ev_id";
            $consulta_evento = $conectar->prepare($sql_evento);
            $consulta_evento->bindValue(':ev_final', $ev_final);
            $consulta_evento->bindValue(':ev_est', $ev_est);
            $consulta_evento->bindValue(':ev_id', $ev_id);
            $resultado_evento = $consulta_evento->execute();
    
            // Verificar si la consulta de actualización fue exitosa
            if (!$resultado_evento) {
                throw new Exception("Error al actualizar el evento.");
            }
    
            // Insertar en la tabla tm_ev_cierre
            $sql_cierre = "INSERT INTO tm_ev_cierre (usu_id, ev_id, detalle, motivo,adjunto) VALUES (:usu_id, :ev_id, :detalle, :motivo,:adjunto)";
            $consulta_cierre = $conectar->prepare($sql_cierre);
            $consulta_cierre->bindValue(':usu_id', $usu_id);
            $consulta_cierre->bindValue(':ev_id', $ev_id);
            $consulta_cierre->bindValue(':detalle', $detalle_cierre);
            $consulta_cierre->bindValue(':motivo', $motivo_cierre);
            $consulta_cierre->bindValue(':adjunto', $adjunto);
            $resultado_cierre = $consulta_cierre->execute();
    
            // Verificar si la consulta de inserción fue exitosa
            if (!$resultado_cierre) {
                throw new Exception("Error al insertar el cierre del evento.");
            }
    
            // Confirmar transacción
            $conectar->commit();
    
            return true;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conectar->rollBack();
            
            // Mostrar el mensaje de error completo
            echo "<script>console.log('Error en cerrar_evento: " . $e->getMessage() . "')</script>";
            
            // Devolver el mensaje de error para depuración
            return "Error: " . $e->getMessage();
        }
    }
    public function get_evento_where($where){
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT tme.ev_id, tme.ev_nom, tme.ev_mail, tme.ev_desc, te.est_nom, tme.ev_inicio, tme.ev_final, tme.ev_direc, tmc.cat_nom, ten.ev_niv_nom,tme.ev_est,tme.cat_id,tmc.cat_id, ten.ev_niv_id,GROUP_CONCAT(tu.unid_nom SEPARATOR ' - ') AS unidades
            FROM tm_evento tme
            INNER JOIN tm_asignado temu ON tme.ev_id = temu.ev_id 
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

    public function get_evento_por_categoria($cat_id){
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_evento where cat_id=:cat_id";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(":cat_id", $cat_id);
            $sql->execute();
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                error_log("No logro obtener eventos por categoria");
                return array(); // return an empty array instead of 0
            }
        } catch (Exception $e) {
            error_log("Error catch get_evento: " . $e->getMessage());
            throw $e;
        }
    }


    public function get_eventos_categoria_latitud_longitud($startDate = null, $endDate = null) {
        $sql = 'SELECT ev.ev_latitud as "latitud",
              		ev.ev_longitud as "longitud",
              		ev.ev_id as "id",
              		ev.ev_desc as "detalles",
              		ev.ev_direc as "direccion",
              		ev.ev_img as "img",
              		ev.ev_inicio as "fecha_inicio",
              		IFNULL(ev.ev_final, "En Proceso") as "fecha_cierre",
                  nv.ev_niv_nom as "nivel",
                  cat.cat_nom as "categoria",
                  un.unid_nom as "unidad"
              FROM tm_evento as ev
              JOIN tm_categoria as cat
              ON (ev.cat_id=cat.cat_id)
              JOIN tm_usuario as usu
              ON (usu.usu_id=ev.usu_id)
              JOIN tm_ev_niv as nv
              ON (ev.ev_niv = nv.ev_niv_id)
              JOIN tm_unidad as un 
              ON ( un.unid_id=usu.usu_unidad)
              WHERE 1 = 1';


        $params = [];

        if ($startDate) {
            $sql .= ' AND DATE(ev.ev_inicio) >= :startDate';
            $params[':startDate'] = $startDate;
        }
        if ($endDate) {
            $sql .= ' AND DATE(ev.ev_inicio) <= :endDate';
            $params[':endDate'] = $endDate;
        }

        return $this->ejecutarConsulta($sql, $params);
    }

    public function datos_categorias_eventos($fecha_inicio) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            
            $sql = "SELECT tm_categoria.cat_nom, COUNT(tm_evento.ev_id) as cantidad_eventos 
                    FROM tm_categoria 
                    LEFT JOIN tm_evento ON tm_categoria.cat_id = tm_evento.cat_id AND tm_evento.ev_inicio >= :fecha_inicio
                    GROUP BY tm_categoria.cat_id";
            
            $sql = $conectar->prepare($sql);
            $sql->bindValue(':fecha_inicio', $fecha_inicio);
            $sql->execute();
            
            $resultado = $sql->fetchAll();
            return $resultado;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function chat_con_mensage_oculto($ev_id) {
        $msgprivado ="<span class='alert alert-secondary'> Mensaje Privado Necesitas permisos para verlo</span>";
        try {
            $sql = "SELECT 
                tm_emergencia_detalle.emergencia_id,
                IF (tm_emergencia_detalle.privado = 0, tm_emergencia_detalle.ev_desc, :msg_privado) as 'ev_desc',
                tm_emergencia_detalle.ev_inicio,
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_usuario.usu_tipo,
                tm_usuario.usu_unidad, -- Incluimos la unidad del usuario
                tm_unidad.unid_nom -- Agregamos el nombre de la unidad
            FROM 
                tm_emergencia_detalle
            INNER JOIN tm_usuario on tm_emergencia_detalle.usu_id = tm_usuario.usu_id
            LEFT JOIN tm_unidad ON tm_usuario.usu_unidad = tm_unidad.unid_id -- Unimos con la tabla de unidades
            WHERE
                tm_emergencia_detalle.ev_id = :ev_id";
            $params = [":ev_id" => $ev_id, ":msg_privado" => $msgprivado];
            $resultado = $this->ejecutarConsulta($sql, $params);
            return $resultado;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function chat_con_mensage_visible($ev_id) {
        try {
            $sql = "SELECT 
                tm_emergencia_detalle.emergencia_id,
                tm_emergencia_detalle.ev_desc as 'ev_desc',
                tm_emergencia_detalle.ev_inicio,
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_usuario.usu_tipo,
                tm_usuario.usu_unidad, -- Incluimos la unidad del usuario
                tm_unidad.unid_nom -- Agregamos el nombre de la unidad
            FROM 
                tm_emergencia_detalle
            INNER JOIN tm_usuario on tm_emergencia_detalle.usu_id = tm_usuario.usu_id
            LEFT JOIN tm_unidad ON tm_usuario.usu_unidad = tm_unidad.unid_id -- Unimos con la tabla de unidades
            WHERE
                tm_emergencia_detalle.ev_id = :ev_id";
           $params = [":ev_id" => $ev_id];
            $resultado = $this->ejecutarConsulta($sql, $params);
            return $resultado;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function listar_evento_por_id($ev_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT
                tm_evento.ev_id,
                tm_evento.usu_id,
                tm_evento.cat_id,
                tm_evento.ev_direc,
                tm_evento.ev_desc,
                tm_evento.ev_est,
                tm_evento.ev_inicio,
                tm_evento.ev_final, 
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_categoria.cat_nom
            FROM
                tm_evento
            INNER JOIN tm_categoria ON tm_evento.cat_id = tm_categoria.cat_id
            INNER JOIN tm_usuario ON tm_evento.usu_id = tm_usuario.usu_id
            WHERE
                tm_evento.ev_id = ?";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $ev_id);
            $sql->execute();
            $resultado = $sql->fetchAll();
            return $resultado;
        } catch (PDOException $e) {
            error_log("Error en listar_evento_por_id: " . $e->getMessage(), 0);
            return false;
        }
    }
    public function insert_emergencia_detalle($ev_id, $usu_id, $ev_desc, $secreto=0) {
    try {
        $conectar = parent::conexion();
        parent::set_names();

        // Insertar en la tabla tm_emergencia_detalle
        $sql = "INSERT INTO tm_emergencia_detalle 
                (ev_id, usu_id, ev_desc, ev_inicio, ev_est, privado)
                VALUES (?, ?, ?, now(), 1,?);";

        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $ev_id, PDO::PARAM_INT);
        $sql->bindValue(2, $usu_id, PDO::PARAM_INT);
        $sql->bindValue(3, $ev_desc, PDO::PARAM_STR);
        $sql->bindValue(4, $secreto, PDO::PARAM_INT);
        $sql->execute();

        // Verificar si se ha insertado alguna fila
        if ($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }

    } catch (Exception $e) {
        error_log("Error en insert_emergencia_detalle: " . $e->getMessage(), 0);
        throw $e;
    }
    }

    public function update_evento($ev_id){
        $conectar = parent::conexion();
        parent::set_names();
        $sql="UPDATE tm_evento SET ev_est = 2 WHERE ev_id = ?";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $ev_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
      }

    function get_evento_motivo_cierre($ev_id){
    $sql = "SELECT
               cie.ev_id as 'id_evento',
               usr.usu_nom as 'nombre',
               usr.usu_ape as 'apellido',
               usr.usu_name as 'username',
               mov.motivo as 'motivo',
               cie.detalle as 'detalle',
               cie.adjunto as 'adjunto',
               DATE_FORMAT(ev.ev_final, '%d/%m/%Y - hrs %H:%i' )as 'fecha_cierre'
         FROM tm_ev_cierre as cie
         JOIN tm_usuario as usr
         on (usr.usu_id = cie.usu_id)
         JOIN tm_cierre_motivo as mov
         on (mov.mov_id = cie.motivo)
         JOIN tm_evento as ev
         on (ev.ev_id=cie.ev_id)
         WHERE cie.ev_id=:ev_id;";
    $params = [":ev_id"=>$ev_id];
    return $this->ejecutarConsulta($sql,$params,false);
  }
  public function get_documentos($evento_id){
    $sql = "SELECT ev.ev_img as 'inicio_documento',
    cie.adjunto as 'cierre_documento'
    FROM tm_evento as ev
    left JOIN tm_ev_cierre as cie
    ON (cie.ev_id = ev.ev_id)
    WHERE ev.ev_id = :evento_id";
    $params=[":evento_id"=>$evento_id];
    $query = $this->ejecutarConsulta($sql,$params,false);
    if (is_array($query) && count($query) > 0) {
      $respuesta = [
         "status"=>"success",
         "message"=>"Se obtienen los datos",
         "result"=>$query
      ];
    }else{
      $respuesta = [
        "status"=>"error",
        "message"=>"No se obtienen los datos",
        "result"=>[]
      ];
    }
    return $respuesta;
  }

  public function get_imagenes_detalle(int $evento_id): array{
    $sql = "SELECT ev_desc as 'descripcion' FROM tm_emergencia_detalle WHERE ev_id = :evento_id";
    $params = [":evento_id"=>$evento_id];
    $resultados = $this->ejecutarConsulta($sql, $params);
    $imagenesBase64 = [];
    if (is_array($resultados) && count($resultados) > 0) {
        foreach ($resultados as $resultado) {
            if (isset($resultado['descripcion'])) {
                $imagenBase64 = $this->capturarImagenBase64($resultado['descripcion']);
                if ($imagenBase64 !== null) {
                    $imagenesBase64[] = $imagenBase64;
                }
            }
        }
    }
    return $imagenesBase64;
  }
  private function capturarImagenBase64(string $texto): ?string {
    $patron = '/<img[^>]*src="data:image\/[a-zA-Z]+;base64,([^"]*)"/';
    preg_match($patron, $texto, $coincidencias);
    return isset($coincidencias[1]) ? trim($coincidencias[1]) : null;
  }
}
