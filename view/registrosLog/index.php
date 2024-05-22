<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>

<!DOCTYPE html>
<html>
<?php require_once("../MainHead/head.php") ?>
<link rel="stylesheet" href="./StyleRegistroLog.css">
<title>Sistema Emergencia</title>
<script defer type="text/javascript" src="./registroLog.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css" />
<script defer src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>

</head>

<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php"); ?>

    <div class="mobile-menu-left-overlay"></div>

    <?php require_once("../MainNav/nav.php"); ?>

    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding table-responsive-sm" id="container-tabla-log">
            <table class="table" id="tabla-log">
                    
            </table> 
            </div> <!-- #container-tabla-log -->
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
