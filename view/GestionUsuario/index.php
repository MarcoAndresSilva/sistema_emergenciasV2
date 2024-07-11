<?php
require_once("../../config/conexion.php");
require_once("../MainJs/js.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
    
    ?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.css"> 
<link rel="stylesheet" href="./gestionUsuario.css">
<title>Sistema Emergencia</title>
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script defer src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<script defer src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
<script defer src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.min.js"></script>
<script defer src="./GestionPerfile.js"></script>
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
				<div class="container">
				    <h2>Información del Usuario</h2>
				    <a href="../CrearUsuario/" class="btn btn-success">Crear usuario</a>
				    <div id="userInfo" class="row justify-content-center">
				        <div class="spinner-border text-primary mt-5" role="status">
				            <span class="sr-only">Cargando...</span>
				        </div>
				    </div>
				</div>
	        </div><!--.container-fluid-->
	    </div><!--.page-content-->
		<?php require_once("../MainFooter/footer.php"); ?>

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
	</body>
</html>
<?php
}else{
	header("location:".Conectar::ruta()."index.php");
}
?>
