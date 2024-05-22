<?php
    session_start();

    class Conectar {
        protected $dbh;
        protected function Conexion () {
            try {
                // Local
                $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=emergencia_db","root","");
                // Host
                // $conectar = $this->dbh = new PDO("mysql:host=localhost;port=2083;dbname=admem_db_emergencia","admem_marco","Calamar.!1");
                return $conectar;
                
            } catch (Exception $e) {
                print "隆Error DB !: ".$e->getMessage()."<br/>";
                die();
            }    
        }
        //se agrega funcion para los caracteres especiales
        public function set_names () {
            return $this->dbh->query("SET NAMES 'utf8'");
        }

        public function ruta () {
            // Local
            return "http://localhost/sistema_emergenciasV2/";
            // host
            // return "https://emergencias.melipilla.cl/";
        }
    }


?>