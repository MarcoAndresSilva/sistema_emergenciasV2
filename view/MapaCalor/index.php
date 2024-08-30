<?php
require_once("../../models/Permisos.php");
require_once("../../config/conexion.php");

Permisos::redirigirSiNoAutorizado();
?>

<!DOCTYPE html>
<html>
<?php require_once("../MainHead/head.php"); ?>
<title>Sistema Emergencia</title>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="./stylemapacalor.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="module" src="./mapacalor.js"></script>
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
<button id="dateFilterButton" class="btn btn-warning"><i class="fa fa-filter btn-icon" aria-hidden="true"></i> Filtros</button>
<div id="controls" class="row" role="group" aria-label="Controles de mapa">
  <!-- Aquí se insertarán los botones de categorías -->
</div>
<!-- Contenedor del mapa -->
<div class="input-group mb-3">
  <input class="form-control" type="text" id="searchInput" placeholder="Buscar calle" />
  <button class="btn btn-outline-secondary" id="searchButton">Buscar</button>
</div>
  <div id="map"></div>
<div id="summaryTableContainer" class="table-responsive"></div>
  <!-- Controles de filtro por categoría -->

        </div><!--.container-fluid-->
    </div><!--.page-content-->
    
  <!--   <script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQrYCFSz7Q-a-WONxo4yymu9SAPgmaA6c&libraries=visualization&callback=initMap"> -->
  <!-- </script> -->
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
        //selecionar en el sidebar que esta en mapacalor
        document.addEventListener('DOMContentLoaded', function() {
          var enlace = document.querySelector('.MapaCalor');
          if (enlace) {
            enlace.classList.add('selected'); 
          }
        });
    </script>

    <?php require_once("../MainJs/js.php"); ?>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <?php require_once("../MainFooter/footer.php"); ?>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdCMoRAl_-ARUflpa4Jn_qUoOpdXlxQEg&callback=initMap&libraries=visualization&v=weekly&loading=async"
      defer
    ></script>
</body>
</html>
