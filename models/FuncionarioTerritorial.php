<?php

class FuncionarioTerritorial extends Conectar {

//add_FuncionarioTerritorial (Insert FuncionarioTerritorial)
    public function add_FuncionarioTerritorial($f_terri_rut,$f_terri_pnom,$f_terri_snom,$f_terri_appater,$f_terri_apmater,$f_terri_email,$f_terri_celular,$sector_id,$f_terri_direc,$ciudad_id,$comuna_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_f_territorial (f_terri_rut,f_terri_pnom,f_terri_snom,f_terri_appater,f_terri_apmater,f_terri_email,f_terri_celular,sector_id,f_terri_direc,ciudad_id,comuna_id) VALUES (:f_terri_rut,:f_terri_pnom,:f_terri_snom,:f_terri_appater,:f_terri_apmater,:f_terri_email,:f_terri_celular,:sector_id,:f_terri_direc,:ciudad_id,:comuna_id)";

            $consulta = $conectar->prepare($sql);
            $consulta->bindParam(':f_terri_rut',$f_terri_rut);
			$consulta->bindParam(':f_terri_pnom',$f_terri_pnom);
            $consulta->bindParam(':f_terri_snom',$f_terri_snom);
			$consulta->bindParam(':f_terri_appater',$f_terri_appater);
            $consulta->bindParam(':f_terri_apmater',$f_terri_apmater);
			$consulta->bindParam(':f_terri_email',$f_terri_email);
            $consulta->bindParam(':f_terri_celular',$f_terri_celular);
			$consulta->bindParam(':sector_id',$sector_id);
            $consulta->bindParam(':f_terri_direc',$f_terri_direc);
            $consulta->bindParam(':ciudad_id',$ciudad_id);
			$consulta->bindParam(':comuna_id',$comuna_id);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego el FuncionarioTerritorial ". $f_terri_pnom ." ". $f_terri_appater . " ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_FuncionarioTerritorial") </script>  <?php
            throw $e;
        }

    }
	
	//get_FuncionarioTerritorial 
    public function get_FuncionarioTerritorial() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_f_territorial ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontraron FuncionarioTerritorial")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_FuncionarioTerritorial()") </script>  <?php
            throw $e;
        }
    }
	
	
	//get_datos_FuncionarioTerritorial
    public function get_datos_FuncionarioTerritorial($f_terri_rut) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_f_territorial WHERE f_terri_rut = " . $f_terri_rut . " ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontro el FuncionarioTerritorial ". $f_terri_pnom ." ". $f_terri_appater . " ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_datos_FuncionarioTerritorial") </script>  <?php
            throw $e;
        }
    }
	

	//update_variable segun id
	public function update_FuncionarioTerritorial($f_terri_rut,$f_terri_pnom,$f_terri_snom,$f_terri_appater,$f_terri_apmater,$f_terri_email,$f_terri_celular,$sector_id,$f_terri_direc,$ciudad_id,$comuna_id) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_f_territorial SET  f_terri_pnom = :f_terri_pnom,f_terri_snom = :f_terri_snom,f_terri_appater = :f_terri_appater,f_terri_apmater = :f_terri_apmater,f_terri_email = :f_terri_email,f_terri_celular = :f_terri_celular,sector_id = :sector_id,f_terri_direc = :f_terri_direc,ciudad_id = :ciudad_id,comuna_id = :comuna_id WHERE f_terri_rut = " . $f_terri_rut . " ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':f_terri_rut', $f_terri_rut);
            $consulta->bindParam(':f_terri_pnom', $f_terri_pnom);
            $consulta->bindParam(':f_terri_snom', $f_terri_snom);
            $consulta->bindParam(':f_terri_appater', $f_terri_appater);
            $consulta->bindParam(':f_terri_apmater', $f_terri_apmater);
            $consulta->bindParam(':f_terri_email', $f_terri_email);
            $consulta->bindParam(':f_terri_celular', $f_terri_celular);
            $consulta->bindParam(':sector_id', $sector_id);
            $consulta->bindParam(':f_terri_direc', $f_terri_direc);
            $consulta->bindParam(':ciudad_id', $ciudad_id);
            $consulta->bindParam(':comuna_id', $comuna_id);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar los datos del FuncionarioTerritorial: ". $f_terri_pnom ." ". $f_terri_appater . " ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_FuncionarioTerritorial")</script>
            <?php
            throw $e;
        }
	}


	//delete_FuncionarioTerritorial segun id
	public function delete_FuncionarioTerritorial($f_terri_rut) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "DELETE FROM tm_f_territorial WHERE f_terri_rut=" . $f_terri_rut . " " ;
			$consulta = $conectar->prepare($sql);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro borrar al FuncionarioTerritorial: ". $f_terri_pnom ." ". $f_terri_appater . " ")</script><?php
                return 0;
            }
			
		} catch (Exception $e) {
			?> 
            <script>console.log("Error catch     delete_FuncionarioTerritorial")</script>
            <?php
            throw $e;
        }
	}
}