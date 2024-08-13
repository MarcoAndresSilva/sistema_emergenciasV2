$(document).ready(function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.HistorialEventos');
    // Añadir una clase al enlace
    enlace.classList.add('selected');

    cargarTablaGeneral();
});

function cargarTablaGeneral() {
    // Funcion para cargar los datos de las tablas
    $.post("../../controller/evento.php?op=tabla-historial-eventos", function(respuesta, status) {
        // Parsear la respuesta JSON
        var data = JSON.parse(respuesta);
        
        $('#datos-criticos').html(data.critico);
        $('#datos-medios').html(data.medio);
        $('#datos-bajos').html(data.bajo);
        $('#datos-generales').html(data.comun);

        // Agregar el evento click a los botones de ver detalle
        $('.btnDetalleEmergencia').click(function() {
            var ev_id = $(this).data('ev-id');
            ver(ev_id);
        });
    });
}

function ver(ev_id) {
    // Abrir una nueva pestaña con la ruta especificada
    window.open(`../EmergenciaDetalle?ID=${ev_id}`, '_blank');
}


/////////////////////////////////////////////////////////////////////////////////////////////
let lat;
let long;
$(document).on('click', '.btnDireccionarMapa',function() {
    
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