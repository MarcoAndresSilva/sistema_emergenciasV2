<?php
require_once("../../config/conexion.php");

if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
?>

<!DOCTYPE html>
<html>
<?php require_once("../MainHead/head.php"); ?>
<link rel="stylesheet" href="./estilopersonal.css">
<title>Sistema Emergencia</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <h3>Crear Usuario</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="#">Nuevo</a></li>
                                <li class="active">Crear Usuario</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header>

            <h5 class="m-t-lg with-border">Ingresar informaci&oacute;n</h5>

            <div class="row">
                <form class="event-box" action="nuevoevento.js" method="post" id="event_form" enctype="multipart/form-data">
                    
                    <div class="col-lg-5">
                        <fieldset class="form-group">
                            <label class="form-label semibold" for="exampleInput">Ingrese su Nombre </label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                        </fieldset>
                    </div>
                    <div class="col-lg-5">
                        <fieldset class="form-group">
                            <label class="form-label semibold" for="exampleInput">Ingrese su Apellido </label>
                            <input type="text" class="form-control" id="apellido" placeholder="Apellido">
                        </fieldset>
                    </div>
                    
                    <div class="col-lg-5">
                        <fieldset class="form-group">
                            <label class="form-label semibold" for="mail">Direcci&oacute;n de correo electr&oacute;nico</label>
                            <input type="email" class="form-control" id="mail" placeholder="Ingrese su email" value="">
                        </fieldset>
                    </div>
                    
                    <div class="col-lg-5">
                        <fieldset class="form-group">
                            <label class="form-label semibold" for="exampleInput">Nombre de usuario </label>
                            <input type="text" class="form-control" id="usuario" placeholder="Usuario">
                        </fieldset>
                    </div>
                      <div class="col-lg-5">
                                <label class="form-label semibold" for="exampleInput">Telefono</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">+56</span>
                              </div>
                              <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Nuevo número de teléfono" required>
                          </div>
                        </div>                  <div class="col-lg-5">
                        <fieldset class="form-group">
                            <label class="form-label semibold" for="exampleInput"> Contrase&ntilde;a </label>
                            <input type="password" class="form-control" id="contrasena" placeholder="*********">
                        </fieldset>
                    </div>

                    <div class="col-lg-5">
                        <fieldset class="form-group">
                            <label class="form-label semibold" for="exampleInput">Tipo de usuario</label>
                            <select id="usu_tipo" class="form-control" >
                                <!-- Datos recopilados desde funcion get_categoria -->
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-lg-10">
                        <button type="button" class="btn btn-round btn-inline btn-primary" id="btnGuardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div><!--.container-fluid-->
    </div><!--.page-content-->
    
    <?php require_once("../MainFooter/footer.php"); ?>
    
    </body>
        
    <?php
       require_once("../MainJs/js.php"); 
       
    }else{
        header("Location:". Conectar::ruta () ."index.php");
    }
    
    ?>
    <script type="text/javascript" src="./crearusuario.js"></script>

</html>
