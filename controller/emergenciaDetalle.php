<?php
require_once("../config/conexion.php");
require_once("../models/Evento.php");
require_once("../models/Categoria.php");
require_once("../models/Unidad.php");
require_once("../models/Estado.php");
require_once("../models/EventoUnidad.php");

$evento = new Evento();
$categoria = new Categoria();
$unidad = new Unidad();
$estado = new Estado();
$eventounidad = new EventoUnidad();
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
if (isset($_GET["op"])) {
    switch ($_GET["op"]) {


        case "listar_detalle_emergencias":
            $datos = $evento->listar_eventosdetalle_por_evento($_POST["ev_id"]);
            ?>
                <?php
                foreach ($datos as $row) {
                    ?>
                        <article class="activity-line-item box-typical">
        
                            <div class="activity-line-date">
                                <?php echo date("d/m/Y - H:i:s", strtotime($row["ev_inicio"])); ?>
                            </div>
        
                            <header class="activity-line-item-header">
                                <div class="activity-line-item-user">
                                    <div class="activity-line-item-user-photo">
                                        <a href="#">
                                            <img src="../../public/  <?php echo $row['usu_tipo']?>.png" alt="">
                                        </a>
                                    </div>

                                    <div class="activity-line-item-user-name">
                                        <?php echo $row['usu_nom'] . ' ' . $row['usu_ape']; ?> 
                                    </div>

                                    <div class="activity-line-item-user-status">
                                        <?php
                                            if ($row['usu_tipo'] == 1) {
                                                echo 'Visualizador';
                                            } else {
                                                echo 'Administrador';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </header>
        
                            <div class="activity-line-action-list">
        
                                <section class="activity-line-action">
                                    <div class="time">
                                        <?php echo date("H:i:s", strtotime($row["ev_inicio"])); ?>
                                    </div>
                                    
                                    <div class="cont">
                                        <div class="cont-in">
                                            <p><?php echo $row['ev_desc'] ?> </p>
                                        </div>
                                    </div>
                                </section>

                            </div>

                        </article>
                    <?php
                }
                ?>
            <?php
        break;

        case "mostrar":
            $datos = $evento->listar_evento_por_id($_POST["ev_id"]);
            if (is_array($datos) == true && count($datos) > 0) {
                foreach ($datos as $row) {
                    $output["ev_id"] = $row["ev_id"];
                    $output["usu_id"] = $row["usu_id"];
                    $output["ev_est_num"] = $row["ev_est"]; // Añadir el estado como número
        
                    if ($row["ev_est"] == 1) {
                        $output["ev_est"] = '<span class="label label-pill label-success">En proceso</span>';
                    } else {
                        $output["ev_est"] = '<span class="label label-pill label-danger">Finalizado</span>';
                    }
                    
                    $output["cat_id"] = $row["cat_id"];
                    $output["ev_direc"] = $row["ev_direc"];
                    $output["ev_desc"] = $row["ev_desc"];
                    $output["ev_inicio"] = date("d/m/Y - H:i:s", strtotime($row["ev_inicio"]));
                    $output["usu_nom"] = $row["usu_nom"];
                    $output["usu_ape"] = $row["usu_ape"];
                    $output["cat_nom"] = $row["cat_nom"];
                }
                echo json_encode($output);
            }
        break;

            
        case "insertdetalle":
            $evento->insert_emergencia_detalle($_POST["ev_id"], $_POST["usu_id"], $_POST["ev_desc"]);
        break;

        case "updatedetalle":
            $evento->update_evento($_POST["ev_id"]);
        break;

        }
    }        
}
