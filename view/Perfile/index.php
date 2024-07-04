<?php
require_once("../../config/conexion.php");
require_once("../MainJs/js.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
	
	?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
<link rel="stylesheet" href="./nivelCateogiraStyle.css">
<title>Sistema Emergencia</title>
<script defer type="text/javascript" src="./perfile.js"></script>
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
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
    <div id="userInfo" class="row justify-content-center">
        <div class="spinner-border text-primary mt-5" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
</div>

<div class="container">
<h1>Cambiar Contrase&ntilde;a</h1>
        <form id="updatePasswordForm">
            <div class="form-group">
                <label for="old_pass">Contraseña antigua:</label>
                <input type="password" class="form-control" id="old_pass" name="old_pass" required>
            <input type="checkbox" onclick="showPassword('old_pass')"> Mostrar Contraseña
            </div>

            <div class="form-group">
                <label for="new_pass">Nueva contraseña:</label>
                <input type="password" class="form-control" id="new_pass" name="new_pass" required>
                <small id="passwordHelp" class="form-text text-muted">Debe tener al menos 8 caracteres.</small>
            <input type="checkbox" onclick="showPassword('new_pass')"> Mostrar Contraseña
        </div>

            <div class="form-group">
                <label for="confirm_new_pass">Confirmar nueva contraseña:</label>
                <input type="password" class="form-control" id="confirm_new_pass" name="confirm_new_pass" required>
                <small id="confirmHelp" class="form-text"></small>
                <input type="checkbox" onclick="showPassword('confirm_new_pass')"> Mostrar Contraseña
            </div>


            <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
        </form>
    </div><!--.container -->
<div class="container">
<h1>Cambiar Numero</h1>


<form id="updatePhoneForm">
    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <div class="input-group-text">+56</div>
        </div>
        <input type="text" class="form-control" id="new_phone" name="new_phone" placeholder="Nuevo número de teléfono" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar número de teléfono</button>
</form>
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

}else{
	header("location:".Conectar::ruta()."index.php");
}
?>
</html>
