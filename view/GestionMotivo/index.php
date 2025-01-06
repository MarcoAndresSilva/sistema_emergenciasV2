<?php
require_once("../../config/conexion.php");
require_once("../../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();
	?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
<link rel="stylesheet" href="./nivelCateogiraStyle.css">
<title>Sistema Emergencia</title>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" />
<script  src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<script defer src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script defer src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<script defer type="text/javascript" src="./GestionMotivo.js"></script>
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
								<li><a href="../Home/">Inicio</a></li>
                <li class="active">Administración</li>
                <li class="active">Gestion de motivos de cierre</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

            <div class="container">
			<h5 class="m-t-lg with-border">Informaci&oacute;n de Instituciones de Emergencias</h5>
			<button class="btn btn-success" id="addButton" onclick="fn_agregar_motivo_cierre()">Agregar motivo</button>
					<table id="miTabla" class="table table-bordered table-responsive table-striped table-vcenter js-dataTable-js">
						<thead>
							<tr>
							<th>motivo</th>
							<th>Accion</th>
							</tr>
						</thead>
						<tbody>
						<!-- Las filas se añadirán dinámicamente aquí -->
						</tbody>
					</table>
            </div>
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

require_once("../MainJs/js.php");
?>
</html>
