<?php
require_once("../config/conexion.php");
require_once("../models/Evento.php");
require_once("../models/Categoria.php");
require_once("../models/Unidad.php");
require_once("../models/Estado.php");
require_once("../models/EventoUnidad.php");
require_once("../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();


$evento = new Evento();
$categoria = new Categoria();
$unidad = new Unidad();
$estado = new Estado();
$eventoUnidad = new EventoUnidad();

if (isset($_GET["op"])) {
    switch ($_GET["op"]) {
        
    
    }
}
