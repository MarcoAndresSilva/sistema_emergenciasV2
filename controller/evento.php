<?php
require_once("../config/conexion.php");
require_once("../models/Evento.php");
require_once("../models/Categoria.php");
require_once("../models/Unidad.php");
require_once("../models/Estado.php");
require_once("../models/EventoUnidad.php");
require_once("../models/NivelPeligro.php");
require_once("../models/Correo.php");
require_once("../models/Noticia.php");
require_once("../models/Seccion.php");
require_once("../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();
require_once("../models/Usuario.php");


$usuario = new Usuario();
$seccion = new Seccion();
$evento = new Evento();
$categoria = new Categoria();
$unidad = new Unidad();
$estado = new Estado();
$eventounidad = new EventoUnidad();
$NivelPeligro = new NivelPeligro();
$noticia = new Noticia();

function guardarImagen($archivo, $carpeta) {
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
        return "Error al recibir la imagen: " . $archivo['error'];
    }

    $directorio_destino = __DIR__ . "/../public/img/{$carpeta}/";

    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombre_archivo = uniqid('', true) . '.' . $extension;
    $ruta_destino = $directorio_destino . $nombre_archivo;

    if (!is_dir($directorio_destino)) {
        if (!mkdir($directorio_destino, 0775, true)) {
            return "No se pudo crear el directorio: " . $directorio_destino;
        }
    }

    if (!is_writable($directorio_destino)) {
        return "El directorio no tiene permisos de escritura: " . $directorio_destino;
    }

    if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        return "Error al mover el archivo a: " . $ruta_destino;
    }

    $ruta_relativa = "/img/{$carpeta}/" . $nombre_archivo;
    return $ruta_relativa;
}

if (isset($_GET["op"])) {
    switch ($_GET["op"]) {

        case "add_evento":
            $usu_id = $_SESSION["usu_id"]; 
            $ev_desc = $_POST['ev_desc'];
            $ev_est = $_POST['ev_est'];
            $ev_inicio = $_POST['ev_inicio'];
            $ev_direc = $_POST['ev_direc'];
            $ev_latitud = $_POST['ev_latitud'];
            $ev_longitud = $_POST['ev_longitud'];
            $cat_id = $_POST['cat_id'];
            $cate = new Categoria();
            $cate_info = $cate->get_datos_categoria($cat_id);
            $ev_niv = $cate_info[0]['nivel'];
        
            $ev_img = null;

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $carpeta = 'imagenesEventos';
                $url_imagen = guardarImagen($_FILES['imagen'], $carpeta);
        
                if ($url_imagen === "Error al recibir la imagen." || $url_imagen === "Error al mover el archivo.") {
                    echo $url_imagen;
                    break;
                  }
                $ev_img = $url_imagen;
        
            }
        
            $datos = $evento->add_evento(
                $usu_id,
                $ev_desc,
                $ev_est,
                $ev_inicio,
                $ev_direc,
                $ev_latitud,
                $ev_longitud,
                $cat_id,
                $ev_niv,
                $ev_img
            );
            header('Content-Type: application/json');
            $last_evento = $noticia->obtenerUltimoRegistro("tm_evento","ev_id");
            $id_ultimo_evento = $last_evento["ev_id"];
            $args = [
              "asunto" => "Nuevo Evento",
              "mensaje" => "Evento sin derivar",
              "url" => "../ControlEventos/index.php?id_evento=$id_ultimo_evento",
            ];
            $noticia->crear_noticia_y_enviar_grupo_usuario($args);

            echo json_encode($datos);
        break;
        case "get_documentos":
            $datos = $evento->get_documentos($_POST['evento_id']);
            echo json_encode($datos);
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

       case "tabla-control":

      $eventos = [];
    
      $datos = $evento->get_evento();
      if (is_array($datos) && count($datos) > 0) {
        foreach ($datos as $row) {
          $evento = [];
            $evento['ev_id'] = $row['ev_id'];
              // Obtener la categoría del evento
              $datos_categoria = $categoria->get_datos_categoria($row['cat_id']);
              $evento['categoria'] = isset($datos_categoria[0]['cat_nom']) ? $datos_categoria[0]['cat_nom'] : 'Sin Categoría';

                  // Dirección con botón de mapa
                  $direccion = $row['ev_direc'] . " <button class='btn btn-inline btn-primary btn-sm btnDireccionarMapa'><i class='fa-solid fa-location-dot'></i></button>";
                  $evento['direccion'] = str_replace(["No hay coordenadas", "Sin dirección"], "", $direccion);
        
                  // Obtener las asignaciones
                  $datos_asignaciones = $eventounidad->get_datos_eventoUnidad($row['ev_id']);
                  $asignacion = [];
                  if (!empty($datos_asignaciones['data']) && is_array($datos_asignaciones['data'])) {
                      foreach ($datos_asignaciones['data'] as $row_asignaciones) {
                          $unid_id = $row_asignaciones['sec_id'];
                          $datos_unidad = $unidad->get_seccion_unidad($unid_id);
                          if (!empty($datos_unidad) && is_array($datos_unidad)) {
                              foreach ($datos_unidad as $row_unidad) {
                                  $asignacion[] = $row_unidad['unid_nom'];
                              }
                          }
                      }
                   $asignacion = array_unique($asignacion);
                      $assignacion = !empty($asignacion) ? implode(' - ', $asignacion) : 'No asignada';
                      $evento['asignacion'] = '<span class="label label-pill label-primary">' . htmlspecialchars($assignacion) . '</span>';
                  } else {
                      // Si no hay asignaciones, mostrar mensaje estilizado con ícono de advertencia
                      $evento['asignacion'] = '<span class="label label-warning"><i class="fa-solid fa-minus-circle"></i> No asignada</span>';
                  }
                  // Nivel de peligro con estilos e íconos
                  if ($row['ev_niv_id'] == 1) {
                      $evento['nivel_peligro'] = '<span class="label label-pill label-danger"><i class="fa-solid fa-exclamation-triangle"></i> Crítico</span>';
                  } elseif ($row['ev_niv_id'] == 2) {
                      $evento['nivel_peligro'] = '<span class="label label-pill label-warning"><i class="fa-solid fa-exclamation-circle"></i> Medio</span>';
                  } elseif ($row['ev_niv_id'] == 3) {
                      $evento['nivel_peligro'] = '<span class="label label-pill label-success"><i class="fa-solid fa-info-circle"></i> Bajo</span>';
                  } else {
                      $evento['nivel_peligro'] = '<span class="label label-pill label-default"><i class="fa-solid fa-circle"></i> Común</span>';
                  }
        
                  // Estado con estilos e íconos
                  $dato_estado = $estado->get_datos_estado($row['ev_est']);
                  if (isset($dato_estado[0]['est_nom'])) {
                      if ($dato_estado[0]['est_nom'] == "En Proceso") {
                          $evento['estado'] = '<span class="label label-pill label-success"><i class="fa-solid fa-hourglass-half"></i> ' . $dato_estado[0]['est_nom'] . '</span>';
                      } elseif ($dato_estado[0]['est_nom'] == "Finalizado") {
                          $evento['estado'] = '<span class="label label-pill label-danger"><i class="fa-solid fa-check-circle"></i> ' . $dato_estado[0]['est_nom'] . '</span>';
                      } else {
                          $evento['estado'] = '<span class="label label-pill label-secondary"><i class="fa-solid fa-question-circle"></i> ' . $dato_estado[0]['est_nom'] . '</span>';
                      }
                  } else {
                      $evento['estado'] = 'Desconocido';
                  }
                  // Fecha de apertura
                  $evento['fecha_apertura'] = $row['ev_inicio'];
                  // Botones para derivar y ver detalles
                  $evento['ver_niv_peligro'] = "<button id='btnPanelPeligro' data-ev-id='" . $row['ev_id'] . "'><i class='fa-solid fa-exclamation-triangle'></i></button>";
                  $evento['ver_derivar'] = "<button id='btnPanelDerivar' data-ev-id='" . $row['ev_id'] . "'><i class='fa-solid fa-up-right-from-square'></i></button>";
                  $evento['ver_detalle'] = "<a id='btnDetalleEmergencia' href='../EmergenciaDetalle/?ID=".$row['ev_id']."'><i class='fa-regular fa-comments'></i></a>";
      
                  $eventos[] = $evento;
                }
                echo json_encode($eventos);
            } else {
                echo json_encode([]);
            }
          break;
          case "tabla-historial-eventos": 
            $eventos = [];
            $datos = $evento->get_evento();
            if (is_array($datos) && count($datos) > 0) {
                foreach ($datos as $row) {
                    $evento = [];
                    $evento['ev_id'] = $row['ev_id'];
                    // Obtener la categoría del evento
                    $datos_categoria = $categoria->get_datos_categoria($row['cat_id']);
                    $evento['categoria'] = isset($datos_categoria[0]['cat_nom']) ? $datos_categoria[0]['cat_nom'] : 'Sin Categoría';
                    // Dirección con botón de mapa
                    $direccion = $row['ev_direc'] . " <button class='btn btn-inline btn-primary btn-sm btnDireccionarMapa'><i class='fa-solid fa-location-dot'></i></button>";
                    $evento['direccion'] = str_replace(["No hay coordenadas", "Sin dirección"], "", $direccion);
          
              // Obtener las asignaciones
              $datos_asignaciones = $eventounidad->get_datos_eventoUnidad($row['ev_id']);
              $asignacion = [];
              if (is_array($datos_asignaciones) && count($datos_asignaciones) > 0) {
                  foreach ($datos_asignaciones["data"] as $row_asignaciones) {
                      $unid_id = $row_asignaciones['sec_id'];
                      $datos_unidad = $unidad->get_seccion_unidad($unid_id);
                      foreach ($datos_unidad as $row_unidad) {
                          $asignacion[] = $row_unidad['unid_nom'];
                      }
                  }
                  $asignacion = array_unique($asignacion);
                  // Si hay asignaciones, mostrar las unidades asignadas
                  $evento['asignacion'] = '<span class="label label-pill label-primary">' . implode(' - ', $asignacion) . '</span>';
              } else {
                  // Si no hay asignaciones, mostrar mensaje estilizado con ícono de advertencia
                  $evento['asignacion'] = '<span class="label label-warning"><i class="fa-solid fa-minus-circle"></i> No asignada</span>';
              }

              // Nivel de peligro con estilos e íconos
              if ($row['ev_niv_id'] == 1) {
                  $evento['nivel_peligro'] = '<span class="label label-pill label-danger"><i class="fa-solid fa-exclamation-triangle"></i> Crítico</span>';
              } elseif ($row['ev_niv_id'] == 2) {
                  $evento['nivel_peligro'] = '<span class="label label-pill label-warning"><i class="fa-solid fa-exclamation-circle"></i> Medio</span>';
              } elseif ($row['ev_niv_id'] == 3) {
                  $evento['nivel_peligro'] = '<span class="label label-pill label-success"><i class="fa-solid fa-info-circle"></i> Bajo</span>';
              } else {
                  $evento['nivel_peligro'] = '<span class="label label-pill label-default"><i class="fa-solid fa-circle"></i> Común</span>';
              }
              // Estado con estilos e íconos
              $dato_estado = $estado->get_datos_estado($row['ev_est']);
              if (isset($dato_estado[0]['est_nom'])) {
                  if ($dato_estado[0]['est_nom'] == "En Proceso") {
                      $evento['estado'] = '<span class="label label-pill label-success"><i class="fa-solid fa-hourglass-half"></i> ' . $dato_estado[0]['est_nom'] . '</span>';
                  } elseif ($dato_estado[0]['est_nom'] == "Finalizado") {
                      $evento['estado'] = '<span class="label label-pill label-danger"><i class="fa-solid fa-check-circle"></i> ' . $dato_estado[0]['est_nom'] . '</span>';
                  } else {
                      $evento['estado'] = '<span class="label label-pill label-secondary"><i class="fa-solid fa-question-circle"></i> ' . $dato_estado[0]['est_nom'] . '</span>';
                  }
              } else {
                  $evento['estado'] = 'Desconocido';
              }
                    // Fecha de apertura
                    $evento['fecha_apertura'] = $row['ev_inicio'];
          
                    // Botón para ver documentos
                    $evento['ver_documentos'] = "<button class='btnDocumentos' data-ev-id='" . $row['ev_id'] . "'><i class='fa-regular fa-folder-open'></i></button>";
                    $evento['ver_informe'] = "<a class='btn' href='../GenerarPdf/?id_evento=" . $row['ev_id'] . "'><i class='fa fa-file'> </a>";
                    $eventos[] = $evento;
                }
                
                echo json_encode($eventos);
            } else {
                echo json_encode([]);
            }
          break;
        
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
            $adjunto = null;
            if (isset($_POST["adjunto"])) {
                $imagen = $_FILES["adjunto"];
                $carpeta = "imagenesCierres";
                // Guardar la imagen usando la función guardarImagen
                $adjunto = guardarImagen($imagen, $carpeta);
            }
            
            
            $usu_id = $_SESSION["usu_id"];
            
            if ($usu_id) {
                // Llamar al método cerrar_evento con los parámetros correspondientes
                $datos = $evento->cerrar_evento(
                    $_POST['ev_id'],
                    $_POST['ev_final'],
                    $_POST['ev_est'],
                    $_POST['detalle_cierre'],
                    $_POST['motivo_cierre'],
                    $usu_id,
                    $adjunto
                );
               $args_noticia = ["asunto"=>"Evento Cerrado","mensaje"=>$_POST["detalle_cierre"],"id_evento"=>$_POST["ev_id"],"url"=>"#"];
                // Verificar si cerrar_evento devuelve true
                if ($datos === true) {
                    $seccion->update_disponible_todos_de_evento_cerrado($_POST['ev_id']);
                    $noticia->crear_noticia_y_enviar_grupo_usuario($args_noticia);
                    echo 1;
                } else {
                    // Imprimir el error recibido para diagnóstico
                    echo "Error al cerrar el evento. Detalles: ";
                    var_dump($datos); // Asegúrate de revisar qué está devolviendo esta función
                }
            } else {
                echo "Error: Usuario no encontrado"; // Mensaje de error detallado si no se encuentra el usuario
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

        case "get_id_ultimo_evento":
            $datos = $evento->get_id_ultimo_evento();
            echo $datos;
        break;

        case "get_datos_categoria_eventos_ultimos_30_dias":
            $fecha_inicio = date('Y-m-d', strtotime('-30 days')); // Fecha de inicio hace 30 días
            $datos = $evento->datos_categorias_eventos($fecha_inicio);
            echo json_encode($datos);
        break;

        case "get_evento_lat_lon":
          $startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : null;
          $endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : null;
          $datos = $evento->get_eventos_categoria_latitud_longitud($startDate, $endDate);
          header('Content-Type: application/json');
          echo json_encode($datos);
        break;
        
        case "get_filters_evento_map":
        $datosUnidad= $unidad->get_unidad();
        $datosNivel = $NivelPeligro->get_nivel_peligro();

        $unidades = array_map(function($item) {
            return $item['unid_nom'];
        }, $datosUnidad);

        $niveles = array_map(function($item) {
            return $item['ev_niv_nom'];
        }, $datosNivel);

        echo json_encode([
            'unidades' => $unidades,
            'niveles' => $niveles
        ]);
        break;

        case 'informacion_evento_completo':
            header('Content-Type: application/json');
              $id_evento = isset($_POST['id_evento']) ? $_POST['id_evento'] : null;
              if (empty($id_evento)){
                $respuesta = [
                  "status"=>"error",
                  "message"=>"id_evento no encontrados",
                ];
                echo json_encode($respuesta);
                break;
              }
            $datosEvento = $evento->get_evento_id($id_evento);
            $secciones_asignadas = $seccion->get_secciones_evento($id_evento);
            $usuario_creador = $usuario->get_info_usuario($datosEvento['usu_id']);
            $id_secciones_asignadas = [];
            if (is_array($secciones_asignadas) == true and count($secciones_asignadas) > 0){
              foreach ($secciones_asignadas as $seccion){
                $id_secciones_asignadas[] = $seccion['id'];
              }
            }
            if (is_array($datosEvento) == true and count($datosEvento) > 0){
                $respuesta = [
                  "status"=>"success",
                  "message"=>"Se obtienen los datos del evento $id_evento",
                  "evento"=>$datosEvento,
                  "creador"=>$usuario_creador['result'],
                  "secciones_asignadas"=>["secciones"=>$secciones_asignadas,"id_secciones_asignadas"=>$id_secciones_asignadas],
                ];
            }else{
              $respuesta = [
                "status"=>"error",
                "message"=>"No se obtienen los datos del evento $id_evento",
              ];
            }
            echo json_encode($respuesta);
        break;

    }


}
