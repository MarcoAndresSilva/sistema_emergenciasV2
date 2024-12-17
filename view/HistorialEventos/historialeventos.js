$(document).ready(function() {
    cargarTablaGeneral();
});

function cargarTablaGeneral() {
    $('#tabla-historial').DataTable({
        "pageLength": 10,
        "lengthMenu": [[10, 20, 50], [10, 20, 50]],
        "ordering": true,
        "searching": true,
        "paging": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "ajax": {
            "url": "../../controller/evento.php?op=tabla-historial-eventos",
            "type": "POST",
            "dataSrc": "" 
        },
        "order": [[0, "desc"]],
        "columns": [
            { "data": "ev_id" },
            { "data": "categoria" },
            { "data": "direccion" },
            { "data": "asignacion" },
            { "data": "nivel_peligro" },
            { "data": "estado" },
            { "data": "fecha_apertura" },
            { "data": "ver_documentos" },
            { "data": "ver_informe" }
        ],
          language: {
                url: "../registrosLog/spanishDatatable.json"
            },
          destroy: true, // Permite volver a inicializar la tabla si ya ha sido creada
        "drawCallback": function(settings) {
            // Aquí aplicas nuevamente los estilos o cambios de color que necesitas
            $('.peligro_critico').addClass('label label-pill label-primary');
            $('.peligro_medio').addClass('label label-pill label-warning');
            $('.peligro_bajo').addClass('label label-pill label-success');
            $('.peligro_comun').addClass('label label-pill label-default');
        }
    });

         // Asegúrate de que el evento de clic esté delegado correctamente
    $('#tabla-historial').on('click', '.btnDocumentos', function() {     
        var ev_id = $(this).data('ev-id');
        cargar_documentos(ev_id);
    });
}
/////////////////////////////////////////////////////////////////////////////////////////////
let lat;
let long;
$(document).on('click', '.btnDireccionarMapa', function() {
    evento_id = $('#tabla-historial').DataTable().row($(this).closest('tr')).data().ev_id;
    consultarEventoMostarMapa(evento_id);
    toggleMapa();
});

function toggleMapa() {   
    $('#modal-mapa').toggle();
}

function consultarEventoMostarMapa(ev_id) {

    $.post("../../controller/evento.php?op=get_evento_id",{ev_id : ev_id}, function(data,status){
        
        var eventos = JSON.parse(data);

        lat = eventos['ev_latitud'];
        long = eventos['ev_longitud'];

        mostrarMapa(lat,long);
    });
}

function mostrarMapa(lat,long) {
    const eventLocation = { lat, lng: long };

    var map = new google.maps.Map(document.getElementById('map'), {
        center: eventLocation,
        zoom: 17 // Nivel de zoom
    });

    //Activación y ejecución de la obtencion de coordenadas del usuario
    new google.maps.Marker({
        position: eventLocation,
        map: map,
        title: 'Ubicación del evento'
    });
}

$('.btnCrearRuta').off('click').on('click',function(){

    const alternativeUrl = `https://www.google.com/maps/dir//${lat},${long}`;

    // Muestra un mensaje explicativo sobre por qué necesitas la ubicación
    Swal.fire({
        icon: 'info',
        title: 'Permiso de ubicación requerido',
        text: 'Necesitamos tu ubicación para mostrar la ruta más precisa hacia el evento. Si no deseas compartir tu ubicación, podemos mostrarte la ruta general.',
        showCancelButton: true,
        confirmButtonText: 'Dar permisos',
        cancelButtonText: 'Continuar sin permisos'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario acepta, intenta obtener la ubicación
            obtenerUbicacion(alternativeUrl);
        } else {
            // Si el usuario decide no dar permisos, usa la URL alternativa
            window.open(alternativeUrl, '_blank');
        }
    });
});

function obtenerUbicacion(alternativeUrl = '') {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
                
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // Crea la URL con la ubicación del usuario
            const dynamicUrl = `https://www.google.com/maps/dir/${userLat},${userLng}/${lat},${long}`;
            window.open(dynamicUrl, '_blank');
        }, function () {
            // Si el usuario no permite la ubicación, usa la URL alternativa
            Swal.fire({
                icon: 'warning',
                title: 'Ubicación no disponible',
                text: 'No se pudo acceder a tu ubicación. Mostrando la ruta general.',
                confirmButtonText: 'Entendido'
            }).then(() => {
                window.open(alternativeUrl, '_blank');
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Geolocalización no soportada',
            text: 'Tu navegador no admite la función de geolocalización.',
            confirmButtonText: 'Entendido'
        }).then(() => {
            window.open(alternativeUrl, '_blank');
        });
    }
}


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
