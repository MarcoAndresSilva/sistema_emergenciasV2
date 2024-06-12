<?php
class Categoria extends Conectar
{
    //add_categoria (Insert categoria)
    public function add_categoria($cat_nom,$est) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_categoria (cat_nom, est) VALUES (:cat_nom, :est)";

            $consulta = $conectar->prepare($sql);

            $consulta->bindParam(':cat_nom',$cat_nom);
			$consulta->bindParam(':est',$est);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego categoria ". $cat_nom ." ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_categoria") </script>  <?php
            throw $e;
        }

    }

    public function get_categoria()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_categoria";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $resultado = $sql ->fetchAll();

        if(is_array($resultado) and count($resultado) > 0){
            return $resultado;
        }else {
            ?> <script>console.log("No se encontraron Eventos")</script><?php
            return 0;
        }
    }

    public function get_datos_categoria($cat_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_categoria WHERE cat_id= " . $cat_id . " ";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $resultado = $sql ->fetchAll();

        if(is_array($resultado) and count($resultado) > 0){
            return $resultado;
        }else {
            ?> <script>console.log("No se encontraron Eventos")</script><?php
            return 0;
        }
    }

    //update_categoria segun id
	public function update_categoria($cat_id, $cat_nom,$est) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_categoria SET  cat_nom=:cat_nom, est=:est WHERE cat_id = " . $cat_id . " ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':cat_nom',$cat_nom);
			$consulta->bindParam(':est',$est);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar la categoria")</script><?php
                return 0;
            }
        } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_categoria")</script>
            <?php
            throw $e;
        }
	}

	//delete_categoria segun id
	public function delete_categoria($cat_id) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "DELETE FROM tm_categoria WHERE cat_id=" . $cat_id . " " ;
			$consulta = $conectar->prepare($sql);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro borrar la categoria")</script><?php
                return 0;
            }
			
		} catch (Exception $e) {
			?> 
            <script>console.log("Error catch     delete_categoria")</script>
            <?php
            throw $e;
        }
	}

     // Método para obtener el cat_nom de la tabla tm_categoria correspondiente al ev_id
     public function get_cat_nom_by_ev_id($ev_id){
         try {
             $conectar = parent::conexion();
             parent::set_names();
             $sql = "SELECT c.cat_nom 
                     FROM tm_categoria c
                     INNER JOIN tm_evento e ON c.cat_id = e.cat_id
                     WHERE e.ev_id = :ev_id";
             $consulta = $conectar->prepare($sql);
             $consulta->bindParam(':ev_id', $ev_id, PDO::PARAM_INT);
             $consulta->execute();
             $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
 
             if ($resultado) {
                 return json_encode($resultado); // Devolver la respuesta como JSON
             } else {
                 return json_encode(array('error' => 'Categoría no encontrada para el evento con ID: ' . $ev_id));
             }
         } catch (Exception $e) {
             return json_encode(array('error' => $e->getMessage()));
         }
     }
     public function get_categoria_nivel(){
         try {
             $conectar = parent::conexion();
             parent::set_names();
             $sql = "SELECT * FROM tm_categoria as cat INNER JOIN tm_ev_niv niv ON(cat.est=niv.ev_niv_id);";
             $consulta = $conectar->prepare($sql);
             $consulta->execute();
             $resultado = $consulta->fetchAll();

             if ($resultado) {
                return $resultado;
            } else {
                return array('error' => 'Categoría no encontradas');
            }
        } catch (Exception $e) {
            return json_encode(array('error' => $e->getMessage()));
        }
     }
    public function get_categoria_relacion_motivo($mov_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = 'SELECT 
                	cat.cat_nom as "cat_nom",
                	mc.activo as "activo",
                    mc.mov_id as "mov_id"
                FROM tm_categoria as cat
                JOIN `tm_motivo_cate` as mc
                on(mc.cat_id= cat.cat_id)
                WHERE mc.mov_id = :mov_id;';
        $sql = $conectar->prepare($sql);
        $sql->bindParam(':mov_id', $mov_id);
        $sql->execute();
        $resultado = $sql ->fetchAll();

        if(is_array($resultado) and count($resultado) > 0){
            return $resultado;
        }else {  
            return $this->get_categoria();
        }
    }

 }



