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
  <style>
    /* Asegúrate de definir el estilo del contenedor del mapa */
    #map {
      height: 500px; /* Define la altura del mapa */
      width: 100%;  /* Define el ancho del mapa */
    }
  </style>
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


   <div id="controls">
    <button class="btn" onclick="filterCategory('incendios', this)">Incendios</button>
    <button class="btn" onclick="filterCategory('caidaArbol', this)">Caida de Árbol</button>
    <button class="btn" onclick="filterCategory('corteLuz', this)">Corte de Luz</button>
  </div> <!-- Contenedor del mapa -->
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

