<?php

//  Desarrollado por: Ilustre Municipalidad de Melipilla.
//  Departamento:Informática.
//  Directora de departamento: Limbi Odeth Ortiz Neira.
//  Jefe de proyecto: Cristian Esteban Suazo Olguin 

require_once("config/conexion.php");
if (isset($_POST["enviar"]) and $_POST["enviar"] == "si") {
    require_once("models/Usuario.php");
    $usuario = new Usuario();
    $usuario->login();
}
?>


<!DOCTYPE html>
<html>
<head lang="es">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<link rel="stylesheet" href="public/css/estilos-personalizados-login.css">
<link rel="stylesheet" href="public/css/lib/summernote/summernote.css"/>
<link rel="stylesheet" href="public/css/separate/pages/editor.min.css">
<link rel="stylesheet" href="public/css/separate/pages/login.min.css">
<link rel="stylesheet" href="public/css/lib/font-awesome/font-awesome.min.css">
<link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="public/css/main.css">
<link rel="stylesheet" href="public/css/estilos-personalizados-login.css">
<link rel="stylesheet" href="view/MainHeader/estiloheader.css">
<link rel="icon" href="../../public/img/logo-meli-sin-nombre.png">

<title>Sistema de Emergencias Comunales</title>
</head>

<body>
    <div class="page-center">
        <div class="container-login">
            <div class="box-login">

                <div class="bg-illustration">
                    <img src="public\img\Logotipo_municipal_vertical.png" alt="logo">
                </div>

                <div class="login-form-box">
                    <form class="sign-box" action="" method="post" id="login_form">
                        <div class="sign-avatar">
                            <img src="public/img/avatar-sign.png" alt="">
                        </div>
                        <header class="sign-title">Acceder</header>
                        <?php
                        if (isset($_GET["m"])) {
                            switch ($_GET["m"]) {
                                case "1":
                                    ?>
                                    <div class="alert alert-danger role=" alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <div class="d-flex align-items-center justify-content-start">
                                            <i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-cs-t-0"></i>
                                            <span>El Usuario y/o Contraseña son incorrectos</span>
                                        </div>
                                    </div>
                                    <?php
                                    break;
                                case "2";
                                    ?>
                                    <div>
                                        <div class="alert alert-danger role=" alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-cs-t-0"></i>
                                                <span>Los campos estas vacios</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                        <div class="form-group">
                            <input type="text" id="usu_name" name="usu_name" class="form-control" placeholder="Usuario" />
                        </div>
                        <div class="form-group">
                            <input type="password" id="usu_pass" name="usu_pass" class="form-control"
                                placeholder="Contrase&ntilde;a" />
                        </div>
                        <div class="form-group">
                            <div class="float-right reset">
                                <a href="reset-password.html">Cambiar contrase&ntilde;a</a>
                            </div>
                        </div>
                        <input type="hidden" name="enviar" class="form-control" value="si">
                        <button type="submit" class="btn btn-rounded">Iniciar Sesi&oacute;n</button>
                        <p class="sign-note">Necesitas acceder al sistema <a href="sign-up.html">Registrate</a></p>

                    <form>
                </div>
            </div>
                
            </div>
        </div>
    </div><!--.page-center-->

    <script src="public/js/lib/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="public/js/lib/match-height/jquery.matchHeight.min.js"></script>
    <script src="public/js/lib/tether/tether.min.js"></script>
    <script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
    <script src="public/js/plugins.js"></script>               
    <script src="public/js/app.js"></script>
    <script src="index.js"></script>
</body>

</html>