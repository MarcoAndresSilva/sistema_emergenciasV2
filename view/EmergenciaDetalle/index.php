<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
?>

<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php") ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    <link rel="stylesheet" href="./emergenciadetalle.css">
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
                            <h3>Detalle Eventos Emergencias</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="../HistorialEventos">Historial Eventos</a></li>
                                <li class="active">Detalle de las Emergencias</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header>
            

            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <h3 class="m-t-lg" id="lblNomIdTicket"></h3>
                        <div id="lblEstado"></div>                        
                        <span class="label label-pill label-primary" id="lblNomUsuario"></span>
                        <span class="label label-pill label-warning" id="lblFechaCrea"></span>
                    </div>
                </div>
            </div>
           
            <div class="row">
                <div class="col-lg-6 mt-4">
                    <fieldset class="form-group">
                        <label class="form-label semibold" for="exampleInput">Categoria</label>
                        <input type="text" class="form-control" id="cat_nom" name="cat_nom" readonly>
                    </fieldset>
                </div>

                <div class="col-lg-6 mt-4">
                    <fieldset class="form-group">
                    <label class="form-label semibold" for="unid_nom">Unidad Asignada</label>
                        <input type="text" class="form-control" id="unid_nom" name="unid_nom" readonly>
                    </fieldset>
                </div>

                <div class="col-lg-12">
                    <fieldset class="form-group">
                        <label class="form-label semibold" for="tic_descripUsu">Descripción del evento</label>
                            <div class="summernote-theme-1">
                                <textarea class="tic_descripUsu" name="tic_descripUsu" id="tic_descripUsu"></textarea>
                            </div>
                    </fieldset>
                </div>
			</div>

            <section class="activity-line" id="lblDetalle"></section>

            <div class="box-typical box-typical-padding" id="pnlDetalle">
                <p class="fw-bold">Actualizar estado de la Emergencia</p>

                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="form-group">
                            <label class="form-label semibold" for="ev_desc">Descripción</label>
                            <div class="summernote-theme-1">
                                <textarea class="ev_desc" id="ev_desc"></textarea>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-12">
                        <button type="button" id="btnEnviar" class="btn btn-rounded btn-inline btn-primary">Enviar</button>
                    </div>
                   
                </div>
            </div>

		</div>
    </div>

</div>
<!--.page-content-->

    <?php require_once("../MainFooter/footer.php"); ?>

    <?php

require_once("../MainJs/js.php");
?>
</body>

<script type="text/javascript" src="./emergenciadetalle.js"></script>
</html>

<?php
} else {
    header("location:" . Conectar::ruta() . "index.php");
}
?>