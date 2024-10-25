<?php

require_once "../../models/Permisos.php";
require_once "../../config/conexion.php";
Permisos::redirigirSiNoAutorizado();
?>

<!DOCTYPE html>
<html>
<?php require_once "../MainHead/head.php" ?>
<title>Sistema Emergencia</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="./StyleSeguridadPassword.css">
<script defer src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script defer src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
<script defer src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script defer src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script defer type="text/javascript" src="./seccionGestion.js"></script>
</head>

<body class="with-side-menu">
<?php require_once "../MainHeader/header.php"; ?>
<div class="mobile-menu-left-overlay"></div>
<?php require_once "../MainNav/nav.php"; ?>
<div class="page-content">
      <button class="btn btn-success" onclick="openModalAgregar()">+ agregar secciones</button>
    <div class="container-fluid">
        <div id="button-container"></div
        <div class="input-group rounded"></div>
        <div id="table-container"></div>
    </div><!-- .container-fluid --> 
</div><!-- .page-content -->
<?php require_once "../MainFooter/footer.php"; ?>
<?php require_once "../MainJs/js.php";?>
</body>
</html>
