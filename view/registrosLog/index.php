<?php
require_once("../../config/conexion.php");
require_once("../../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();
?>

<!DOCTYPE html>
<html>
<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="./StyleRegistroLog.css">
<title>Sistema Emergencia</title>
<script defer type="text/javascript" src="./registroLog.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css" />
<script defer src="https://cdn.datatables.net/1.13.6/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.js"></script>
<script defer src="https://cdn.datatables.net/responsive/2.0.3/js/dataTables.responsive.min.js"></script>
<script defer src="https://cdn.datatables.net/responsive/2.0.3/js/responsive.dataTables.min.js"></script>
</head>

<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php"); ?>

    <div class="mobile-menu-left-overlay"></div>

    <?php require_once("../MainNav/nav.php"); ?>

    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding table-responsive-sm" id="container-tabla-log">
<!-- Crear los campos de entrada de fecha y el select en tu HTML -->
<div class="form-group col-xs-12 col-sm-6 col-md-4">
    <label for="startDate">Fecha de inicio:</label>
    <input type="date" id="startDate" class="form-control">
</div>
<div class="form-group col-xs-12 col-sm-6 col-md-4">
    <label for="endDate">Fecha de fin:</label>
    <input type="date" id="endDate" class="form-control">
</div>
<div class="form-group col-xs-12 col-sm-6 col-md-4">
    <label for="mySelect">Operación:</label>
    <select id="mySelect" class="form-select">
        <option value="">todos</option>
    </select>
</div>
            <table class="table" id="tabla-log">
                    
            </table> 
            </div> <!-- #container-tabla-log -->
        </div><!--.container-fluid-->
    </div><!--.page-content-->
        
        <?php require_once("../MainFooter/footer.php"); ?>

</body>
<?php require_once("../MainJs/js.php"); ?>
 

</html>
