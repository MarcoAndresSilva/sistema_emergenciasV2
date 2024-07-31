<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
?>

<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php") ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    <link rel="stylesheet" href="./estilopersonaleventos.css">
    <script src="../../public/js/sweetaler2v11-11-0.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <title>Sistema Emergencia</title>
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
                            <h3>Eventos</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="#">Eventos</a></li>
                                <li class="active">En proceso</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header>
            
            <h5 class="m-t-lg with-border">Informaci&oacute;n Actual</h5>
            <div class="box-typical box-typical-padding table table-responsive-sm">

                <!-- tabla de asuntos inmediatos (Alto) -->
                <table class="table tabla-media tabla-basica table-bordered table-striped table-vcenter js-dataTable-js">
                    <thead>
                        <tr>
                            <th style="width:5%">ID</th>
                            <th style="width:9.5%">Categor&iacute;a</th>
                            <th style="width:9.5%">Direcci&oacute;n</th>
                            <th style="width:25%">Asignaci&oacute;n</th>
                            <th style="width:10%">Nivel Peligro</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:15%">Hora Apertura</th>
                            <th style="width:5%">Derivar</th>
                            <th style="width:5%">Cerrar</th>
                        </tr>
                    </thead>
                    <tbody id="datos-criticos">
                        <!-- Datos de consulta -->
                    </tbody>
                    <tbody id="datos-medios">
                        <!-- Datos de consulta -->
                    </tbody>
                    <tbody id="datos-bajos">
                        <!-- Datos de consulta -->
                    </tbody>
                    <tbody id="datos-generales">
                        <!-- Datos de consulta -->
                    </tbody>
                </table>
            </div>

            
<div id="modal-mapa" class="modal-overlay">
    <div class="vista-mapa" id="map">
    </div>
    <button id='btn' type='button' class='btn btn-inline btn-primary btn-sm ladda-button btnCrearRuta' > Ir </button>
    <span class="glyphicon glyphicon-remove CerrarModalMap"></span>
</div>



        </div>
    </div><!--.page-content-->

    <?php require_once("../MainFooter/footer.php"); ?>

    <?php
require_once('modalDerivar.php');
require_once('modalCerrar.php');
require_once("../MainJs/js.php");
?>
</body>

<script type="text/javascript" src="./evento.js"></script>
</html>

<?php
} else {
    header("location:" . Conectar::ruta() . "index.php");
}
?>