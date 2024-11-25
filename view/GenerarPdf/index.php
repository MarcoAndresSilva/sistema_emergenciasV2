<?php
require_once("../../config/conexion.php");
require_once("../../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();
	?>
<!DOCTYPE html>
<html>
	<?php require_once("../MainHead/head.php") ?>
<title>Sistema Emergencia</title>
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
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
                            <h3>Informe de Evento</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="#">Documento</a></li>
                                <li class="active">Generar Infome</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header>

    <div id="app" class="container bg-white">
      <h1 class="title">Generador de informe pdf</h1>

     <div class="input-group mb-3">
    <input
        id="id-evento"
        class="form-control"
        type="text"
        value="<?php echo isset($_GET['id_evento']) ? htmlspecialchars($_GET['id_evento']) : ''; ?>"
        placeholder="Buscar Evento por ID">
    <div class="input-group-append">
        <button id="search-btn" class="btn btn-primary">Buscar Evento</button>
    </div>
</div>
      <div class="field has-text-centered">
      </div>

      <div id="pdf-container" class="bg-white " style="display: none;">
        <h2 class="subtitle has-text-centered">Información Del Evento <span id="numero-ticket"></span></h2>
          <div class=" mx-auto">
             <figure class="figure">
                <img id="image-mapa" class="figure-img img-fluid rounded border" src="">
                <figcaption class="figure-caption text-right" id="gps">lat: <span id="latitud"></span>, lon: <span id="longitud"></span></figcaption>
             </figure>
          </div>

        <table class="table">
          <tr><th>Ticket</th><td id="numero-ticket"></td></tr>
          <tr><th>Fecha Inicio</th><td id="fecha-inicio"></td></tr>
          <tr><th>Fecha Cierre</th><td id="fecha-cierre"></td></tr>
          <tr><th>Categoria</th><td id="categoria"></td></tr>
          <tr><th>Descripción</th><td id="descripcion"></td></tr>
          <tr><th>Nivel</th><td id="nivel"></td></tr>
          <tr><th>Usuario Creador</th><td id="usuario-creador"></td></tr>
          <tr><th>Direccion</th><td id="direccion"></td></tr>
          <tr><th>Lista de Participantes:</th><td id="lista-participantes"></td></tr>
        </table>

        <div class="text-right pb-3 mb-3"> <b>Informe Generado:</b> <span id="fecha-informe"></span> </div>
      </div>

      <!-- Botón para generar PDF -->
      <div class="field has-text-centered">
        <button id="generate-pdf-btn" class="btn btn-info">Generar PDF</button>
      </div>
    </div>
  </div>
    </div><!--.page-content-->
  <script type="module" src="./appContruirInformeEvento.js"></script>
    <?php require_once("../MainJs/js.php"); ?>
    <?php require_once("../MainFooter/footer.php"); ?>
</body>
</html>
