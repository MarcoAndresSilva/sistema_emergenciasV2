<?php
require_once("../../config/conexion.php");
require_once("../MainJs/js.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
	
	?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="estiloinstitucionesemergencias.css">
<title>Sistema Emergencia</title>
<script defer type="text/javascript" src="./institucionesemergencias.js"></script>
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
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

			<h5 class="m-t-lg with-border">Informaci&oacute;n de Instituciones de Emergencias</h5>

					<table id="miTabla" class="table table-bordered table-responsive table-striped table-vcenter js-dataTable-js">
						<thead>
							<tr>
							<th>ID Categoría</th>
							<th>Nombre Categoría</th>
							<th>Estado</th>
							<th>Nivel de Evaluación</th>
							</tr>
						</thead>
						<tbody>
						<!-- Las filas se añadirán dinámicamente aquí -->
						</tbody>
					</table>

        </div><!--.container-fluid-->
    </div><!--.page-content-->

	<?php require_once("../MainFooter/footer.php"); ?>
	
</body>
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

<?php

}else{
	header("location:".Conectar::ruta()."index.php");
}
?>
</html>