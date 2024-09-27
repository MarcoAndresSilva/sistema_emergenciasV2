$(document).ready(function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.ControlEventos');
    // Añadir una clase al enlace
    enlace.classList.add('selected');

    cargarTablaGeneral();
});

function cargarTablaGeneral() {
    $('#tabla-control').DataTable({
        "pageLength": 10,
        "lengthMenu": [[10, 20, 50], [10, 20, 50]],
        "ordering": true,
        "searching": true,
        "paging": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "ajax": {
            "url": "../../controller/evento.php?op=tabla-control",
            "type": "POST",
            "dataSrc": "" 
        },
        "columns": [
            { "data": "ev_id" },
            { "data": "categoria" },
            { "data": "direccion" },
            { "data": "asignacion" },
            { "data": "nivel_peligro" },
            { "data": "estado" },
            { "data": "fecha_apertura" },
            { "data": "ver_derivar" },
            { "data": "ver_detalle" }

        ],
        "createdRow": function(row,data, dataIndex){
        $("td", row).eq(0).attr("id","id_evento_celda");
        },
        "drawCallback": function(settings) {
            // Aquí aplicas nuevamente los estilos o cambios de color que necesitas
            $('.peligro_critico').addClass('label label-pill label-primary');
            $('.peligro_medio').addClass('label label-pill label-warning');
            $('.peligro_bajo').addClass('label label-pill label-success');
            $('.peligro_comun').addClass('label label-pill label-default');
        }
    });

         // Asegúrate de que el evento de clic esté delegado correctamente
    $('#tabla-control').on('click', '#btnDetalleEmergencia', function() {
        ev_id = $(this).data('ev-id');
        ver(ev_id);
    });
}

function ver(ev_id) {
    // Abrir una nueva pestaña con la ruta especificada
    window.open(`../EmergenciaDetalle?ID=${ev_id}`, '_blank');
    
}


 //Variable id_evento
 $id_evento = $(this).data('ev-id');


//////////btn derivar//////////// 
$(document).on("click", "#btnPanelDerivar", function(e) {
    console.log('Button Derivar clicked');
    mostrarModal('#modalDerivar');
   
    $id_evento = $(this).data('ev-id');
    mostrarIdEvento($id_evento);
    consultarCategoria($id_evento);
    consultarNivelPeligro($id_evento);
    consultarUnidadDisponible($id_evento);
});


function mostrarModal(modalId) {
    var modal = $(modalId);
    if (modal.length) {
        modal.removeClass('fade');
        modal.modal('show');
    } else {
        console.error('Modal not found: ' + modalId);
    }
}

// Función para obtener el id del evento y mostrarlo en el label
function mostrarIdEvento(ev_id) {
    $('#ev_id').text(ev_id);
}

// Función para mostrar el cat_nom en el label
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

//Funcion para consultar las unidades disponibles y mostrarlas en los check
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


$(document).on("click", ".btnActualizarTodos", function() {
    console.log('Button actualizar clicked');
    ActualizarTodo($id_evento);
});



function ActualizarTodo($id_evento){
    console.log("actualizar todod clicked");
    mostrarIdEvento($id_evento);
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
        
    
    
    
//////////////////////////////////////////////// Abrir mapa////////////////////////////////////////////////////////////

let lat;
let long;
$(document).on('click', '.btnDireccionarMapa',function() {
    
    // Encuentra la fila correspondiente
    var $tr = $(this).closest('tr');
    
    // Obtén la instancia de DataTables
    var table = $('#tabla-control').DataTable();
    
    // Usa DataTables para obtener los datos de la fila
    var data = table.row($tr).data();
    
    // Obtén el ev_id desde los datos de la fila
    var ev_id = data.ev_id;

    // Desplegar mapa para direccionar al lugar
    toggleMapa();
    consultarEventoMostarMapa(ev_id);
});

function toggleMapa() {   
    $('#modal-mapa').toggle();
}

function consultarEventoMostarMapa(ev_id) {

    $.post("../../controller/evento.php?op=get_evento_id",{ev_id : ev_id}, function(data,status){
        
        var eventos = JSON.parse(data);

        lat = eventos[0]['ev_latitud'];
        long = eventos[0]['ev_longitud'];

        mostrarMapa(lat,long);
    });
}

var LocationUserOrigin;

function mostrarMapa(lat,long) {

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17 // Nivel de zoom
    });

    //Activación y ejecución de la obtencion de coordenadas del usuario
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
            marker = new google.maps.Marker({
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
            swal("Error de Geolocalización!","No se logro optener la ubicación", "error");
        });
    } else {
        console.error('Error: El navegador no soporta geolocalización.');
    }
}

//Btn Crear ruta)
$('.btnCrearRuta').off('click').on('click',function(){
    
    // Redirecciona a google maps
    window.location.href = "https://www.google.com/maps/dir/" + LocationUserOrigin.lat + "," + LocationUserOrigin.lng + "/" + lat + "," + long  ;

});

$('.CerrarModalMap').off('click').on('click',function(){
    
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
