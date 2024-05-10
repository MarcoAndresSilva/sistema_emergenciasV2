$where = "";
$(document).ready(function() {


    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.Historial');

    // Añadir una clase al enlace
    enlace.classList.add('selected');

    /////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
    //Funcion para cargar los datos de las tablas
    $.post("../../controller/evento.php?op=tabla-general-historial",function(respuesta,status){
        // Parsear la respuesta JSON
        var data = JSON.parse(respuesta);
        $('#datos-generales').html(data.html);
        
    });
    
    /////////////////////////////////////////////////////////////////////////////////////////////

    let select;
    
    select = $('#filtro-select').val();
    const input = document.querySelector('.search-input');
    input.addEventListener("keyup", (evento) => {
        // keyup - fired when key released
        var inputValue = input.value;
        let where = "";
        if (inputValue !== ""){
            
            where = input.value;
            $.post("../../controller/evento.php?op=get_eventos",{where: where},function(respuesta,status){
                
                datos = JSON.parse(respuesta);
                console.log(datos.array2);

                // Iterar sobre cada array interno en datos.array2
                for (let i = 0; i < datos.array2.length; i++) {
                    const arrayInterno = datos.array2[i];
                    
                    // Filtrar el array interno en busca del valor "asalto"
                    const filtrado = arrayInterno.filter(elemento => {
                        // Verificar si el elemento es una cadena antes de llamar a includes
                        if (typeof elemento === 'string') {
                            return elemento.includes("asalto");
                        }
                        return false;
                    });
                    
                    // Si se encuentra "asalto" en el array interno, imprimir el resultado
                    if (filtrado.length > 0) {
                        console.log("Se encontró 'asalto' en el array interno " + i + ":");
                        console.log(filtrado);
                    } else {
                        console.log("No se encontró 'asalto' en el array interno " + i);
                    }
                }

                // $('#datos-generales').html(datos.html);
            });
        }else if (evento.keyCode === 8 || evento.key === "Backspace") {
            where = input.value;
            $.post("../../controller/evento.php?op=get_eventos",{where: where},function(respuesta,status){
                
                datos = JSON.parse(respuesta);
                
                $('#datos-generales').html(datos.html);
            });
        }else {
            
            //Funcion para cargar los datos de la tabla en caso de no tener nada el inputValue
            $.post("../../controller/evento.php?op=tabla-general-historial",function(respuesta,status){
                // Parsear la respuesta JSON
                var data = JSON.parse(respuesta);
                $('#datos-generales').html(data.html);                   
            });
        }
    });   
});

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

//Btn Cerrar evento (Añade hora cierre)
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

/////////////////////////////////////////////////////////////////////////////////////////////

//Btn mostrar modal informacion emergencia
$(document).on('click', '.btnInfoEmergencia', function() {
    
    //Abre el modal de la informacion de la emergencia
    toggleModalInfoEmergencia();

    // Obtener el valor del ID del evento desde la celda
    ev_id = $(this).closest('tr').find('#id_evento_celda_historial').attr('value');

    cargarInfoEmergencia();

});

// Obtener el nombre del usuario que reportó la emergencia

$('.btnCerrarInfoEmergencia').off('click').on('click',function(){
    
    //Cierra el modal de la informacion de la emergencia
    toggleModalInfoEmergencia();

    // Vaciar los valores de los elementos <p>
    $('#id_info_emergencia').text("");
    $('#categoria_info_emergencia').text("");
    $('#direccion_info_emergencia').text("");
    $('#unidades_info_emergencia').text("");
    $('#estado_info_emergencia').text("");
    $('#fecha_info_emergencia').text("");
    
    // Vaciar el atributo src del elemento <img>
    $('#imagenEmergencia').attr('src', "");

});

function toggleModalInfoEmergencia() {
    
    $('#modalInfoEmergencia').toggle();
    
}

function cargarInfoEmergencia() {

    $.post("../../controller/evento.php?op=get_evento_id",{ev_id : ev_id}, function(data,status){
        
        var eventos = JSON.parse(data);

        $('#id_info_emergencia').html(eventos[0]['ev_id']);
        const cat_id = eventos[0]['cat_id'];

        // Categoria de la emergencia
        $.post("../../controller/categoria.php?op=datos_categoria",{cat_id : cat_id}, function(datos_categoria_respuesta,status){
            
            datos_categoria = JSON.parse(datos_categoria_respuesta);
            $('#categoria_info_emergencia').html(datos_categoria[0]['cat_nom']);

        });

        $('#direccion_info_emergencia').html(eventos[0]['ev_direc']);

        // Obtiene el id de la emergencia en la variable
        const ev_id = eventos[0]['ev_id'];

        // Obtiene todas las unidades relacioandas con la emergencia de id ev_id
        $.post("../../controller/eventoUnidad.php?op=get_datos_eventoUnidad",{ev_id : ev_id}, function(datos_unidad_respuesta,status){
            
            // Transforma la respuesta string en un arreglo
            datos_unidad = JSON.parse(datos_unidad_respuesta);
            
            //Crea un arreglo para almacenar las unidades obtenidas
            let arreglo_unidades = [];
            // Recorre el arreglo de la variable datos_unidad
            for (let i = 0; i < datos_unidad.length; i++) {

                let unidad = datos_unidad[i];

                //Añade el unid_id a la variable
                let unid_id = unidad[0];

                //Utiliza la variable unid_id para obtener los datos de la unidad (Se genera una function anonima para controlar la asincronía)
                (function(unid_id) {
                    $.post("../../controller/unidad.php?unidad=datos_unidad", { unid_id: unid_id }, function(datos_unidad_respuesta_unica) {
                        
                        datos_unidad_unica = JSON.parse(datos_unidad_respuesta_unica);
                        
                        let nombre_unidad = datos_unidad_unica[0]['unid_nom'];
                        
                        arreglo_unidades.push(nombre_unidad);
        
                        // Si todas las unidades se han procesado, actualiza el HTML
                        if (arreglo_unidades.length === datos_unidad.length) {
                            
                            const str_unidades = arreglo_unidades.join(",");

                            $('#unidades_info_emergencia').html(str_unidades);
                        }
                    });
                })(unid_id);
            }

        });

        const ev_est = eventos[0]['ev_est'];
        // Estado de la emergencia
        $.post("../../controller/estado.php?solicitud=estado_emergencia",{ev_est : ev_est}, function(datos_estado_respuesta,status){
            
            datos_estado_respuesta = JSON.parse(datos_estado_respuesta);
            $('#estado_info_emergencia').html(datos_estado_respuesta[0]['est_nom']);

        });
        
        $('#fecha_info_emergencia').html(eventos[0]['ev_inicio']);

        //Mostrar la imagen del evento
        let rutaImagen = eventos[0]['ev_img'];


        if(rutaImagen !== "" && rutaImagen !== undefined && rutaImagen !== null){

            let imagenEmergencia = document.getElementById("imagenEmergencia");

            imagenEmergencia.src =  "../" + rutaImagen;
        }else {

            let imagenEmergencia = document.getElementById("imagenEmergencia");
            
            imagenEmergencia.src = "../../public/img/logo_emergencia.png";
        }

    });
}