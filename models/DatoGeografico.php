<?php

class DatoGeografico extends Conectar {

    //get_pais general (Select pais)
    public function get_pais() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_pais ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontraron Paises")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_pais") </script>  <?php
            throw $e;
        }

    }

    //get_datos_pais segun id
    public function get_datos_pais($pais_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_pais where pais_id = ". $pais_id ." ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se Encontro Pais")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_datos_pais") </script>  <?php
            throw $e;
        }
    }

    //add_pais general (Insert pais)
    public function add_pais($pais_nom) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_pais (pais_nom) VALUES (:pais_nom) ";

            $consulta = $conectar->prepare($sql);

            $consulta->bindParam(':pais_nom',$pais_nom);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego el pais ". $pais_nom ." ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_pais") </script>  <?php
            throw $e;
        }

    }

    //get_region 
    public function get_region() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_region ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontraron Regiones")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_region") </script>  <?php
            throw $e;
        }
    }

    //get_datos_region segun id
    public function get_datos_region($region_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_region where region_id = ". $region_id ." ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontro Region")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_datos_region") </script>  <?php
            throw $e;
        }
    }

    //add_region general (Insert region)
    public function add_region($region_nom, $pais_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_region (region_nom,pais_id) VALUES (:region_nom,:pais_id) ";

            $consulta = $conectar->prepare($sql);

            $consulta->bindParam(':region_nom',$region_nom);
            $consulta->bindParam(':pais_id',$pais_id);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego el region ". $region_nom ." ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_region") </script>  <?php
            throw $e;
        }

    }

    //get_comuna 
    public function get_comuna() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_comuna ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontraron Comunas")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_comuna") </script>  <?php
            throw $e;
        }
    }

    //get_datos_comuna segun id
    public function get_datos_comuna($comuna_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_comuna where comuna_id = " . $comuna_id . "";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontro Comuna")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_datos_comuna") </script>  <?php
            throw $e;
        }
    }

    //add_comuna general (Insert comuna)
    public function add_comuna($comuna_nom,$region_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_comuna (comuna_nom,region_id) VALUES (:comuna_nom,:region_id) ";

            $consulta = $conectar->prepare($sql);

            $consulta->bindParam(':comuna_nom',$ciudad_nom);
            $consulta->bindParam(':region_id',$comuna_id);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego la comuna ". $comuna_nom ." ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_ciudad") </script>  <?php
            throw $e;
        }

    }

    //get_ciudad 
    public function get_ciudad() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_ciudad ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontraron Ciudades")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_ciudad") </script>  <?php
            throw $e;
        }
    }

    //get_datos_ciudad_id segun id
    public function get_datos_ciudad() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_ciudad ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontro ciudad")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_datos_ciudad") </script>  <?php
            throw $e;
        }
    }

    //add_ciudad general (Insert ciudad)
    public function add_ciudad($ciudad_nom,$comuna_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_pais (ciudad_nom,comuna_id) VALUES (:ciudad_nom,:comuna_id) ";

            $consulta = $conectar->prepare($sql);

            $consulta->bindParam(':ciudad_nom',$ciudad_nom);
            $consulta->bindParam(':comuna_id',$comuna_id);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego la ciudad ". $ciudad_nom ." ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_ciudad") </script>  <?php
            throw $e;
        }

    }

    //get_sector 
    public function get_sector() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_sector ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontraron Sectores")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_sector") </script>  <?php
            throw $e;
        }
    }

    //get_datos_sector_id segun id 
    public function get_datos_sector($sector_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_sector where sector_id = " . $sector_id . " ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
              ?> <script>console.log("No se encontro Sector")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    get_datos_sector") </script>  <?php
            throw $e;
        }
    }

    //add_sector general (Insert sector)
    public function add_sector($sector_nom,$ciudad_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_sector (sector_nom,ciudad_id) VALUES (:sector_nom,:ciudad_id) ";

            $consulta = $conectar->prepare($sql);

            $consulta->bindParam(':sector_nom',$sector_nom);
            $consulta->bindParam(':ciudad_id',$ciudad_id);

            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se agrego el sector ". $sector_nom ." ")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script> console.log("Error catch    add_sector") </script>  <?php
            throw $e;
        }

    }
}