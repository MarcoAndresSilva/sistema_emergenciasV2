<?php
class Unidad extends Conectar {

    public function get_unidad() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_unidad ";
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
            ?> <script>console.log("Error catch     get_unidad")</script> <?php
            throw $e;
        }

    }

    //get_datos_unidad segun su id
    public function get_datos_unidad($unid_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_unidad where unid_id = ". $unid_id ." ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Unidades")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     get_datos_unidad")</script> <?php
            throw $e;
        }

    }

    //get_unidad_dispo segun disponibilidad
    public function get_unidad_est($unid_est) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_unidad where unid_est = '". $unid_est ."' ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Unidades")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     get_unidad_est")</script> <?php
            throw $e;
        }

    }

    public function add_unidad($unid_nom,$unid_est,$responsable_rut,$reemplazante_rut) {
        try{
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_unidad (unid_nom,unid_est,responsable_rut,reemplazante_rut) VALUES ( :unid_nom, :unid_est,:responsable_rut,:reemplazante_rut)";
            
            $consulta  = $conectar->prepare($sql);
        
            $consulta->bindParam(':unid_nom', $unid_nom);
            $consulta->bindParam(':unid_est', $unid_est);
            $consulta->bindParam(':responsable_rut', $responsable_rut);
            $consulta->bindParam(':reemplazante_rut', $reemplazante_rut);
            
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se encontraron unidades")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     add_unidad")</script> <?php
            throw $e;
        }
    }

    //update_unidad segun id
	public function update_unidad($unid_id,$unid_nom,$unid_est,$responsable_rut,$reemplazante_rut) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_unidad SET  unid_nom=:unid_nom ,unid_est=:unid_est, responsable_rut=:responsable_rut, reemplazante_rut=:reemplazante_rut WHERE unid_id = " . $unid_id . " ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':unid_nom',$unid_nom);
			$consulta->bindParam(':unid_est',$unid_est);
            $consulta->bindParam(':responsable_rut',$responsable_rut);
			$consulta->bindParam(':reemplazante_rut',$reemplazante_rut);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar la unidad")</script><?php
                return 0;
            }
        } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_unidad")</script>
            <?php
            throw $e;
        }
	}

    //delete_unidad segun id
	public function delete_unidad($unid_id) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "DELETE FROM tm_unidad WHERE unid_id=" . $unid_id . " " ;
			$consulta = $conectar->prepare($sql);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro borrar la unidad")</script><?php
                return 0;
            }
			
		} catch (Exception $e) {
			?> 
            <script>console.log("Error catch     delete_unidad")</script>
            <?php
            throw $e;
        }
	}
}