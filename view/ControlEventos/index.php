<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
	
	?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
<link rel="stylesheet" href="./estilopersonaleventos.css">
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
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
							<h3>Eventos</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="#">Eventos</a></li>
								<li class="active">En proceso</li>
							</ol>
						</div>
					</div>
				</div>
			</header>
			
			<h5 class="m-t-lg with-border">Informaci&oacute;n Actual</h5>
			<div class="box-typical box-typical-padding table table-responsive-sm" >
				<!-- <button id="btn"type='button' class='btn btn-inline btn-primary btn-sm ladda-button btnMostrarIdEvento'> <i class='fa fa-users'></i></button> -->

				<!-- tabla de asuntos inmediatos (Alto) -->
                <table class="table tabla-media tabla-basica table-bordered table-striped table-vcenter js-dataTable-js">
					<thead>
						<tr>
                            <th style="width:5%">ID</th>
							<th style="width:9.5%">Categor&iacute;a</th>
							<th style="width:9.5%">Direcci&oacute;n</th>
                            <th style="width:25%">Asignaci&oacute;n</th>
                            <th style="width:10%">Nivel Peligro</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:15%">Hora Apertura</th>
                            <th style="width:5%">Derivar</th>
							<th style="width:5%">Cerrar</th>
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
			
			<!-- Selector de unidades Modal-->
			<div id="selector-unidad" class="modal-overlay modalBox">
				<div class="btn-cancelar">
					<button id="btn"type='button' class='btn btn-inline btn-danger btn-sm ladda-button 	btnCancelar'> <i class='fa fa-close'></i>
					</button>
				</div>
				<div id="distribucion-box">
					<div id="distribucion-espacios">
						<h3 class="derivacion-title">Panel de Derivación</h3>
						<fieldset class="form-group" id="grupito">
							<label class="form-label bold" for="id_nivel_peligro" id="asignaciones-title">Id de Evento</label>
							<div id="ev_id"></div>
						</fieldset>
						<fieldset class="form-group" id="grupito">
							<label class="form-label bold" for="id_nivel_peligro" id="asignaciones-title">Categoria</label>
							<div id="cat_nom"></div>
						</fieldset>
						<fieldset class="form-group">
							<label class="form-label bold" for="id_nivel_peligro" id="asignaciones-title">Nivel de Peligro </label>
							<select id="niv_id" class="form-control" >
								<!-- Datos de consulta -->
							</select>
						</fieldset>
					</div>
					<div id="distribucion-espacios">
						<fieldset class="form-group">
							<label class="form-label bold" id="asignaciones-title">Asignar Unidad</label>
							<div id="unidadOptions" class="form-check">
								<!-- Las opciones se agregarán aquí dinámicamente -->
							</div>
						</fieldset>

						<fieldset class="form-group box-selection" id="buttons-group">
							<label class="form-label semibold" id="asignaciones-title" for="id_nivel_peligro">Seleccionar</label>
							<div class="btn-box">
								<button id="btn"type='button' class='btn btn-inline btn-success btn-sm ladda-button btnActualizarTodos'> Derivar
									<span><i class="fa-solid fa-arrow-up-from-bracket"></i> </span>
								</button>		
							</div>
							<!-- <div class="btn-box">
								<button id="btn"type='button' class='btn btn-inline btn-warning btn-sm ladda-button btnCerrarEvento close-btn'>Cerrar Evento <span> <i class="fa-regular fa-circle-xmark"></i></span>
					 			</button>
							</div> -->

						</fieldset>	
					</div>
				</div>
			</div>

			<div id="modal-mapa" class="modal-overlay">
                <div class="vista-mapa" id="map">
                </div>
                <button id='btn' type='button' class='btn btn-inline btn-primary btn-sm ladda-button btnCrearRuta' > Ir </button>
                <span class="glyphicon glyphicon-remove CerrarModalMap"></span>
            </div>

			<div id="selector-cerrar" class="modal-overlay modalBox" style="z-index: 1001">
				<div class="btn-cancelar">
					<button id="btn"type='button' class='btn btn-inline btn-danger btn-sm ladda-button btnCancelarCerrar'> <i class='fa fa-close'></i>
					</button>
				</div>
				<form class="event-box" action="nuevoevento.js" method="post" id="event_form" enctype="multipart/form-data">
					<div id="distribucion-box">
						<div id="distribucion-espacios">
							<h3 class="derivacion-title">Panel de Cierre</h3>

							<fieldset class="form-group" id="grupito">
								<label class="form-label bold" id="asignaciones-title">Id de Evento</label>
								<div id="ev_id_cierre"></div>
							</fieldset>
							<fieldset class="form-group" id="grupito">
								<label class="form-label bold" id="asignaciones-title">Categoria</label>
								<div id="cat_nom_cierre"></div>
							</fieldset>
							<fieldset class="form-group" id="grupito">
								<label class="form-label bold" id="asignaciones-title">Nombre Apellido</label>
								<input type="text" class="form-control" id="nombre_apellido" placeholder="Nombre y Apellido" value="<?php echo $_SESSION['usu_nom'] . ' ' . $_SESSION['usu_ape']; ?>" readonly>
							</fieldset>
							<fieldset class="form-group" id="grupito">
								<label class="form-label bold" id="asignaciones-title">Ingrese un detalle</label>
								<input type="text" class="form-control" id="detalle_cierre" name="detalle_cierre" placeholder="Detalle del cierre">
							</fieldset>
							<fieldset class="form-group">
								<label class="form-label bold" id="asignaciones-title">Seleccione el motivo</label>
								<select id="motivo_cierre" class="form-control">
									<!-- Datos de consulta -->
								</select>
							</fieldset>
							<fieldset class="form-group">
								<label class="form-label semibold" for="exampleInput">Desea adjuntar una imagen de la emergencia?</label>
								<input type="file" id="imagen" name="imagen" accept="image/*">
								<button id="btnCargarArchivo" class="btn btn-round btn-inline btn-primary">Cargar Archivo</button>
								<label id="archivoAdjuntado">No hay archivo adjunto (.JPG/.JPEG/.PNG)</label>
							</fieldset>
							<fieldset class="form-group box-selection" id="buttons-group">
								<label class="form-label semibold" id="asignaciones-title">Seleccionar</label>
								<div class="btn-box">
									<button id="btn" type="button" class="btn btn-inline btn-warning btn-sm ladda-button btnCerrarEvento close-btn">
										Cerrar Evento <span><i class="fa-regular fa-circle-xmark"></i></span>
									</button>
								</div>
							</fieldset>
						</div>
					</div>
				</form>
			</div>
		</div>
   	</div><!--.page-content-->

<?php require_once("../MainFooter/footer.php"); ?>

</body>

<?php
require_once("../MainJs/js.php");

}else{
	header("location:".Conectar::ruta()."index.php");
}
?>
<script type="text/javascript" src="./evento.js"></script>
</html>