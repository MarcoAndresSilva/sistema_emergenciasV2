<?php
    session_start();

    class Conectar {
        protected $dbh;
        protected function Conexion () {
            try {

                // Host
                // $conectar = $this->dbh = new PDO("mysql:host=localhost;port=2083;dbname=admem_db_emergencia","admem_marco","Calamar.!1");
                // return $conectar;

                // docker     
                $this->dbh = new PDO("mysql:host=mysql;dbname=emergencia_db", "root", "tu");
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->dbh;
              
                // xampp
                // $conectar = $this->dbh = new PDO("mysql:host=localhost;dbname=admem_db_emergencia","root","");
                // return $conectar;

                
            } catch (Exception $e) {
                print "隆Error DB !: ".$e->getMessage()."<br/>";
                die();
            }    
        }
        //se agrega funcion para los caracteres especiales
        public function set_names () {
            return $this->dbh->query("SET NAMES 'utf8'");
        }

        public static function ruta () {
            // Local docker 
            return "http://localhost/";
            // local xamp
            // return "http://localhost/sistema_emergenciasV2/";
            // host
            // return "https://emergencias.melipilla.cl/";
        }
    // Método para ejecutar consultas y devolver resultados
    protected function ejecutarConsulta($sql, $params = [], $fetchAll = true)
    {
        $conexion = $this->conexion();
        $this->set_names();
        $consulta = $conexion->prepare($sql);

        foreach ($params as $key => &$val) {
            $consulta->bindParam($key, $val);
        }

        $consulta->execute();

        if ($fetchAll) {
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $consulta->fetch(PDO::FETCH_ASSOC);
        }
    }
    // Método para ejecutar acciones (INSERT, UPDATE, DELETE)
    protected function ejecutarAccion($sql, $params = [])
    {
        $conexion = $this->conexion();
        $this->set_names();
        $consulta = $conexion->prepare($sql);

        foreach ($params as $key => &$val) {
            $consulta->bindParam($key, $val);
        }

        $consulta->execute();

        return $consulta->rowCount() > 0;
    }

}
?>
