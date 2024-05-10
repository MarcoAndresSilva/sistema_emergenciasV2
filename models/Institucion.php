<?php

class Institucion extends Conectar {

//add_institucion (Insert institucion)
    public function add_institucion($institu_nom,$institu_direc,$sector_id,$ciudad_id,$comuna_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_institucion (institu_nom,institu_direc,sector_id,ciudad_id,comuna_id) VALUES (:institu_nom,:institu_direc,:sector_id,:ciudad_id,:comuna_id)";

            $consulta = $conectar->prepare($sql);
			$consulta->bindParam(':institu_nom',$institu_nom);
            $consulta->bindParam(':institu_direc',$institu_direc);
			$consulta->bindParam(':sector_id',$sector_id);
            $consulta->bindParam(':ciudad_id',$ciudad_id);
			$consulta->bindParam(':comuna_id',$comuna_id);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego el institucion ". $institu_nom ." ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_institucion") </script>  <?php
            throw $e;
        }

    }
	
	//get_institucion 
    public function get_institucion() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_institucion ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontraron institucion")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_institucion()") </script>  <?php
            throw $e;
        }
    }
	
	
	//get_datos_institucion
    public function get_datos_institucion($institu_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_institucion WHERE institu_id = " . $institu_id . " ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontro el institucion ". $institu_nom . " ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_datos_institucion") </script>  <?php
            throw $e;
        }
    }
	

	//update_institucion segun id
	public function update_institucion($institu_id,$institu_nom,$institu_direc,$sector_id,$ciudad_id,$comuna_id) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_institucion SET  institu_nom=:institu_nom,institu_direc=:institu_direc,sector_id=:sector_id,ciudad_id=:ciudad_id,comuna_id=:comuna_id WHERE institu_id = " . $institu_id . " ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':institu_nom', $institu_nom);
            $consulta->bindParam(':institu_direc', $institu_direc);
            $consulta->bindParam(':sector_id', $sector_id);
            $consulta->bindParam(':ciudad_id', $ciudad_id);
            $consulta->bindParam(':comuna_id', $comuna_id);
            

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar los datos de la institucion: ". $institu_nom . " ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_institucion")</script>
            <?php
            throw $e;
        }
	}


	//delete_institucion segun id
	public function delete_institucion($institu_id) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "DELETE FROM tm_institucion WHERE institu_id=" . $institu_id . " " ;
			$consulta = $conectar->prepare($sql);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro borrar a la institucion: ". $institu_nom . " ")</script><?php
                return 0;
            }
			
		} catch (Exception $e) {
			?> 
            <script>console.log("Error catch     delete_institucion")</script>
            <?php
            throw $e;
        }
	}
}