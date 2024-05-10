//API GOOGLE MAPS
$(document).ready(function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.Map');

    // Añadir una clase al enlace
    enlace.classList.add('selected');

    //Funcion para cargar el mapa
    // initMap();
   
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17 // Nivel de zoom
    });

    // Este script debe estar después de la inicialización del mapa
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            console.log(userLocation);
            var marker = new google.maps.Marker({
                position: {lat: position.coords.latitude, lng: position.coords.longitude}, // Coordenadas del marcador
                map: map,
                title: 'Tu estas aquí'
            });

            map.setCenter(userLocation);

        }, function() {
            // Manejo de errores
            console.error('Error: No se pudo obtener la ubicación del usuario.');
        });
    } else {
        console.error('Error: El navegador no soporta geolocalización.');
    }

    var ev_id = 165;
    $.post("../../controller/evento.php?op=get_evento_id",{ev_id:ev_id},function(respuesta,status){
        // Parsear la respuesta JSON
        var data = JSON.parse(respuesta);
        console.log("../" + data[0]['ev_img']);
        //Ruta imagen
        var imagenURL = "../" + data[0]['ev_img'];

        $('#imagen-cargada').attr('src', imagenURL)
        
    });
});

// Este script debe estar después de incluir la API de Google Maps
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.397, lng: 150.644}, // Coordenadas iniciales
        zoom: 8 // Nivel de zoom
    });
}