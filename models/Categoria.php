<?php
class Categoria extends Conectar
{
       // add_categoria (Insert categoria)
    public function add_categoria($cat_nom, $nivel)
    {
        $sql = "INSERT INTO tm_categoria (cat_nom, nivel) VALUES (:cat_nom, :nivel)";
        $params = [':cat_nom' => $cat_nom, ':nivel' => $nivel];
        return $this->ejecutarAccion($sql, $params);
    }

    // get_categoria
    public function get_categoria()
    {
        $sql = "SELECT * FROM tm_categoria";
        return $this->ejecutarConsulta($sql);
    }

    // get_datos_categoria
    public function get_datos_categoria($cat_id)
    {
        $sql = "SELECT * FROM tm_categoria WHERE cat_id = :cat_id";
        $params = [':cat_id' => $cat_id];
        return $this->ejecutarConsulta($sql, $params);
    }

    // update_categoria segun id
    public function update_categoria($cat_id, $cat_nom, $nivel)
    {
        $sql = "UPDATE tm_categoria SET cat_nom = :cat_nom, nivel = :nivel WHERE cat_id = :cat_id";
        $params = [':cat_id' => $cat_id, ':cat_nom' => $cat_nom, ':nivel' => $nivel];
        return $this->ejecutarAccion($sql, $params);
    }

    // delete_categoria segun id
    public function delete_categoria($cat_id)
    {
        $sql = "DELETE FROM tm_categoria WHERE cat_id = :cat_id";
        $params = [':cat_id' => $cat_id];
        return $this->ejecutarAccion($sql, $params);
    }

    // get_cat_nom_by_ev_id
    public function get_cat_nom_by_ev_id($ev_id)
    {
        $sql = "SELECT c.cat_nom FROM tm_categoria c INNER JOIN tm_evento e ON c.cat_id = e.cat_id WHERE e.ev_id = :ev_id";
        $params = [':ev_id' => $ev_id];
        $resultado = $this->ejecutarConsulta($sql, $params, false);

        if ($resultado) {
            return json_encode($resultado);
        } else {
            return json_encode(['error' => 'Categoría no encontrada para el evento con ID: ' . $ev_id]);
        }
    }

    // get_categoria_nivel
    public function get_categoria_nivel()
    {
        $sql = "SELECT * FROM tm_categoria as cat INNER JOIN tm_ev_niv niv ON(cat.nivel = niv.ev_niv_id)";
        return $this->ejecutarConsulta($sql);
    }

    // get_categoria_relacion_motivo
    public function get_categoria_relacion_motivo($mov_id)
    {
        $sql = 'SELECT cat.cat_nom as "cat_nom", mc.activo as "activo", mc.mov_id as "mov_id"
                FROM tm_categoria as cat
                JOIN tm_motivo_cate as mc ON mc.cat_id = cat.cat_id
                WHERE mc.mov_id = :mov_id';
        $params = [':mov_id' => $mov_id];
        $resultado = $this->ejecutarConsulta($sql, $params);

        if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
        } else {
            return $this->get_categoria();
        }
    }
}
?>
