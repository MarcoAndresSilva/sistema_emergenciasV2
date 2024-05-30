// API GOOGLE MAPS
$(document).ready(async function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.Map');

    // Añadir una clase al enlace
    enlace.classList.add('selected');

    // Importar la librería de marcadores avanzados de Google Maps
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    // Función para cargar el mapa
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17, // Nivel de zoom
        center: { lat: -34.397, lng: 150.644 }, // Coordenadas iniciales (puedes ajustar según tus necesidades)
        mapId: 'DEMO_MAP_ID', // Map ID requerido para AdvancedMarkerElement (solo si lo necesitas)
        apiKey: 'AIzaSyAQrYCFSz7Q-a-WONxo4yymu9SAPgmaA6c' // Tu clave de API
    });

    // Este script debe estar después de la inicialización del mapa
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            var marker = new AdvancedMarkerElement({
                position: userLocation, // Coordenadas del marcador
                map: map,
                title: 'Tu estás aquí'
            });

            map.setCenter(userLocation);

        }, function() {
            // Manejo de errores
            console.error('Error: No se pudo obtener la ubicación del usuario.');
        });
    } else {
        console.error('Error: El navegador no soporta geolocalización.');
    }

    // Código adicional para cargar una imagen de un evento específico
    var ev_id = 165;
    $.post("../../controller/evento.php?op=get_evento_id", { ev_id: ev_id }, function(respuesta, status) {
        // Parsear la respuesta JSON
        var data = JSON.parse(respuesta);
        console.log("../" + data[0]['ev_img']);
        // Ruta imagen
        var imagenURL = "../" + data[0]['ev_img'];

        $('#imagen-cargada').attr('src', imagenURL);
    });
});
