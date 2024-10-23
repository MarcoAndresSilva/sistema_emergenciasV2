<?php

class EventoUnidad extends Conectar {
    public function add_eventoUnidad($ev_id, $sec_id) {
        try{
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_asignado (ev_id, sec_id) VALUES ( :ev_id, :sec_id)";
            
            $consulta  = $conectar->prepare($sql);
        
            $consulta->bindParam(':ev_id', $ev_id);
            $consulta->bindParam(':sec_id', $sec_id);
            
            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?>
                <script>console.log("No se agregaro filas intermedias tm_asignado")</script>
                <?php
                return 0;
            }
        } catch (Exception $e) {
            ?> 
            <script>console.log("Error catch     add_eventoUnidad")</script>
            <?php
            throw $e;
        }
    }


    public function delete_unidad($ev_id, $sec_id) {
        try{
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "DELETE FROM tm_asignado WHERE ev_id = :ev_id AND sec_id = :sec_id";
            
            $consulta  = $conectar->prepare($sql);
        
            $consulta->bindParam(':ev_id', $ev_id);
            $consulta->bindParam(':sec_id', $sec_id);
            
            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?>
                <script>console.log("No se eliminaron filas intermedias tm_asignado")</script>
                <?php
                return 0;
            }
        } catch (Exception $e) {
            ?> 
            <script>console.log("Error catch     delete_unidad")</script>
            <?php
            throw $e;
        }
    }

    
    public function add_reporte_cambio_unidad($ev_id, $str_antiguo, $str_nuevo, $fec_cambio) {
        try{
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_camb_asig (ev_id, antigua_asig, nueva_asig, fec_cambio) VALUES ( :ev_id, :antigua_asig, :nueva_asig, :fec_cambio)";
            
            $consulta  = $conectar->prepare($sql);
        
            $consulta->bindParam(':ev_id', $ev_id);
            $consulta->bindParam(':antigua_asig', $str_antiguo);
            $consulta->bindParam(':nueva_asig', $str_nuevo);
            $consulta->bindParam(':fec_cambio', $fec_cambio);
            
            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?>
                <script>console.log("No se agregaro filas al reporte de cambio de unidades asignadas")</script>
                <?php
                return 0;
            }
        } catch (Exception $e) {
            ?> 
            <script>console.log("Error catch     add_reporte_cambio_unidad")</script>
            <?php
            throw $e;
        }
    }

    public function get_datos_eventoUnidad($ev_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT sec_id FROM tm_asignado WHERE ev_id = ". $ev_id. "";
            $consulta  = $conectar->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll();
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


    public function get_datos_UnidadesAsignadas_por_evento($sec_id) {
         $sql = "SELECT * FROM `tm_asignado` as asg
        JOIN tm_seccion as sec
        on (sec.sec_id=asg.sec_id)
        JOIN tm_unidad as unid  on(unid.unid_id=sec.sec_unidad)
        where asg.ev_id = :ev_id";
        
        $params = [':ev_id' => $sec_id];
        $resultado = $this->ejecutarConsulta($sql, $params);
    
        if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
        } else {
            return false;
        }
    }
    


    //update_asignacion_evento segun id
	public function update_asignacion_evento($ev_id, $sec_id) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_asignado SET  sec_id=:sec_id WHERE ev_id = :ev_id and sec_id=:sec_id";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':sec_id',$sec_id);
            $consulta->bindParam(':ev_id',$ev_id);

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
}
