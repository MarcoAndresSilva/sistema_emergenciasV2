<?php
require_once("../../config/conexion.php");
require_once("../MainJs/js.php");

// Verificar la sesión del usuario
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
?>

<!DOCTYPE html>
<html>
<?php require_once("../MainHead/head.php"); ?>
<title>Sistema Emergencia</title>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQrYCFSz7Q-a-WONxo4yymu9SAPgmaA6c&libraries=visualization"></script>
  <link rel="stylesheet" href="./stylemapacalor.css">
</head>

<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php"); ?>

    <div class="mobile-menu-left-overlay"></div>

    <?php require_once("../MainNav/nav.php"); ?>

    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Mapa</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="#">Mapa</a></li>
                                <li class="active">Mapa</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header>

<button id="toggleMapView" class="btn btn-warning">
  <i class="fas fa-map-marker-alt"></i> Mapa De Dispersión
</button>
<button id="togglePOIs" class="btn btn-warning btn-inactive">
  <i class="fas fa-eye btn-icon"></i> Mostrar Puntos de Interés
</button>
<div id="date-filters" class="mb-2 row">
  <label for="startDate">Fecha de Inicio:</label>
  <input type="date" class="mb-2 form-control" id="startDate" name="startDate">
  <label for="endDate">Fecha de Cierre:</label>
  <input type="date" class="mb-2 form-control" id="endDate" name="endDate">
  <button id="applyDateFilter" class="btn btn-warning ">Aplicar Filtro</button>
</div>
<div id="controls" class="row" role="group" aria-label="Controles de mapa">
  <!-- Aquí se insertarán los botones de categorías -->
</div>
<!-- Contenedor del mapa -->
  <div id="map"></div>

  <!-- Controles de filtro por categoría -->

        </div><!--.container-fluid-->
    </div><!--.page-content-->
    
  <!--   <script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQrYCFSz7Q-a-WONxo4yymu9SAPgmaA6c&libraries=visualization&callback=initMap"> -->
  <!-- </script> -->
    <script defer type="text/javascript" src="./mapacalor.js"></script>
    <script>
        document.getElementById('show-hide-sidebar-toggle').addEventListener('click', function(e) {
            e.preventDefault();

            var body = document.body;

            if (!body.classList.contains('sidebar-hidden')) {
                body.classList.add('sidebar-hidden');
            } else {
                body.classList.remove('sidebar-hidden');
            }
        });
    </script>
  <script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQrYCFSz7Q-a-WONxo4yymu9SAPgmaA6c&libraries=visualization&callback=initMap">
  </script>
</body>

<?php
} else {
    header("location:".Conectar::ruta()."index.php");
}
?>

</html>

