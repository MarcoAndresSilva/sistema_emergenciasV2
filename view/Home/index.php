<?php
require_once("../../config/conexion.php");

if (isset($_SESSION["usu_id"])) {
	
?>

<!DOCTYPE html>
<?php require_once("../MainHead/head.php"); ?>
<html>
<head>
	<title>Sistema Emergencia</title>
	<link rel="stylesheet" href="../../public/css/lib/lobipanel/lobipanel.min.css">
	<link rel="stylesheet" href="../../public/css/separate/vendor/lobipanel.min.css">
	<link rel="stylesheet" href="../../public/css/lib/jqueryui/jquery-ui.min.css">
	<link rel="stylesheet" href="../../public/css/separate/pages/widgets.min.css">
	<link rel="stylesheet" href="../../public/css/lib/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="../../public/css/lib/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="../../public/css/main.css">
	<link rel="stylesheet" href="./estilopersonal.css">
</head>

<body class="with-side-menu">
	
	
	<?php require_once("../MainHeader/header.php"); ?>
    <div class="mobile-menu-left-overlay"></div>

    <?php require_once("../MainNav/nav.php"); ?>

    <div class="page-content">
		<div class="container-fluid">
        
			<div class="row">
				<div class="col-xl-12"    >

				<!-- nuevo grafico con ChartJS -->										
					<div>
						<canvas id="myChart" style="height: 40vh; width:100%"></canvas>
					</div>
				</div>
				<!--.col-->
				<div class="col-xl-12">
					<div class="row">
						<div class="col-sm-6">
							<article class="statistic-box red">
								<div>
									<div class="number" id="number-open"><!-- Trae los numeros desde el js --></div>
									<div class="caption-open">
										<div>Emergencias Abiertas</div>
									</div>
									<div class="percent">
										<div class="arrow up"></div>
										<p id="porcentaje-emer-open" ><!-- porcentaje desde js --></p>
									</div>
								</div>
							</article>
						</div>
						<!--.col-->
						<div class="col-sm-6">
							<article class="statistic-box purple">
								<div>
									<div class="number" id="number-close"><!-- Trae los numeros desde el js --></div>
									<div class="caption-close">
										<div>Emergencias Cerradas</div>
									</div>
									<div class="percent">
										<div class="arrow down"></div>
										<p id="porcentaje-emer-close"><!-- porcentaje desde js --></p>
									</div>
								</div>
							</article>
						</div>
						<!--.col-->
						<div class="col-sm-6">
							<article class="statistic-box yellow">
								<div>
									<div class="number" id="number-eventos-criticos-medios"><!-- Trae los numeros desde el js --></div> 
									<div class="caption-available">
										<div>Emergencias Criticas- Medias</div>
									</div>
									<div class="percent">
										<div class="arrow down"></div>
										<p id="porcentaje-criticos-medios"><!-- porcentaje desde js --></p>
									</div>
								</div>
							</article>
						</div>
						<!--.col-->
						<div class="col-sm-6">
							<article class="statistic-box green">
								<div>
									<div class="number" id="number-eventos-bajas-comunes"><!-- porcentaje desde js --></div>
									<div class="caption">
										<div>Emergencias Bajas - Comunes</div>
									</div>
									<div class="percent">
										<div class="arrow up"></div>
										<p id="porcentaje-bajas-comunes"><!-- porcentaje desde js --></p>
									</div>
								</div>
							</article>
						</div>
						<!--.col-->
					</div>
					<!--.row-->
				</div>
				<!--.col-->
			</div>
			<!--.row-->

			<div class="row">
				<div class="col-xl-6 dahsboard-column">
					<section class="box-typical box-typical-dashboard panel panel-default scrollable">
						<header class="box-typical-header panel-heading">
							<h3 class="panel-title">Emergencias Inmediatas</h3>
						</header>
						<div class="box-typical-body panel-body">
							<table class="tbl-typical">
								
								<tbody id="critico-medio">
									<!-- Datos de eventos criticos y medios desde funcion js -->
								</tbody>
							</table>
						</div>
						<!--.box-typical-body-->
					</section>
					<!--.box-typical-dashboard-->
					
				</div>
				<!--.col-->
				<div class="col-xl-6 dahsboard-column">
					<section class="box-typical box-typical-dashboard panel panel-default scrollable">
						<header class="box-typical-header panel-heading">
							<h3 class="panel-title">Emergencias No inmediatas</h3>
						</header>
						<div class="box-typical-body panel-body">
							<table class="tbl-typical">
								
								<tbody id="bajo-comun">
									<!-- Datos de eventos criticos y medios desde funcion js -->
								</tbody>
							</table>
						</div>
						<!--.box-typical-body-->
					</section>
					<!--.box-typical-dashboard-->
					
					<!--.box-typical-dashboard-->
				</div>
				<!--.col-->
			</div>

        </div><!--.container-fluid-->
    </div><!--.page-content-->
	
	<?php require_once("../MainFooter/footer.php"); ?>

</body>

<?php require_once("../MainJs/js.php"); ?>
<script type="text/javascript" src="home.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<?php

}else{
	header("location:".Conectar::ruta()."index.php");
}
?>


</html>