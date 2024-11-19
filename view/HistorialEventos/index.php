<?php
require_once("../../config/conexion.php");

require_once("../../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();

?>

<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php") ?>

    <!-- Agrega las librerías de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    <link rel="stylesheet" href="./historialeventos.css">
    <script src="../../public/js/sweetaler2v11-11-0.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- Agrega el script de DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="./ver_documentos.js"></script>
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
                            <h3>Historial de las Emergencias</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="#">Emergencias</a></li>
                                <li class="active">Detalle de las Emergencias</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header>
            
            <h5 class="m-t-lg with-border">Informaci&oacute;n Actual</h5>
            <div class="box-typical box-typical-padding table table-responsive-sm">

                <!-- tabla de asuntos inmediatos (Alto) -->
                <table  id="tabla-historial" class="table tabla-media tabla-basica table-bordered table-striped table-vcenter js-dataTable-js">
                    <thead>
                        <tr>
                            <th style="width:5%">ID</th>
                            <th style="width:10%">Categor&iacute;a</th>
                            <th style="width:10%">Direcci&oacute;n</th>
                            <th style="width:25%">Asignaci&oacute;n</th>
                            <th style="width:10%">Nivel Peligro</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:15%">Fecha Apertura</th>
                            <th style="width:5%">Ver Documentación</th>
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

require_once("../MainJs/js.php");
?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdCMoRAl_-ARUflpa4Jn_qUoOpdXlxQEg&libraries=places&v=3.55"></script>
</body>

<script type="text/javascript" src="./historialeventos.js"></script>
</html>
