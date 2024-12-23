<?php
require_once("../../models/Permisos.php");
require_once("../../config/conexion.php");

Permisos::redirigirSiNoAutorizado();
?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
<link rel="stylesheet" href="./nivelCateogiraStyle.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.css"> 
<title>Sistema Emergencia</title>
<script defer type="text/javascript" src="./NivelCategoria.js"></script>
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script defer src="https://cdn.datatables.net/1.13.6/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.js"></script>
<script defer src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
<script defer src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.min.js"></script>

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
							<h3>Categoría de Emergencias</h3>
							<ol class="breadcrumb breadcrumb-simple">
							  <li><a href="../Home/">Inicio</a></li>
                <li class="active">Administración</li>
                <li class="active">Nivel categoria</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<h5 class="m-t-lg with-border">Informaci&oacute;n de Instituciones de Emergencias</h5>
			<button class="btn btn-success" id="addButton">Agregar Categoría</button>

<div class="container">
					<table id="miTabla" class="table display table-bordered table-responsive table-striped table-vcenter js-dataTable-js">
						<thead>
							<tr>
							<th>ID Categoría</th>
							<th>Nombre Categoría</th>
							<th>Nivel de Evaluación</th>
							<th>Accion</th>
							</tr>
						</thead>
						<tbody>
						<!-- Las filas se añadirán dinámicamente aquí -->
						</tbody>
					</table>

        </div><!--.container-fluid-->
    </div><!--.page-content-->

</div>
	<?php require_once("../MainFooter/footer.php"); ?>
	
	<?php require_once("../MainFooter/footer.php"); ?>
</body>
<?php
require_once("../MainJs/js.php");?>
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
</html>
