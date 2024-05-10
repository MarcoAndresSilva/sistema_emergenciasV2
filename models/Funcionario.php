<?php

class Funcionario extends Conectar {

//add_funcionario (Insert funcionario)
    public function add_funcionario($funci_rut,$funci_pnom,$funci_snom,$funci_appater,$funci_apmater,$funci_email,$funci_celular,$sector_id,$funci_direc,$ciudad_id,$comuna_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_funcionario (funci_rut,funci_pnom,funci_snom,funci_appater,funci_apmater,funci_email,funci_celular,sector_id,funci_direc,ciudad_id,comuna_id) VALUES (:funci_rut,:funci_pnom,:funci_snom,:funci_appater,:funci_apmater,:funci_email,:funci_celular,:sector_id,:funci_direc,:ciudad_id,:comuna_id)";

            $consulta = $conectar->prepare($sql);
            $consulta->bindParam(':funci_rut',$funci_rut);
			$consulta->bindParam(':funci_pnom',$funci_pnom);
            $consulta->bindParam(':funci_snom',$funci_snom);
			$consulta->bindParam(':funci_appater',$funci_appater);
            $consulta->bindParam(':funci_apmater',$funci_apmater);
			$consulta->bindParam(':funci_email',$funci_email);
            $consulta->bindParam(':funci_celular',$funci_celular);
			$consulta->bindParam(':sector_id',$sector_id);
            $consulta->bindParam(':funci_direc',$funci_direc);
            $consulta->bindParam(':ciudad_id',$ciudad_id);
			$consulta->bindParam(':comuna_id',$comuna_id);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego el funcionario ". $funci_pnom ." ". $funci_appater . " ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_funcionario") </script>  <?php
            throw $e;
        }

    }
	
	//get_funcionario 
    public function get_funcionario() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_funcionario ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontraron Funcionario")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_funcionario()") </script>  <?php
            throw $e;
        }
    }
	
	
	//get_datos_funcionario
    public function get_datos_funcionario($funci_rut) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_funcionario WHERE funci_rut = " . $funci_rut . " ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontro el funcionario ". $funci_pnom ." ". $funci_appater . " ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_datos_funcionario") </script>  <?php
            throw $e;
        }
    }
	

	//update_funcionario segun id
	public function update_funcionario($funci_rut,$funci_pnom,$funci_snom,$funci_appater,$funci_apmater,$funci_email,$funci_celular,$sector_id,$funci_direc,$ciudad_id,$comuna_id) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_funcionario SET  funci_pnom = :funci_pnom,funci_snom = :funci_snom,funci_appater = :funci_appater,funci_apmater = :funci_apmater,funci_email = :funci_email,funci_celular = :funci_celular,sector_id = :sector_id,funci_direc = :funci_direc,ciudad_id = :ciudad_id,comuna_id = :comuna_id WHERE funci_rut = " . $funci_rut . " ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':funci_rut', $funci_rut);
            $consulta->bindParam(':funci_pnom', $funci_pnom);
            $consulta->bindParam(':funci_snom', $funci_snom);
            $consulta->bindParam(':funci_appater', $funci_appater);
            $consulta->bindParam(':funci_apmater', $funci_apmater);
            $consulta->bindParam(':funci_email', $funci_email);
            $consulta->bindParam(':funci_celular', $funci_celular);
            $consulta->bindParam(':sector_id', $sector_id);
            $consulta->bindParam(':funci_direc', $funci_direc);
            $consulta->bindParam(':ciudad_id', $ciudad_id);
            $consulta->bindParam(':comuna_id', $comuna_id);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar los datos del funcionario: ". $funci_pnom ." ". $funci_appater . " ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_funcionario")</script>
            <?php
            throw $e;
        }
	}


	//delete_funcionario segun id
	public function delete_funcionario($funci_rut) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "DELETE FROM tm_funcionario WHERE funci_rut=" . $funci_rut . " " ;
			$consulta = $conectar->prepare($sql);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro borrar al funcionario: ". $funci_pnom ." ". $funci_appater . " ")</script><?php
                return 0;
            }
			
		} catch (Exception $e) {
			?> 
            <script>console.log("Error catch     delete_funcionario")</script>
            <?php
            throw $e;
        }
	}
}