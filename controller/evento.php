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
        case "add_evento":
            // Obtener datos de la sesión del usuario
            $usu_id = $_SESSION["usu_id"];
            $usu_nom = $_SESSION["usu_nom"];
            $usu_ape = $_SESSION["usu_ape"];
            $usu_mail = $_SESSION["usu_correo"];
            $usu_telefono = $_SESSION["usu_telefono"];
        
            $datos = $evento->add_evento(
                // $usu_id,
                $usu_nom, // Llenar con el nom del usuario en sesión
                $usu_ape, // Llenar con el ape del usuario en sesión
                $usu_mail, // Llenar con el correo del usuario en sesión
                $_POST['ev_desc'],
                $_POST['ev_est'],
                $_POST['ev_inicio'],
                $_POST['ev_direc'],
                $_POST['cat_id'],
                $_POST['ev_niv'],
                $_POST['ev_img'],
                $usu_telefono // Llenar con el teléfono del usuario en sesión
            );
        
            if ($datos == true) {
                echo 1;
            } else {
                echo 0;
            }
        break;

        case "carga-imagen":
            //verificar si se obtuvo el ID del evento
            if (isset($_POST['ev_id'])){
                // Verificar si se envió un archivo y no hubo errores
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    // Ruta donde se almacenará la imagen 
                    $ruta_destino = '../public/img/imagenesEventos/' . $_FILES['imagen']['name'];
                    // Mover la imagen del directorio temporal al destino final
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                        // Actualizar la columna ev_img en la base de datos con la ruta de la imagen
                        $datos = $evento->update_imagen_evento($_POST['ev_id'], $ruta_destino);
                        if ($datos == true) {
                            echo 1;
                        } else {
                            echo 0;
                        }
                    } else {
                        echo "Error al mover el archivo.";
                    }
                } else {
                    echo "Error al recibir la imagen.";
                }
            }else {
                echo "Error al recibir el ID del evento.";
            }
        break;
        case "carga-imagen-cierre":
            //verificar si se obtuvo el ID del evento
            if (isset($_POST['ev_id'])){
                // Verificar si se envió un archivo y no hubo errores
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    // Ruta donde se almacenará la imagen 
                    $ruta_destino = '../public/img/imagenesCierres/' . $_FILES['imagen']['name'];
                    // Mover la imagen del directorio temporal al destino final
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                        // Actualizar la columna ev_img en la base de datos con la ruta de la imagen
                        $datos = $evento->update_imagen_cierre($_POST['ev_id'], $ruta_destino);
                        if ($datos == true) {
                            echo 1;
                        } else {
                            echo 0;
                        }
                    } else {
                        echo "Error al mover el archivo.";
                    }
                } else {
                    echo "Error al recibir la imagen.";
                }
            }else {
                echo "Error al recibir el ID del evento.";
            }
        break;

        case "tabla-general":
            $html = "";
            $critico = "";
            $medio = "";
            $bajo = "";
            $comun = "";
            
            
            $datos = $evento->get_evento();
            if (is_array($datos) == true and count($datos) > 0) {
                
                //Datos para Tabla comun variable = $html
                //Recorre los resultados que entrego la funcion get_evento_nivel(1)
                foreach ($datos as $row) {
                    //Variable temporal para recorrido y almacenamiento
                    $recorrido = "";
                    $recorrido .= "<tr>";
                    
                    $recorrido .= "<td id='id_evento_celda' value='" . $row['ev_id'] . "'>" . $row['ev_id'] . "</td>";

                    //Llama a la funcion get_datos_categoria para obtener el nombre de la categoria
                    $datos_categoria = $categoria->get_datos_categoria($row['cat_id']);
                    foreach ($datos_categoria as $row_categoria) {
                        $recorrido .= "<td>". $row_categoria['cat_nom']. "</td>";
                    }

                    $direccion = $row['ev_direc'];
                    // Dividir la cadena en texto y coordenadas
                    $parts = explode(" , ", $direccion);

                    // Si hay coordenadas, eliminarlas y agregar el botón
                    if ($parts[1] !== "No hay coordenadas") {
                        // Eliminar las coordenadas
                        $texto = $parts[0];
                        // Agregar el botón después del texto de la dirección
                        $direccion = $texto . " <button id='btn' type='button' class='btn btn-inline btn-primary btn-sm ladda-button btnDireccionarMapa modal-btn' id='btnDireccionarMapa'> <i class='fa-solid fa-location-dot'></i> </button>";
                    }else {
                        $direccion = $parts[0];
                    }

                    // Si no hay coordenadas, eliminar el texto que indica su ausencia
                    $direccion = str_replace("No hay coordenadas", "", $direccion);
                    $direccion = str_replace("Sin dirección", "", $direccion);

                    $recorrido .= "<td> " . $direccion . " </td>";

                    //Llama a la funcion get_datos_eventounidad para obtener los nombres de las unidades asignadas
                    $datos_asignaciones = $eventounidad->get_datos_eventoUnidad($row['ev_id']);
                    // $datos_asignaciones = [[1,1],[2,2]];
                    if (is_array($datos_asignaciones) && count($datos_asignaciones) > 0) { 
                        $recorrido .= "<td>";
                        $contar = 0;
                        foreach($datos_asignaciones as $row_asignaciones){
                            $unid_id = $row_asignaciones['unid_id'];
                            $datos_unidad = $unidad->get_datos_unidad($unid_id);
                            foreach ($datos_unidad as $row_unidad ) {
                                if ($contar == 0){
                                    $recorrido .= $row_unidad['unid_nom'];
                                    $contar += 1;
                                }else{
                                    $recorrido .= " - " . $row_unidad['unid_nom'];
                                }
                            }
                        }
                        $recorrido .= "</td>";
                    }else{

                        $recorrido .= "<td>No asignada</td>";
                    }


                    //Peligro
                    if($row['ev_niv_id'] == 1){
                        $recorrido .= "<td > <span class='label label-pill label-primary peligro_critico' > Critico </span> </td>";
                    }else if($row['ev_niv_id'] == 2) {
                        $recorrido .= "<td > <span class='label label-pill label-primary peligro_medio' > Medio </span> </td>";
                    }else if($row['ev_niv_id'] == 3) {
                        $recorrido .= "<td > <span class='label label-pill label-primary peligro_bajo' > Bajo </span> </td>";
                    }else if($row['ev_niv_id'] == 0) {
                        $recorrido .= "<td > <span class='label label-pill label-primary peligro_comun' > Comun </span> </td>";
                    }
                    
                    //Llama a la funcion get_datos_estado para obtener el estado
                    $dato_estado = $estado->get_datos_estado($row['ev_est']);
                    foreach ($dato_estado as $row_estado) {
                        $recorrido.= "<td>". $row_estado['est_nom']. "</td>";
                    }
                    
                    // Hora de Apertura
                    $recorrido .= "<td>" . $row['ev_inicio'] . "</td>";
                    
                     // boton derivar
                     $recorrido .= "<td> <button id='btn' type='button' class='btn btn-inline btn-primary btn-sm ladda-button btnMostrarDatos modal-btn'> <i class='fa-solid fa-up-right-from-square'></i> </button>
                     </td>";
 
                      // boton cerrar
                      $recorrido .= "<td> <button id='btn' type='button' class='btn btn-inline btn-danger btn-sm ladda-button btnPanelCerrar modal-btn'> <i class='fa-solid fa-square-xmark'></i> </button>
                      </td>";

                    $recorrido .= "</tr>";

                    //Filtro de filas por nivel de peligro
                    if($row['ev_est'] == 1){
                        if($row['ev_niv_id'] == 1){
                            $critico .= $recorrido; 
                        }else if($row['ev_niv_id'] == 2) {
                            $medio .= $recorrido; 
                        }else if($row['ev_niv_id'] == 3) {
                            $bajo .= $recorrido; 
                        }else if($row['ev_niv_id'] == 0) {
                            $comun .= $recorrido;
                        }
                    }
                    $html .= $recorrido;
                }
                $respuesta = array(
                    'html' => $html,
                    'critico' => $critico,
                    'medio' => $medio,
                    'bajo' => $bajo,
                    'comun' => $comun
                );
                echo json_encode($respuesta);
            }else{
                $html = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $critico = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $medio = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $bajo = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $comun = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $respuesta = array(
                    'html' => $html,
                    'critico' => $critico,
                    'medio' => $medio,
                    'bajo' => $bajo,
                    'comun' => $comun
                );
                
                echo json_encode($respuesta);
            }
            break;
            
/*-----------------------------------------------------------------------------------------------------*/

        case "tabla-general-historial":
            $html = "";
            $critico = "";
            $medio = "";
            $bajo = "";
            $comun = "";
            
            
            $datos = $evento->get_evento();
            if (is_array($datos) == true and count($datos) > 0) {
        
                //Datos para Tabla comun variable = $html
                //Recorre los resultados que entrego la funcion get_evento_nivel(1)
                foreach ($datos as $row) {
                    //Variable temporar para recorrido y almacenamiento
                    $recorrido = "";
                    $recorrido .= "<tr class='modalInfo'>";
        
                    $recorrido .= "<td id='id_evento_celda_historial' value='" . $row['ev_id'] . "'>" . $row['ev_id'] . "</td>";
                    //Llama a la funcion get_datos_categoria para obtener el nombre de la categoria
                    $datos_categoria = $categoria->get_datos_categoria($row['cat_id']);
                    foreach ($datos_categoria as $row_categoria) {
                        $recorrido .= "<td>". $row_categoria['cat_nom']. "</td>";
                    }
        
                    $direccion = $row['ev_direc'];
                    // Dividir la cadena en texto y coordenadas
                    $parts = explode(" , ", $direccion);
                    
                    // Si hay coordenadas, eliminarlas y agregar el botón
                    if ($parts[1] !== "No hay coordenadas") {
                        // Eliminar las coordenadas
                        $texto = $parts[0];
                        // Agregar el botón después del texto de la dirección
                        $direccion = $texto;
                    }else {
                        $direccion = $parts[0];
                    }
        
                    // Si no hay coordenadas, eliminar el texto que indica su ausencia
                    $direccion = str_replace("No hay coordenadas", "", $direccion);
        
                    $recorrido .= "<td>" . $direccion . "</td>";
                    
                    $recorrido .= "<td>" . $row['ev_inicio'] . "</td>";
                    $recorrido .= "<td> <button id='btn' type='button' class='btn btn-inline btn-primary btn-sm ladda-button btnInfoEmergencia '> <i class='fa fa-search'></i></button>
                    </td>";
                    $recorrido .= "</tr>";
                    //Filtro de filas por nivel de peligro
                    if($row['ev_est'] == 1){
                        if($row['ev_niv_id'] == 1){
                            $critico .= $recorrido; 
                        }else if($row['ev_niv_id'] == 2) {
                            $medio .= $recorrido; 
                        }else if($row['ev_niv_id'] == 3) {
                            $bajo .= $recorrido; 
                        }else if($row['ev_niv_id'] == 0) {
                            $comun .= $recorrido;
                        }
                    }
                    $html .= $recorrido;
                }
                $respuesta = array(
                    'html' => $html,
                    'critico' => $critico,
                    'medio' => $medio,
                    'bajo' => $bajo,
                    'comun' => $comun
                );
                echo json_encode($respuesta);
            }else{
                $html = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $critico = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $medio = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $bajo = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $comun = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $respuesta = array(
                    'html' => $html,
                    'critico' => $critico,
                    'medio' => $medio,
                    'bajo' => $bajo,
                    'comun' => $comun
                );
                
                echo json_encode($respuesta);
            }
            break;

/*-----------------------------------------------------------------------------------------------------*/

        case "tablas-dashboard":
            $html = "";
            $criticoYmedio = "";
            $bajoYcomun = "";
            $htmlTemporal = "";
            $criticoYmedioTemporal = "";
            $bajoYcomunTemporal = "";
            
            
            $datos = $evento->get_evento();
            if (is_array($datos) == true and count($datos) > 0) {
                
                //Datos para Tabla comun variable = $html
                //Recorre los resultados que entrego la funcion get_evento_nivel(1)
                foreach ($datos as $row) {
                    //Variable temporal para recorrido y almacenamiento
                    $recorrido = "";
                    $recorrido .= "<tr>";
                    
                    $recorrido .= "<td id='id_evento_celda_historial' value='" . $row['ev_id'] . "'>" . $row['ev_id'] . "</td>";

                    //Llama a la funcion get_datos_estado para obtener el estado
                    $dato_estado = $estado->get_datos_estado($row['ev_est']);
                    foreach ($dato_estado as $row_estado) {
                        $recorrido.= "<td>". $row_estado['est_nom']. "</td>";
                    }
                    
                    //Llama a la funcion get_datos_eventounidad para obtener los nombres de las unidades asignadas
                    $datos_asignaciones = $eventounidad->get_datos_eventoUnidad($row['ev_id']);
                    // $datos_asignaciones = [[1,1],[2,2]];
                    if (is_array($datos_asignaciones) && count($datos_asignaciones) > 0) { 
                        $recorrido .= "<td>";
                        $contar = 0;
                        foreach($datos_asignaciones as $row_asignaciones){
                            $unid_id = $row_asignaciones['unid_id'];
                            $datos_unidad = $unidad->get_datos_unidad($unid_id);
                            foreach ($datos_unidad as $row_unidad ) {
                                if ($contar == 0){
                                    $recorrido .= $row_unidad['unid_nom'];
                                    $contar += 1;
                                }else{
                                    $recorrido .= " - " . $row_unidad['unid_nom'];
                                }
                            }
                        }
                        $recorrido .= "</td>";
                    }else{

                        $recorrido .= "<td>No asignada</td>";
                    }
    
    
                    //Peligro
                    if($row['ev_niv_id'] == 1){
                        $recorrido .= "<td > <span class='label label-pill label-primary peligro_critico' > Critico </span> </td>";
                    }else if($row['ev_niv_id'] == 2) {
                        $recorrido .= "<td > <span class='label label-pill label-primary peligro_medio' > Medio </span> </td>";
                    }else if($row['ev_niv_id'] == 3) {
                        $recorrido .= "<td > <span class='label label-pill label-primary peligro_bajo' > Bajo </span> </td>";
                    }else if($row['ev_niv_id'] == 0) {
                        $recorrido .= "<td > <span class='label label-pill label-primary peligro_comun' > Comun </span> </td>";
                    }
                    
                    
                    $recorrido .= "<td>" . $row['ev_inicio'] . "</td>";
                    $recorrido .= "</tr>";

                    //Filtro de filas por nivel de peligro
                    if($row['ev_est'] == 1){
                        if($row['ev_niv_id'] == 1 || $row['ev_niv_id'] == 2){
                            $criticoYmedioTemporal .= $recorrido; 
                        }else if($row['ev_niv_id'] == 3 || $row['ev_niv_id'] == 0) {
                            $bajoYcomunTemporal .= $recorrido;
                        }
                    }
                    $htmlTemporal .= $recorrido;
                }
                $criticoYmedio = "<tr>
                <th>
                    <div>N° ID</div>
                </th>
                <th>
                    <div>Estado</div>
                </th>
                <th>
                    <div>Asignado a:</div>
                </th>
                <th align='center'>
                    <div>Nivel evento</div>
                </th>
                <th align='center'>
                    <div>Fecha</div>
                </th>
            </tr>" . $criticoYmedioTemporal;
                $bajoYcomun = "<tr>
                <th>
                    <div>N° ID</div>
                </th>
                <th>
                    <div>Estado</div>
                </th>
                <th>
                    <div>Asignado a:</div>
                </th>
                <th align='center'>
                    <div>Nivel evento</div>
                </th>
                <th align='center'>
                    <div>Fecha</div>
                </th>
            </tr>" . $bajoYcomunTemporal;
                $html = "<tr>
                <th>
                    <div>N° ID</div>
                </th>
                <th>
                    <div>Estado</div>
                </th>
                <th>
                    <div>Asignado a:</div>
                </th>
                <th align='center'>
                    <div>Nivel evento</div>
                </th>
                <th align='center'>
                    <div>Fecha</div>
                </th>
                </tr>" . $htmlTemporal;
                $respuesta = array(
                    'html' => $html,
                    'criticoYmedio' => $criticoYmedio,
                    'bajoYcomun' => $bajoYcomun
                );
                echo json_encode($respuesta);
            }else{
                $html = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $criticoYmedio = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $bajoYcomun = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $respuesta = array(
                    'html' => $html,
                    'criticoYmedio' => $criticoYmedio,
                    'bajoYcomun' => $bajoYcomun
                );
                
                echo json_encode($respuesta);
            }
            break;

        case "update_nivelpeligro_evento":
            $datos = $evento->update_nivelpeligro_evento($_POST['ev_id'],
            $_POST['ev_niv']);
                if ($datos == true) {
                echo 1;
            } else {
                echo 0;
            }
            break;

        case "get_evento_id":
            $ev_id = $_POST["ev_id"];
            $datos_evento = $evento->get_evento_id($ev_id);

            if (is_array($datos_evento) == true and count($datos_evento) > 0){
                
                echo json_encode($datos_evento);

            } else {
                echo '<script> console.log(Error al obtener evento con la id: ' . $ev_id . ') </script>';
            }
            break;
            case "cerrar_evento":
                $nombre_apellido = $_POST['nombre_apellido'];
                list($nombre, $apellido) = explode(' ', $nombre_apellido, 2);
            
                // Obtener el usu_id basado en el nombre y apellido
                $usu_id = $evento->obtener_usuario_id($nombre, $apellido);
            
                if ($usu_id) {
                    $datos = $evento->cerrar_evento($_POST['ev_id'], $_POST['ev_final'], $_POST['ev_est'], $_POST['detalle_cierre'], $_POST['motivo_cierre'], $usu_id);
                    
                    if ($datos == true) {
                        echo 1;
                    } else {
                        echo 0;
                    }
                } else {
                    echo 0; // No se encontró el usuario
                }
                break;
        case "cantidad-eventos":
            
            $eventos_abiertos = 0;
            $eventos_cerrados = 0;
            $eventos_controlados = 0;
            $eventos_ext = 0;
            $cantidad_total = 0;
            $porcentaje_abiertas = 0.0;
            $porcentaje_cerradas = 0.0;
            $datos_var= [];
            
            //Obtener la fecha actual
            $fecha_actual = date('Y-m-d', strtotime('+1 day'));

            // Restar un mes a la fecha actual
            $fecha_mes_anterior = date('Y-m-d', strtotime('-1 month -1 day',strtotime($fecha_actual)));
            
            // Obtener datos de eventos por día
            $datos = $evento->get_eventos_por_rango_sin_cantidad($fecha_actual , $fecha_mes_anterior);

            // $datos = $evento->get_evento();

            if (is_array($datos) && count($datos) > 0){
                foreach ($datos as $row) {
                    if($row['ev_est'] === 1){
                        $eventos_abiertos ++;
                    }else if ($row['ev_est'] === 2){
                        $eventos_cerrados ++;
                    }else if ($row['ev_est'] === 3) {
                        $eventos_controlados ++;
                    }else {
                        $eventos_ext ++;
                    }
                    $datos_var = $datos;
                }
            }
            $cantidad_total += $eventos_abiertos + $eventos_cerrados;
            if($cantidad_total != 0){
                if($eventos_abiertos != 0){
                    $porcentaje_abiertas +=  round(($eventos_abiertos / $cantidad_total )* 100);
                }
                if($eventos_cerrados != 0){
                    $porcentaje_cerradas += round(($eventos_cerrados / $cantidad_total )*100);
                }
            }else{
                $porcentaje_unidades_disponibles = 0;
                $porcentaje_unidades_no_disponibles = 0;
            }
            
            $respuesta = array(
                'eventos_abiertos' => $eventos_abiertos,
                'eventos_cerrados' => $eventos_cerrados,
                'eventos_controlados' => $eventos_controlados,
                'eventos_ext' => $eventos_ext,
                'porcentaje_abiertas' => $porcentaje_abiertas,
                'porcentaje_cerradas' => $porcentaje_cerradas,
                'datos_var' => $datos_var
            );
            echo json_encode($respuesta);
            break;          


        case "evento-grafico-dashboard":

            //Obtener la fecha actual
            $fecha_actual = date('Y-m-d', strtotime('+1 day'));

            // Restar un mes a la fecha actual
            $fecha_mes_anterior = date('Y-m-d', strtotime('-1 month -1 day',strtotime($fecha_actual)));
            
            // echo $fecha_actual . " " . $fecha_mes_anterior;
            // Obtener datos de eventos por día
            $datos = $evento->get_eventos_por_rango($fecha_actual , $fecha_mes_anterior);
            
            $data = [];
            foreach ($datos as $row) {
                $dia = date('d-m', strtotime($row["fecha"]));
                $cantidad = (int) $row["cantidad"];
                $data[] = [$dia, $cantidad];
                
            }

            echo json_encode($data);
        break;

        case "cantidad-segun-nivel-peligro":
            // Variables
            $totalCriticosMedios = 0;
            $totalBajasComunes = 0;

            // Obtener la fecha actual
            $fecha_actual = date('Y-m-d', strtotime('+1 day'));

            // Restar un mes a la fecha actual
            $fecha_mes_anterior = date('Y-m-d', strtotime('-1 month -1 day', strtotime($fecha_actual)));

            // Obtener la cantidad de eventos críticos y medios
            $datosCriticos = $evento->get_cantidad_eventos_por_nivel([1, 2], $fecha_actual, $fecha_mes_anterior);
            $totalCriticosMedios = $datosCriticos['total'];

            // Obtener la cantidad de eventos bajas y comunes
            $datosBajasComunes = $evento->get_cantidad_eventos_por_nivel([3, 0], $fecha_actual, $fecha_mes_anterior);
            $totalBajasComunes = $datosBajasComunes['total'];

            // Crear un array con los resultados
            $resultado = array(
                'totalCriticosMedios' => $totalCriticosMedios,
                'totalBajasComunes' => $totalBajasComunes
            );

            // Devolver los resultados como JSON
            echo json_encode($resultado);
        break;
            
            // -----------------------------------------------------------------------------------------


        case "get_eventos":

            $where = $_POST['where'];

            $html = "";
            $critico = "";
            $medio = "";
            $bajo = "";
            $comun = "";
            $array2 = [];
            
            // $datos = $evento->get_evento_where($where);
            $datos = $evento->get_evento();
            if (is_array($datos) == true and count($datos) > 0) {
                
                //Datos para Tabla comun variable = $html
                //Recorre los resultados que entrego la funcion get_evento_nivel(1)
                foreach ($datos as $row) {
                    $array = [];
                    //Variable temporar para recorrido y almacenamiento
                    $recorrido = "";
                    $recorrido .= "<tr class='modalInfo'>";
                    
                    $recorrido .= "<td id='id_evento_celda_historial' value='" . $row['ev_id'] . "'>" . $row['ev_id'] . "</td>";
                    $array[] = $row['ev_id'];

                    //Llama a la funcion get_datos_categoria para obtener el nombre de la categoria
                    $datos_categoria = $categoria->get_datos_categoria($row['cat_id']);
                    foreach ($datos_categoria as $row_categoria) {
                        $recorrido .= "<td>". $row_categoria['cat_nom']. "</td>";
                        $array[] = $row['cat_nom'];
                    }
        
                    $direccion = $row['ev_direc'];
                    // Dividir la cadena en texto y coordenadas
                    $parts = explode(" , ", $direccion);
                    
                    // Si hay coordenadas, eliminarlas y agregar el botón
                    if ($parts[1] !== "No hay coordenadas") {
                        // Eliminar las coordenadas
                        $texto = $parts[0];
                        // Agregar el botón después del texto de la dirección
                        $direccion = $texto;
                    }else {
                        $direccion = $parts[0];
                    }
        
                    // Si no hay coordenadas, eliminar el texto que indica su ausencia
                    $direccion = str_replace("No hay coordenadas", "", $direccion);
        
                    $recorrido .= "<td>" . $direccion . "</td>";
                    $array[] = $direccion;
                    
                    $recorrido .= "<td>" . $row['ev_inicio'] . "</td>";
                    $array[] = $row['ev_inicio'];
                    $recorrido .= "<td> <button id='btn' type='button' class='btn btn-inline btn-primary btn-sm ladda-button btnInfoEmergencia '> <i class='fa fa-search'></i></button>
                    </td>";
                    $recorrido .= "</tr>";
                    //Filtro de filas por nivel de peligro
                    if($row['ev_est'] == 1){
                        if($row['ev_niv_id'] == 1){
                            $critico .= $recorrido; 
                        }else if($row['ev_niv_id'] == 2) {
                            $medio .= $recorrido; 
                        }else if($row['ev_niv_id'] == 3) {
                            $bajo .= $recorrido; 
                        }else if($row['ev_niv_id'] == 0) {
                            $comun .= $recorrido;
                        }
                    }
                    $array2[] = $array;
                    $html .= $recorrido;
                }
                $respuesta = array(
                    'html' => $html,
                    'critico' => $critico,
                    'medio' => $medio,
                    'bajo' => $bajo,
                    'comun' => $comun,
                    'array2' => $array2
                );
                echo json_encode($respuesta);
            }else{
                $html = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $critico = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $medio = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $bajo = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $comun = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $array2 = "<tr><td colspan=5>No se encontraron registros</td></tr>";
                $respuesta = array(
                    'html' => $html,
                    'critico' => $critico,
                    'medio' => $medio,
                    'bajo' => $bajo,
                    'comun' => $comun,
                    'array2' => $array2
                );
                
                echo json_encode($respuesta);
            }
            break;


            // -----------------------------------------------------------------------------------------
        case "get_id_ultimo_evento":
            $datos = $evento->get_id_ultimo_evento();
            echo $datos;
            break;
        case "get_datos_categoria_eventos_ultimos_30_dias":
            $fecha_inicio = date('Y-m-d', strtotime('-30 days')); // Fecha de inicio hace 30 días
            $datos = $evento->datos_categorias_eventos($fecha_inicio);
            echo json_encode($datos);
            break;

    }


}        
}
