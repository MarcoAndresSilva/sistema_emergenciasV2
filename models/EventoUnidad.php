<?php

class EventoUnidad extends Conectar {
    public function add_eventoUnidad($ev_id, $unid_id) {
        try{
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_ev_tm_unid (ev_id, unid_id) VALUES ( :ev_id, :unid_id)";
            
            $consulta  = $conectar->prepare($sql);
        
            $consulta->bindParam(':ev_id', $ev_id);
            $consulta->bindParam(':unid_id', $unid_id);
            
            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?>
                <script>console.log("No se agregaro filas intermedias tm_ev_tm_unid")</script>
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


    public function delete_unidad($ev_id, $unid_id) {
        try{
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "DELETE FROM tm_ev_tm_unid WHERE ev_id = :ev_id AND unid_id = :unid_id";
            
            $consulta  = $conectar->prepare($sql);
        
            $consulta->bindParam(':ev_id', $ev_id);
            $consulta->bindParam(':unid_id', $unid_id);
            
            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?>
                <script>console.log("No se eliminaron filas intermedias tm_ev_tm_unid")</script>
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
            $sql = "SELECT unid_id FROM tm_ev_tm_unid WHERE ev_id = ". $ev_id. "";
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

    //update_asignacion_evento segun id
	public function update_asignacion_evento($ev_id, $unid_id) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_ev_tm_unid SET  unid_id=:unid_id WHERE ev_id = :ev_id and unid_id=:unid_id";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':unid_id',$unid_id);
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