<?php
require_once("../../config/conexion.php");
require_once("../MainJs/js.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
<link rel="stylesheet" href="./seguridadUnidad.css">
<title>Sistema Emergencia</title>
<link rel="stylesheet" href="../../public/css/datatable/jquery.1.11.5.dataTables.min.css">
<link rel="stylesheet" href="../../public/css/datatable/responsive.2.2.9.dataTables.min.css">
<script src="../../public/js/jqueryV3.5.1.min.js"></script>
<script src="../../public/js/datatable/jquery.1.11.5.jquery.dataTables.min.js"></script>
<script src="../../public/js/datatable/dataTables.2.2.9.responsive.min.js"></script>
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
<script defer type="text/javascript" src="./seguridadUnidad.js"></script>
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
							<h3>Instituciones de Emergencias</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="#">Registro</a></li>
								<li class="active">Instituciones</li>
							</ol>
						</div>
					</div>
				</div>
			</header>
          <div class="container"> 
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Unidad</th>
                        <th>Mayúsculas</th>
                        <th>Minúsculas</th>
                        <th>Especiales</th>
                        <th>Números</th>
                        <th>Largo</th>
                        <th>Dias para Cambiar</th>
                        <th>Fecha de Modificación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
          </div>
        </div><!--.container-fluid-->
    </div><!--.page-content-->

	<?php require_once("../MainFooter/footer.php"); ?>
	
</body>
<?php
} else {
	header("location:".Conectar::ruta()."index.php");
}
?>
</html>
