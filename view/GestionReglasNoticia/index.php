<?php
require_once("../../models/Permisos.php");
require_once("../../config/conexion.php");
Permisos::redirigirSiNoAutorizado();
?>

<!DOCTYPE html>
<html>
<?php require_once("../MainHead/head.php") ?>
<title>Sistema Emergencia</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="./GestionReglasNoticia.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script defer src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script defer src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script defer src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script defer type="text/javascript" src="./GestionReglasNoticia.js"></script>
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
                      <h3>Gestion Reglas Noticia</h3>
                      <ol class="breadcrumb breadcrumb-simple">
                        <li><a href="../Home/">Inicio</a></li>
                        <li class="active">Administración</li>
                        <li class="active">Gestion reglas de noticias</li>
                      </ol>
                    </div>
                  </div>
                </div>
              </header>
            <div class="box-typical box-typical-padding table-responsive-sm" id="tabla-password">
                    <div class="input-group rounded">
                    </div> <!-- .input-group -->
<table id="reglas-table" class="table table-bordered responsive  table-vcenter js-dataTable-js display nowrap"> 
</table>
            </div> <!-- .box-typical -->


        </div><!--.container-fluid-->
    </div><!--.page-content-->

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
          var enlace = document.querySelector('.Parametria');
          if (enlace) {
            enlace.classList.add('selected');
          }
        });
    </script>
        <?php require_once("../MainFooter/footer.php"); ?>

</body>
<?php require_once("../MainJs/js.php");?>

</html>

