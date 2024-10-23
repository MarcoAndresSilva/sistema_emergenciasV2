<?php

require_once '../config/conexion.php';
require_once '../models/Seccion.php';
require_once '../models/Permisos.php';

$permisos = new Permisos();

$pagina = "Crear Nuevo Evento";
$listausuarios = $permisos->usuarios_permitidos($pagina);

header('Content-Type: application/json');
echo json_encode($listausuarios);
