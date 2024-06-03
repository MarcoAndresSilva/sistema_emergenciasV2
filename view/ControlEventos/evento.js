$(document).ready(function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.ControlEventos');

    // Añadir una clase al enlace
    enlace.classList.add('selected');

    //Funcion para cargar los datos de las tablas
    $.post("../../controller/evento.php?op=tabla-general",function(respuesta,status){

        // Parsear la respuesta JSON
        var data = JSON.parse(respuesta);
        
        $('#datos-criticos').html(data.critico);
        $('#datos-medios').html(data.medio);
        $('#datos-bajos').html(data.bajo);
        $('#datos-generales').html(data.comun);
    });
});



//Variable Iid_evento
$id_evento = 0;

//Activar el boton de asignación
$(document).on('click', '.btnMostrarDatos', function() {
    
    //Llamar a la funcion para mostrar u ocultar la pestaña de Unidades Disponibles
    togglePestana();
    
    // Obtener el valor del ID del evento desde la celda
    $id_evento = $(this).closest('tr').find('#id_evento_celda').attr('value');

    // Llama la funcion para consultar la categoria
    consultarCategoria($id_evento);

    // Llama la funcion para consultar la categoria
    mostrarCatIdEvento($id_evento);
    
    //Llamar a la función para consultar las unidades disponibles
    consultarUnidadDisponible($id_evento);
    
    //Llamar a la función para consultar el nivel de peligro
    consultarNivelPeligro($id_evento);
});

// Función para mostrar u ocultar la pestaña Derivar
function togglePestana() { 
    $('#selector-unidad').toggle();
}

// Cancelar actualización
$('.btnCancelar').off('click').on('click',function(){
    // Llamar a la función para mostrar u ocultar la pestaña
    togglePestana();
});
  

// Función para obtener el cat_id del evento y mostrarlo en el div cat_id
function mostrarCatIdEvento(ev_id) {
    $('#ev_id').text(ev_id);
}

// Función para mostrar el cat_nom en el div
function consultarCategoria(ev_id) {
    $.post("../../controller/categoria.php?op=get_cat_nom_by_ev_id", { ev_id: ev_id }, function(data, status) {
        try {
            var jsonData = JSON.parse(data);
            if (jsonData && jsonData.cat_nom) {
                $('#cat_nom').text(jsonData.cat_nom); // Usar .text() para establecer el contenido del div
            } else {
                console.log("No se encontró el cat_nom correspondiente para el evento con ID: " + ev_id);
            }
        } catch (error) {
            console.log("Error al analizar la respuesta JSON:", error);
        }
    });
}

//Funcion para consultar las unidades disponibles
function consultarUnidadDisponible($id_evento) {
    var unid_est = 1;
    var id_evento = $id_evento; // Obtén el ID del evento
    var est_id = 1;
    // Limpiar el contenido actual del div
    $('#unidadOptions').empty();
    $.post("../../controller/unidad.php?unidad=listar", {est_id: est_id },function(data,status){


        // Agregar opciones al div
        for (var i = 0; i < data.length; i++) {

            var option = '<div class="form-check">' +
                '<input class="form-check-input" type="checkbox" name="unidad" id="unidad_' + data[i].unid_id + '" value="' + data[i].unid_id + '">' +
                '<label class="form-check-label" for="unidad_' + data[i].unid_id + '">' + data[i].unid_nom + '</label>' +
                '</div>';

            $('#unidadOptions').append(option);
        }
        //Obtener unidades asignadas al evento
        $.post("../../controller/eventoUnidad.php?op=get_datos_eventoUnidad", {ev_id: id_evento }, function(asignadas,status){
            // Verificar si asignadas es un array antes de usar forEach
            if (Array.isArray(asignadas)) {
                asignadas.forEach(unidad => {
                    var unidadID = unidad['unid_id'];
                    $('#unidad_' + unidadID).prop('checked', true);
                });
            } else {
                console.log("La respuesta recibida no es un array: ", asignadas);
            }
        }, 'json');
        
    }, 'json');
}

function consultarNivelPeligro($id_evento) {
    var id_evento = $id_evento; // Obtén el ID del evento
    
    //Realiza la recopilación y añade las unidades al select
    $.post("../../controller/nivelPeligro.php?op=get_nivel_peligro",function(data,status){
        // Limpiar el contenido actual del select
        $('#niv_id').empty();
        
        $('#niv_id').html(data);
    });
    
    // Obtener unidades asignadas al evento
    $.post("../../controller/evento.php?op=get_evento_id", { id_evento: id_evento }, function(asignadas, status) {
        
        // Marcar como seleccionado el nivel de peligro del evento
        if (typeof asignadas[0]['ev_niv'] !== 'undefined') {
            
            //Obtener el id del nivel de peligro que esta en el evento
            var nivelPeligroEvento = asignadas[0]['ev_niv'];
            
            // Seleccionar el nivel de peligro en el select
            $('#niv_id').val(nivelPeligroEvento);

        }
        
        
        
    }, 'json');
    

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Funcion para Modificar las unidades y asignaciones a un evento
//Activar el boton de asignación
$('.btnNivelPeligro').off('click').on('click',function(){
    
    //Llama a la funcion para modificar las unidades y asignaciones a un evento
    ActualizarNivelPeligro($id_evento);
    
    //Llamar a la funcion para ocultar la pestaña de Unidades Disponibles
    togglePestana();
});

function ActualizarNivelPeligro($id_evento) {
    var ev_id = $id_evento;
    
    // Esto devuelve Id del Nivel de Peligro seleccionado
    var ev_niv = $('#niv_id').val();

    $.post("../../controller/evento.php?op=update_nivelpeligro_evento", { ev_id: ev_id, ev_niv: ev_niv }, function(data, status) {
        if (data == 1) {
            $('#id_evento').val('');
            $('#niv_id').val(0);
            $('#unidadOptions input:checked').prop('checked',false);

            swal("Actualizado","Nivel de Peligro del evento actualizado correctamente","success");
        } else {
            swal("Error: Nivel de peligro del evento no actualizado","Asegurece de realizar algún cambio","error");
        }
    });
    
}


    //Funcion para Modificar las unidades y asignaciones a un evento
    //Activar el boton de asignación
    $('.btnUnidadAsignada').off('click').on('click',function(){
    
    //Llama a la funcion para modificar las unidades y asignaciones a un evento
    ActualizarUnidadAsignada($id_evento);
    
    //Llamar a la funcion para ocultar la pestaña de Unidades Disponibles
    togglePestana();
    
    });

function ActualizarUnidadAsignada($id_evento) {
    var ev_id = $id_evento;
    var unid_ids = []; 
    var unid_antiguas = [];
    var str_antiguo = "";
    var str_nuevo = "";
    var error = 0;
    
    //Actualizar Asignacion de unidades
    // Esto devuelve un array con las IDs seleccionadas (Nuevos)
    $('#unidadOptions input:checked').each(function ( ) {
        unid_ids.push($(this).val());
    });

    //Generación de Array de unidades ya asignadas al evento (antiguo)
    $.post("../../controller/eventoUnidad.php?op=get_datos_eventoUnidad", {ev_id :ev_id}, function(data,status){
        if (data.error){
            console.log("No hay unidades antiguas");
            str_antiguo = "No hay unidades asignadas";
        }else {
            console.log(data);
            console.log(typeof data);
            // datos = JSON.parse(data);
                
            unid_antiguas = data.map(function(item) {
            return item.unid_id;
            });
            
            str_antiguo = unid_antiguas.join(',');
            
            //recorrer array antiguo y asignando cada valor a     antigua_unid_id
            unid_antiguas.forEach(unid_id => {
                
                //Borrar cada valor en la tabla relacionada al ev_id
                $.post("../../controller/eventoUnidad.php?op=delete_unidad", {ev_id :ev_id, unid_id :unid_id}, function(data,status){
                    console.log("Array delete unidades asignadas");
                    console.log(data);
                    if (data == 0) {
                        error ++;
                    }
                });
                
            });
        }
        
        str_nuevo = unid_ids.join(',');
        if(str_nuevo == ""){
            str_nuevo = "No hay unidades";
        }
        //Fecha y Hora
        var ev_final = new Date();
        var año = ev_final.getFullYear();
        var mes = ev_final.getMonth() + 1; // Mes en JavaScript es 0-indexado, así que suma 1
        var dia = ev_final.getDate();
        var horas = ev_final.getHours();
        var minutos = ev_final.getMinutes();
        var segundos = ev_final.getSeconds();
        // Formatear la fecha y hora como desees
        var fechaFormateada = año + '-' + mes + '-' + dia + ' ' + horas + ':' + minutos + ':' + segundos;
        var fec_cambio = fechaFormateada;
        
        $.post("../../controller/eventoUnidad.php?op=reporte_actualizacion", {ev_id :ev_id, str_antiguo :str_antiguo, str_nuevo :str_nuevo,fec_cambio :fec_cambio}, function(data,status){
            if(data == 0){
                console.log("Error en insert reporte_actualizacion");
                error ++;
            }
        });
        
        //recorrer array nuevo y asignando cada valor a     unid_id
        unid_ids.forEach(unid_id => {
            $.post("../../controller/eventoUnidad.php?op=insert_asignacion_unidades", { ev_id: ev_id, unid_id: unid_id }, function(data,status){
                if (data == 0) {
                    error ++;
                }
            });
            
        });

    },'json' );
    
    

    if(error > 0){
        swal("Error: Unidades no actualizadas","A ocurrido un error en el procesos de actualización","error");
    }else {
        $('#id_evento').val('');
        $('#unidadOptions input:checked').prop('checked',false);
        $('#niv_id').val(0);
        swal("Derivación exitosa","Actualizacion de las unidades asignadas exitosa","success");
    }
    
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Funcion para Modificar las unidades y nivel de peligro de un evento
//Activar el boton para modificar todo
$('.btnActualizarTodos').off('click').on('click',function(){
    //Llama a la funcion para modificar las unidades y el nivel de peligro de un evento
    ActualizarTodo($id_evento);
    
    //Llamar a la funcion para ocultar la pestaña de Unidades Disponibles
    togglePestana();

    
    
});

function ActualizarTodo($id_evento){
    //Variables
    var errorActualizar = 0;
    var unid_ids = []; 
    var unid_antiguas = [];
    var str_antiguo = "";
    var str_nuevo = "";
    var error = 0;
    
    // Actualiza la unidad asignadas primero
    var ev_id = $id_evento;
    
    // Esto devuelve un array con las IDs seleccionadas
    var unid_ids = []; 
    $('#unidadOptions input:checked').each(function ( ) {
        unid_ids.push($(this).val());
        });
        //Generación de Array de unidades ya asignadas al evento (antiguo)
        $.post("../../controller/eventoUnidad.php?op=get_datos_eventoUnidad", {ev_id :ev_id}, function(data,status){
            if (data.error){
                console.log("No hay unidades antiguas");
                str_antiguo = "No hay unidades asignadas";
            }else {
                // datos = JSON.parse(data); 
                unid_antiguas = data.map(function(item) {
                return item.unid_id;
                });
                
                str_antiguo = unid_antiguas.join(',');
                
                //recorrer array antiguo y asignando cada valor a     antigua_unid_id
                unid_antiguas.forEach(unid_id => {
                    
                    //Borrar cada valor en la tabla relacionada al ev_id
                    $.post("../../controller/eventoUnidad.php?op=delete_unidad", {ev_id :ev_id, unid_id :unid_id}, function(data,status){
                        console.log("Array delete unidades asignadas");
                        console.log(data);
                        if (data == 0) {
                            error ++;
                        }
                    });
                    
                });
            }
            
            str_nuevo = unid_ids.join(',');
            if(str_nuevo == ""){
                str_nuevo = "No hay unidades";
            }
            //Fecha y Hora
            var ev_final = new Date();
            var año = ev_final.getFullYear();
            var mes = ev_final.getMonth() + 1; // Mes en JavaScript es 0-indexado, así que suma 1
            var dia = ev_final.getDate();
            var horas = ev_final.getHours();
            var minutos = ev_final.getMinutes();
            var segundos = ev_final.getSeconds();
            // Formatear la fecha y hora como desees
            var fechaFormateada = año + '-' + mes + '-' + dia + ' ' + horas + ':' + minutos + ':' + segundos;
            var fec_cambio = fechaFormateada;
            
            $.post("../../controller/eventoUnidad.php?op=reporte_actualizacion", {ev_id :ev_id, str_antiguo :str_antiguo, str_nuevo :str_nuevo,fec_cambio :fec_cambio}, function(data,status){
                if(data == 0){
                    console.log("Error en insert reporte_actualizacion");
                    error ++;
                }
            });
            
            //recorrer array nuevo y asignando cada valor a     unid_id
            unid_ids.forEach(unid_id => {
                $.post("../../controller/eventoUnidad.php?op=insert_asignacion_unidades", { ev_id: ev_id, unid_id: unid_id }, function(data,status){
                    if (data == 0) {
                        error ++;
                    }
                });
                
            });

        },'json' );
        if(error > 0){
            console.log("Error: Unidades no actualizadas");
            errorActualizar += 1;
        }
        
        // Esto devuelve Id del Nivel de Peligro seleccionado
        var ev_niv = $('#niv_id').val();
        
        //Actualizar NivelPeligro
        //Respuesta de la Consulta del nivel de peligro del evento
        var RespuestaNivelPeligro = $.post("../../controller/evento.php?op=update_nivelpeligro_evento", { ev_id: ev_id, ev_niv: ev_niv });
        
        //Validacion de respuestas y alertas
        $.when(RespuestaNivelPeligro).done(function ( data2) {
            
            if (data2[0] == 1) {
                console.log("Actualizacion del nivel de peligro exitosa");
            } else {
                console.log("Nivel de peligro no realizada");
                errorActualizar += 1;
            }

            if(errorActualizar === 0){
                $('#id_evento').val('');
                $('#niv_id').val(0);
                $('#unidadOptions input:checked').prop('checked',false);
                swal("Actualizado ","Evento actualizado correctamente","success");
            }else{
                swal("Error: Al actualizar el Evento","Error en la funcion Unidades o Nivel de Peligro","error");
            }
            window.location.reload();
        });

        // window.location.reload();
        
    }
        
    
    


//////////////////////////////////////////////////////////////////////////////////////////////////////////
// Abrir mapa
let lat;
let long;
$(document).on('click', '.btnDireccionarMapa', function() {
    
    //Desplegar mapa para direccionar al lugar
    toggleMapa();
    
    // Obtener el valor del ID del evento desde la celda
    ev_id = $(this).closest('tr').find('#id_evento_celda').attr('value');
    // ev_id = 85;

    consultarEventoMostarMapa(ev_id);

});

function toggleMapa() {
    
    $('#modal-mapa').toggle();

}

function consultarEventoMostarMapa(ev_id) {

    $.post("../../controller/evento.php?op=get_evento_id", {ev_id: ev_id}, function(data, status) {
        
        var eventos = JSON.parse(data);

        var direccion = eventos[0]['ev_direc'];
        
        // Expresión regular para extraer las coordenadas
        var coordenadasRegex = /(-?\d+\.\d+),\s*(-?\d+\.\d+)/;

        var match = direccion.match(coordenadasRegex);

        if (match) {
            // Si hay coincidencias, el primer grupo capturado será la latitud y el segundo será la longitud
            lat = parseFloat(match[1].trim());
            long = parseFloat(match[2].trim());

            console.log(lat);
            console.log(long);
        } else {
            console.log("No se encontraron coordenadas en la dirección.");
        }

        mostrarMapa(lat, long);
    });
}

var LocationUserOrigin;

async function mostrarMapa(lat, long) {

    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17, // Nivel de zoom
        mapId: 'DEMO_MAP_ID' // Map ID requerido para AdvancedMarkerElement
    });

    //Activación y ejecución de la obtención de coordenadas del usuario
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
                
            var userLocation = {
                lat: lat,
                lng: long
            };
            LocationUserOrigin = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            //Marcador que ingresa el usuario
            var marker = new AdvancedMarkerElement({
                position: userLocation, // Coordenadas del marcador
                map: map,
                title: 'ArrastrarEmergencia'
            });
            map.setCenter(userLocation);

        }, function(error) {        
            // Manejo de errores
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    console.error("El usuario denegó la solicitud de geolocalización.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    console.error("La información de ubicación no está disponible.");
                    break;
                case error.TIMEOUT:
                    console.error("Se agotó el tiempo de espera para la solicitud de geolocalización.");
                    break;
                default:
                    console.error("Error desconocido al intentar obtener la ubicación.");
            }
            swal("Error de Geolocalización!", "No se logró obtener la ubicación", "error");
        });
    } else {
        console.error('Error: El navegador no soporta geolocalización.');
    }
}

//Btn Cerrar evento (Añade hora cierre)
// Redireccionar a Google Maps para crear ruta
$('.btnCrearRuta').off('click').on('click', function() {
    // Redirecciona a Google Maps
    if (LocationUserOrigin && lat && long) {
        window.location.href = "https://www.google.com/maps/dir/" + LocationUserOrigin.lat + "," + LocationUserOrigin.lng + "/" + lat + "," + long;
    } else {
        console.error("No se han obtenido las coordenadas necesarias.");
    }
});

$('.CerrarModalMap').off('click').on('click', function() {
    
    // Llamar a la función para mostrar u ocultar la pestaña
    toggleMapa();

});

// Este script debe estar después de incluir la API de Google Maps
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.397, lng: 150.644}, // Coordenadas iniciales
        zoom: 8 // Nivel de zoom
    });
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Cerrar evento

// Mapeo de motivos de cierre según la categoría
var motivosCierre = {
    "Incendios": [
        "Controlado y extinguido",
        "Sin víctimas ni daños mayores",
        "Daños estructurales controlados",
        "Necesidad de investigación adicional",
        "Requiere seguimiento por posibles puntos calientes"
    ],
    "Intoxicacion": [
        "Paciente estabilizado y trasladado a hospital",
        "Intoxicación leve, tratado en el lugar",
        "Causa de intoxicación identificada y mitigada",
        "Necesidad de seguimiento médico adicional",
        "Contaminación controlada y descontaminación realizada"
    ],
    "Caidadearbol": [
        "Árbol removido y área despejada",
        "Sin daños a personas o propiedades",
        "Daños a infraestructura reparados",
        "Necesidad de evaluación adicional de árboles cercanos",
        "Servicios públicos restablecidos"
    ],
    "AccidenteVehicular": [
        "Víctimas estabilizadas y trasladadas a hospital",
        "Vehículos retirados y tráfico restablecido",
        "Necesidad de investigación adicional por parte de la policía",
        "Daños a infraestructura reparados",
        "Seguimiento de seguro y responsabilidades"
    ],
    "DesordenPublico": [
        "Situación controlada y disuelta",
        "Sin heridos ni daños mayores",
        "Detenciones realizadas y sospechosos en custodia",
        "Necesidad de patrullaje adicional en la zona",
        "Investigación adicional requerida"
    ],
    "Asaltos": [
        "Sospechosos detenidos y bajo custodia",
        "Sin heridos mayores",
        "Recuperación de bienes robados",
        "Necesidad de patrullaje adicional en la zona",
        "Investigación adicional requerida"
    ],
    "Otros": [
        "Situación controlada y resuelta",
        "Víctimas atendidas y trasladadas",
        "Daños mitigados y área asegurada",
        "Investigación adicional requerida",
        "Necesidad de seguimiento y monitoreo",
        "Sospechosos detenidos y bajo custodia"
    ]
};


// Activar el botón de Cerrar Evento
$(document).on('click', '.btnPanelCerrar', function() {
    // Llamar a la función para mostrar u ocultar la pestaña 
    togglePestanaCerrar();
    
    // Obtener el valor del ID del evento desde la celda
    var id_evento = $(this).closest('tr').find('#id_evento_celda').attr('value');

    // Llama a la función para consultar la categoría y otros detalles
    consultarCategoriaCierre(id_evento);
    mostrarCatIdEventoCierre(id_evento);
});

// Función para mostrar u ocultar la pestaña Panel Cerrar
function togglePestanaCerrar() { 
    $('#selector-cerrar').toggle();
}

// Función para obtener el cat_id del evento y mostrarlo en el div cat_id
function mostrarCatIdEventoCierre(ev_id) {
    $('#ev_id_cierre').text(ev_id);
}

// Función para mostrar el cat_nom_cierre en el div y cargar los motivos de cierre
function consultarCategoriaCierre(ev_id) {
    $.post("../../controller/categoria.php?op=get_cat_nom_by_ev_id", { ev_id: ev_id }, function(data, status) {
        try {
            var jsonData = JSON.parse(data);
            if (jsonData && jsonData.cat_nom) {
                $('#cat_nom_cierre').text(jsonData.cat_nom); // Usar .text() para establecer el contenido del div
                cargarMotivosCierre(jsonData.cat_nom); // Cargar los motivos de cierre según la categoría
            } else {
                console.log("No se encontró el cat_nom correspondiente para el evento con ID: " + ev_id);
            }
        } catch (error) {
            console.log("Error al analizar la respuesta JSON:", error);
        }
    });
}


// Función para normalizar las claves de las categorías
function normalizarCategoria(categoria) {
    return categoria
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Eliminar acentos
        .replace(/ /g, ''); // Eliminar espacios
}

// Función para cargar los motivos de cierre en el select
function cargarMotivosCierre(categoria) {
    var categoriaNormalizada = normalizarCategoria(categoria);
    var motivos = motivosCierre[categoriaNormalizada] || [];
    var $select = $('#motivo_cierre');
    $select.empty();

    motivos.forEach(function(motivo) {
        $select.append($('<option>', { value: motivo, text: motivo }));
    });
}

//Btn Cerrar evento (Añade hora cierre)
$('.btnCerrarEvento').off('click').on('click',function(){
    //Llama a la funcion cerrar evento Añade la hora final
    CerrarEvento();
        
    // Llamar a la función para mostrar u ocultar la pestaña
    togglePestanaCerrar();    
});
    
function CerrarEvento() {
    var ev_id = $('#ev_id_cierre').text();
    var detalle_cierre = $('#detalle_cierre').val();
    var motivo_cierre = $('#motivo_cierre').val();
    var nombre_apellido = $('#nombre_apellido').val();

    // Fecha y Hora
    var ev_final = new Date();
    var año = ev_final.getFullYear();
    var mes = ev_final.getMonth() + 1; // Mes en JavaScript es 0-indexado, así que suma 1
    var dia = ev_final.getDate();
    var horas = ev_final.getHours();
    var minutos = ev_final.getMinutes();
    var segundos = ev_final.getSeconds();

    // Formatear la fecha y hora como desees
    var fechaFormateada = año + '-' + mes + '-' + dia + ' ' + horas + ':' + minutos + ':' + segundos;

    // Estado del evento al cerrarse
    var ev_est = 2;

    // Respuesta de consulta cerrarEvento
    $.post("../../controller/evento.php?op=cerrar_evento", {
        ev_id: ev_id,
        ev_final: fechaFormateada,
        ev_est: ev_est,
        detalle_cierre: detalle_cierre,
        motivo_cierre: motivo_cierre,
        nombre_apellido: nombre_apellido
    }, function(data) {
        if(data == 1) {
            swal("Evento Finalizado", "El evento se ha cerrado correctamente", "success");
        } else {
            swal("No Finalizado", "El evento no se ha podido cerrar correctamente", "error");
        }
    });
}
    
// Cancelar actualización PAnel Cerrar
$('.btnCancelarCerrar').off('click').on('click',function(){
    // Llamar a la función para mostrar u ocultar la pestaña
    togglePestanaCerrar();
});