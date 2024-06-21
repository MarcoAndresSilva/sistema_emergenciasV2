# cambio por sqlinjection

codigo del documento models/Unidad.php tenia script vulnerable a sqlinjection
en la linea 31 y en la linea 54

**codigo original**
```php

<?php
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
```

**codigo modificado**
```php
<?php
    public function get_datos_unidad($unid_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_unidad where unid_id = :unid_id";
            $sql = $conectar->prepare($sql);
            $sql->bindParam(':unid_id', $unid_id);
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
```
se cambia al uso de parametro bindParam para agregar los datos y evitar sqlinjection
