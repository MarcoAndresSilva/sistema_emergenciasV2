<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
    
    ?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="estilopersonal.css">
<link rel="stylesheet" href="estiloheader.css">
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
                            <h3>Historial de Eventos</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="#">Home</a></li>
								<li class="active">Historial</li>
							</ol>
					    </div>
                        <!-- <div id="filtros">
                             <div class="select">
                                <select name="filtro-select" id="filtro-select">
                                    <option value="1"></option>
                                    <option value="2"></option>
                                    <option value="3"></option>
                                    <option value="4"></option>
                                    <option value="5"></option>
                                </select>
                            </div> 
                        </div> -->
                        <div class="input">
                                <input type="text" class="search-input" placeholder="search..." style="display: none;"/>
                        </div>
					</div>
				</div>
            </header>
            
            <h5 class="m-t-lg with-border">Informaci&oacute;n de Eventos</h5>
            
            <!-- <div class="estructura-tablas"> -->

                <div class="box-typical box-typical-padding table-responsive-sm" id="tabla-principal-historial">
                    <table id="tabla-general tabla-basica" class="table table-bordered table-responsive table-striped table-vcenter js-dataTable-js">
                        <thead>
                            <tr>
                                <th style="width:10%">ID</th>
                                <th style="width:25%">Categor&iacute;a</th>
                                <th style="width:40%">Direcci&oacute;n</th>
                                <th style="width:20%">Fecha Apertura</th>
                                <th style="width:10%">Detalle</th>
							</tr>
                        </thead>
                        <tbody id="datos-generales">
                            <!-- Datos desde js -->
                        </tbody>
                        
                    </table>
                </div>
                <!-- </div> -->
                <!-- Modal informacion Emergencia -->
                <div id="modalInfoEmergencia">
                    <div class="contenedorInfoEmergencia">
                        <div class="btnDatosEmergencia">
                            <button id="btn"type='button' class='btn btn-inline btn-danger btn-sm ladda-button btnCerrarInfoEmergencia'> <i class='fa fa-close'></i></button>
                        </div>
                        <div class="titleDatosEmergencia">
                            <h2>Datos Emergencia</h2>
                        </div>
                        <div class="infoEmergencia">
                            <div class="datosEmergencia table">

                                <div class="dato-emergencia-item dato-emergencia-item_1">
                                    <label for="">N° indentificador:</label>
                                    <p id="id_info_emergencia"></p>
                                </div>                        
                                <div class="dato-emergencia-item dato-emergencia-item_2">
                                    <label for="">Categor&iacute;a:</label>
                                    <p id="categoria_info_emergencia"></p>
                                </div>
                                <div class="dato-emergencia-item dato-emergencia-item_1">
                                    <label for="">Direcci&oacute;n:</label>
                                    <p id="direccion_info_emergencia"></p>
                                </div>
                                <div class="dato-emergencia-item dato-emergencia-item_2">
                                    <label for="">Unidades Asignadas:</label>
                                    <p id="unidades_info_emergencia"></p>
                                </div>
                                <div class="dato-emergencia-item dato-emergencia-item_1">
                                    <label for="">Estado Actual:</label>
                                    <p id="estado_info_emergencia"></p>
                                </div>
                                <div class="dato-emergencia-item dato-emergencia-item_2">
                                    <label for="">Fecha y Hora:</label>
                                    <p id="fecha_info_emergencia"></p>
                                </div>                                
                            </div>
                            <div class="imagenDatosEmergencia">
                                <img id="imagenEmergencia" src="" alt="">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div id="modal-mapa" class="modal-overlay">
                <div class="vista-mapa" id="map">
                </div>
                <button id='btn' type='button' class='btn btn-inline btn-primary btn-sm ladda-button btnCrearRuta' > Ir </button>
                <span class="glyphicon glyphicon-remove CerrarModalMap"></span>
            </div>

        </div><!--.container-fluid-->
    </div><!--.page-content-->
    
    <?php require_once("../MainFooter/footer.php"); ?>
    
</body>

<?php
require_once("../MainJs/js.php");

}else{
    header("location:".Conectar::ruta()."index.php");
}
?>
<script type="text/javascript" src="./historial.js"></script>
</html>