<?php
require_once("../../config/conexion.php");
require_once("../../models/usuario.php");

if (isset($_SESSION["usu_id"])) {
    $usuario = new Usuario();
?>

<!DOCTYPE html>
<html>
<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="./estilopersonal.css">
<title>Sistema Emergencia</title>
<script defer type="text/javascript" src="./searchpass.js"></script>
<script defer type="text/javascript" src="./mesesExpiracion.js"></script>
<script async src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

</head>

<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php"); ?>

    <div class="mobile-menu-left-overlay"></div>

    <?php require_once("../MainNav/nav.php"); ?>

    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding table-responsive-sm" id="tabla-password">
                    <div class="input-group rounded">
                        <input type="search" class="form-control rounded" placeholder="Buscar" aria-label="Search" aria-describedby="search-addon" id="search-pass" />
                        <span class="input-group-text border-0" id="search-addon">
                        <i class="fas fa-search"></i>
                        </span>
                        <div class="form-floating">
                            <select class="form-select" id="selectStatus" >
                                <option value="0" selected>Todos</option>
                                <option value="Vulnerable">Vulnerable</option>
                                <option value="Contrase&ntilde;a expirada">Contrase&ntilde;a Expirada</option>
                                <option value="Seguro">Seguro</option>
                            </select>
                            <label for="floatingSelect">Estado de seguridad</label>
                        </div>
                        <div class="input-group rounded">
                            <input class="form-control rounded" type="number" name="meses" placeholder="Numeros de meses" id="mesesexpiracion">
                        </div>
                    </div>
                    
                    
                    <table id="tabla-general tabla-basica" class="table table-bordered table-responsive table-striped table-vcenter js-dataTable-js">
                        <thead>
                            <tr>
                                <th style="width:10%">Nombre</th>
                                <th style="width:10%">Apellido</th>
                                <th style="width:20%">Estado</th>
                                <th style="width:20%">Correo</th>
                                <th >Detalle</th>
							</tr>
                        </thead>
                        <tbody id="datos-pass">
                        <?php 
                            $allUser = $usuario->get_usuarios_status_passwords();
                            foreach ($allUser as $user) {
                            echo "<tr class='datos-usuarios' >";
                                echo "<td>". $user['nombre']. "</td>";
                                echo "<td>". $user['apellido']. "</td>";
                                echo "<td>";
                                if ($user['mayuscula'] and $user['minuscula'] and $user['numero'] and $user['especiales'] and $user['largo']) {
                                    if (!$user['fecha']) {
                                        echo "<span class='badge badge-warning bg-warning text-warning-emphasis'>Contrase&ntilde;a expirada</span>";
                                        echo "<span class='badge badge-success bg-success'>Seguro</span>";
                                    }else {
                                        echo "<span class='badge badge-success bg-success'>Seguro</span>";
                                    }
                                } else {
                                    echo "<span class='badge badge-danger bg-danger'>Vulnerable</span>";
                                }
                                
                                echo "</td>";
                                echo "<td>". $user['correo']. "</td>";
                                echo "<td>";
                                if (!$user['mayuscula']) {
                                    echo '<p>
                                    <span>    
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-letter-case-upper"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19v-10.5a3.5 3.5 0 0 1 7 0v10.5" /><path d="M3 13h7" /><path d="M14 19v-10.5a3.5 3.5 0 0 1 7 0v10.5" /><path d="M14 13h7" /></svg>
                                    </span>
                                    Le falta mayúsculas en la contraseña.</p>';
                                }
                                if (!$user['minuscula']) {
                                    echo '<p>
                                    <span>
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-letter-case-lower"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.5 15.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" /><path d="M10 12v7" /><path d="M17.5 15.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" /><path d="M21 12v7" /></svg> 
                                    </span>
                                    Le falta minúsculas en la contraseña.</p>';
                                }
                                if (!$user['numero']) {
                                    echo '<p>
                                    <span>
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-number"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v-10l7 10v-10" /><path d="M15 17h5" /><path d="M17.5 10m-2.5 0a2.5 3 0 1 0 5 0a2.5 3 0 1 0 -5 0" /></svg>
                                    </span>
                                     Le falta números en la contraseña.</p>';
                                }
                                if (!$user['especiales']) {
                                    echo '<p>
                                    </span>
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-at"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28" /></svg>
                                     </span>
                                    Le faltan caracteres especiales en la contraseña.</p>';
                                }
                                if (!$user['largo']) {
                                    echo '<p>
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-number-8"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 12h-1a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1 -1v-2a1 1 0 0 0 -1 -1" /></svg>
                                     La contraseña es demasiado corta.
                                    
                                    </p>';
                                }
                                if (!$user['fecha']) {
                                    echo '<p><span>
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-calendar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 2a1 1 0 0 1 .993 .883l.007 .117v1h1a3 3 0 0 1 2.995 2.824l.005 .176v12a3 3 0 0 1 -2.824 2.995l-.176 .005h-12a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-12a3 3 0 0 1 2.824 -2.995l.176 -.005h1v-1a1 1 0 0 1 1.993 -.117l.007 .117v1h6v-1a1 1 0 0 1 1 -1zm3 7h-14v9.625c0 .705 .386 1.286 .883 1.366l.117 .009h12c.513 0 .936 -.53 .993 -1.215l.007 -.16v-9.625z" /><path d="M12 12a1 1 0 0 1 .993 .883l.007 .117v3a1 1 0 0 1 -1.993 .117l-.007 -.117v-2a1 1 0 0 1 -.117 -1.993l.117 -.007h1z" /></svg>
                                    </span> Contrase&ntilde;a expirada, Cambiar contraseña.</p>';
                                }
                                if ($user['mayuscula'] and $user['minuscula'] and $user['numero'] and $user['especiales'] and $user['largo']) {
                                    echo '<p>
                                    <span> 
                                    <svg  xmlns="http://www.w3.org/2000/svg" color="green" width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-shield-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.46 20.846a12 12 0 0 1 -7.96 -14.846a12 12 0 0 0 8.5 -3a12 12 0 0 0 8.5 3a12 12 0 0 1 -.09 7.06" /><path d="M15 19l2 2l4 -4" /></svg>
                                    </span>
                                    Contrase&ntilde;a Robusta </p>';
                                }
                                echo "</td>";
                            }
                        ?>

                        </tbody>
                        
                    </table>
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
 

</html>
