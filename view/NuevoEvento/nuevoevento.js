var coordsUser = {};
var permitirUbicacion = true;
var marker;
var mapContainer = document.getElementById('map');



$(document).ready(function() {
    
    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.NuevoEvento');
    
    // Añadir una clase al enlace
    enlace.classList.add('selected');

    
    //Funcion para cargar los datos de la tabla categoria
    $.post("../../controller/categoria.php?op=combo",function(data,status){
        $('#cat_id').html(data);
    });

    //Funcion para mostrar el select en el que se compartira la ubicacion 
        $('#elegir-ubicacion').on('change', function() {
            var selectedOption = $(this).val();
            if (selectedOption === 'direccion-escrita') {
                $('#direccion-escrita').show();
                $('#direccion-geolocalizacion').hide();
            } else if (selectedOption === 'ubicacion-content') {
                $('#direccion-escrita').hide();
                $('#direccion-geolocalizacion').show();
            }
        });
    });

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ID para realizar una correcta asignación de unidades al evento
var cat_id;
var alerta = true;
//Activacion del boton guardar

$('#btnGuardar').off('click').on('click', function() {
    if (validarFormulario()) {
        // Llama a la función pasa add_evento 
        add_evento();
        
        // insert_asignacion_unidades(ev_id, cat_id);

        if(alerta){
            swal("Emergencia Registrada", "Pronto se trabajará en esta emergencia", "success");
        }else{
            swal("Algo Salio Mal", "No se logro registrar la emergencia", "error");
        }
    }
});
////////////////////////////////////////////////////////////////////////////////////////////////////
//Boton para cargar el archivo
document.getElementById('btnCargarArchivo').addEventListener('click', function() {
    //Evita que el formulario se envie
    event.preventDefault();
    
    //Activa la funcion del input type file
    document.getElementById('imagen').click();
});

document.getElementById('imagen').addEventListener('change', function() {
    var label = document.getElementById('archivoAdjuntado');
    if (this.files && this.files.length > 0) {
      label.textContent = this.files[0].name; // Actualiza el contenido del label con el nombre del archivo seleccionado
    } else {
      label.textContent = 'No hay archivo adjunto (.JPG/.JPEG/.PNG)';
    }
});

////////////////////////////////////////////////////////////////////////////////////////////////////
//Funcion para tomar datos y concretar funcion add_evento
function add_evento() {
    let ev_id;
    //Verifica si el marcador esta definido
    if (marker === null || marker === undefined) {
        console.log("El marcador es nulo o no está definido");
    } else {
        var newCoords = marker.getPosition();
    }

    // Lógica para obtener datos dinámicos de la sesión y llenar campos
    var nombre = "<?php echo $_SESSION['nombre']; ?>";
    var apellido = "<?php echo $_SESSION['apellido']; ?>";
    var telefono = "<?php echo $_SESSION['telefono']; ?>";
    var correo = "<?php echo $_SESSION['correo']; ?>";

    $('#nombre').val(nombre);
    $('#apellido').val(apellido);
    $('#telefono').val(telefono);
    $('#correo').val(correo);

    // Aquí agregamos la variable que falta
    var ev_desc = $('#descripcion').val();
    var ev_est = 1;


    //Fecha y Hora
    var ev_inicio = new Date();
    var anio = ev_inicio.getFullYear();
    var mes = ev_inicio.getMonth() + 1; // Mes en JavaScript es 0-indexado, así que suma 1
    var dia = ev_inicio.getDate();
    var horas = ev_inicio.getHours();
    var minutos = ev_inicio.getMinutes();
    var segundos = ev_inicio.getSeconds();
    // Formatear la fecha y hora como desees
    var fechaFormateada = anio + '-' + mes + '-' + dia + ' ' + horas + ':' + minutos + ':' + segundos;
    ev_inicio = fechaFormateada;

    //Variable Categoría
    cat_id = $('#cat_id').val();
    //Verificamos la categoría asignada para automatizar la asignación de unidades a las emergencias

    var ev_direc = $('#address').val();
    //Obtiene el valor del radiobutton del uso de ubicación
    var valorUbicacion = $("input[name='ubicacion']:checked").val();

    //Utiliza la ubicación del marcador
    if (valorUbicacion === 'permitir') {
        if ($('#address').val() === "") {
            ev_direc = 'Sin dirección , ' + marker.getPosition().lat() + ',' + marker.getPosition().lng();
            console.log('Coordenadas a uilizar: ' + ev_direc);

        } else if ($('#address').val() !== "") {
            ev_direc += ' , ' + marker.getPosition().lat() + ',' + marker.getPosition().lng();
        }

    }
    //Utiliza la ubicación actual del dispositivo
    else if (valorUbicacion === 'permitirActual') {
        if ($('#address').val() === "") {
            ev_direc = 'Sin dirección , ' + marker.getPosition().lat() + ',' + marker.getPosition().lng();

        } else if ($('#address').val() !== "") {
            ev_direc += ' , ' + marker.getPosition().lat() + ',' + marker.getPosition().lng();
        }
    }
    //No utiliza ubicación por lo que añade un string al final de la dirección para especificar que no hay coordenadas
    else if (valorUbicacion === 'noPermitir') {
        ev_direc += " , No hay coordenadas";
    }

    // Valida si la direccion esta vacía o No
    validarCampoVacioDireccion('#address', 'Debes ingresar una dirección.');

    const ev_niv = 0;
    var ev_img = "";

    
    $.post("../../controller/evento.php?op=add_evento", {
        ev_desc: ev_desc,
        ev_est: ev_est,
        ev_inicio: ev_inicio,
        ev_direc: ev_direc,
        cat_id: cat_id,
        ev_niv: ev_niv,
        ev_img: ev_img
    }, function(data, status) {

        console.log(data);

        if (data == 1) {

            $.post("../../controller/evento.php?op=get_id_ultimo_evento", function(dataId, status) {
                ev_id = dataId;

                console.log(dataId);
                console.log(ev_id);

                // Después de agregar el evento con éxito, cargar la imagen
                var formData = new FormData($('#event_form')[0]);
                formData.append('ev_id', ev_id);
                $.ajax({
                    url: '../../controller/evento.php?op=carga-imagen',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response == 1) {
                            console.log("Imagen cargada correctamente");
                        } else {
                            console.log("Error al cargar la imagen");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud de carga de imagen: " + error);
                    }
                });
                if (data == 1) {
                    console.log("data == 1");
                } else {
                    alerta === false;
                }
                insert_asignacion_unidades(ev_id, cat_id);
                console.log(ev_asig);
            });

            // $('#nombre').val('');
            // $('#mail').val('');
            $('#descripcion').val('');
            // $('#address').val('');
            $('#cat_id').val(1);
            // $('#phone').val(1);

        } else {
            alerta === false;
        }
    });

};


function insert_asignacion_unidades(ev_id, cat_id){
    var ev_asig = [];
    switch (cat_id){
        case "1":
            ev_asig = [2,3,4,5];
            break;
            
        case "2":
            ev_asig = [3];
            break;
            
        case "3":
            ev_asig = [3,4,5];
            break;
            
        case "4":
            ev_asig = [2,3];
            break;
            
        case "5":
            ev_asig = [2];
            break;
            
        case "7":
            ev_asig = [2];
            break;
            
        case "9":
            ev_asig = [2,3,4,5];
            break;
    }

    ev_asig.forEach(function(unid_id){
        $.post("../../controller/eventoUnidad.php?op=insert_asignacion_unidades",{ev_id:ev_id, unid_id:unid_id},function(data,status){
            if (data == 0) {
                alerta == false;
            }
        });
    });

}



// Listener para los cambios en el checklist "Usar Ubicación"
document.querySelectorAll('input[name="ubicacion"]').forEach(function(radio) {
    //Contador para mostrar la información 1 sola vez
    permisoContador = 0;
    radio.addEventListener('change', function() {
        if(permisoContador == 0){
            swal("Aceptar el Permiso de Ubicación", "Favor Aceptar el permiso de Ubicación que solicita el sitio Web","info");
            permisoContador++;
        }
        permitirUbicacion = this.value === 'permitir';
        permitirActual = this.value ==='permitirActual';

        toggleMap();
        //API DE GOOGLE MAPS
        //Activación del mapa
        var map = new google.maps.Map(mapContainer, {
            zoom: 17 // Nivel de zoom
        });
        // Se utiliza un marcador que permite su arrastre
        if(permitirUbicacion){
            //Activación y ejecución de la obtencion de coordenadas del usuario
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    
                    var userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    //Marcador que ingresa el usuario
                    marker = new google.maps.Marker({
                        position: userLocation, // Coordenadas del marcador
                        map: map,
                        title: 'Arrastrar',
                        draggable: true
                    });
                    map.setCenter(userLocation);

                    // Agregar un evento al marcador para obtener las nuevas coordenadas cuando se arrastra
                    google.maps.event.addListener(marker, 'dragend', function (event) {
                        var newLat = event.latLng.lat();
                        var newLng = event.latLng.lng();
                        userLocation = {
                            lat: newLat,
                            lng: newLng
                        };
                        console.log('Nuevas coordenadas: ' + userLocation.lat + ',' + userLocation.lng);
                    });

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
        // Se utiliza un marcador fijo de la ubicación actual
        else if(permitirActual){
            //Activación y ejecución de la obtencion de coordenadas del usuario
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    
                    var userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    //Marcador que ingresa el usuario
                    marker = new google.maps.Marker({
                        position: userLocation, // Coordenadas del marcador
                        map: map,
                        title: 'Tu ubicación'
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

        

    });
});

// Función para mostrar u ocultar la pestaña
function toggleMap() {
    
    if (permitirUbicacion || permitirActual) {
        // Mostrar el mapa si la ubicación o la ubicaciónActual está permitida
        mapContainer.style.display = 'block';
    } else{
        // Ocultar el mapa si la ubicación no está permitida
        mapContainer.style.display = 'none';
    }
}
//Validación del formulario
function validarFormulario() {
    return (
        // validarCampoNombre('#nombre', 'Debes ingresar un nombre sin caracteres especiales') &&
        // validarEmail('#mail', 'Debes ingresar una dirección de correo electrónico válida.') &&
        validarCampoVacio('#descripcion', 'Debes ingresar una descripción.') &&
        validarCampoVacio('#cat_id', 'Debes seleccionar una categoría.')
        // validarTelefono('#phone', 'El teléfono solo puede contener números y no puede estar vacío.')
    );
}
// el nombre vendra diamicamente del perfil
// function validarCampoNombre(selector, mensajeError) {
//     var valor = $(selector).val().trim();
    
//     // Expresión regular que permite letras, espacios y tildes
//     var regexNombre = /^[A-Za-zÁ-Úá-ú\s]+$/;

//     if (valor === "" || !regexNombre.test(valor)) {
//         mostrarMensajeError(mensajeError);
//         return false;
//     }
    
//     return true;
// }

function validarCampoVacio(selector, mensajeError) {
    var valor = $(selector).val().trim();
    if (valor === "") {
        mostrarMensajeError(mensajeError);
        return false;
    }
    return true;
}

function validarCampoVacioDireccion(selector, mensajeError) {
    var valor = $(selector).val().trim();
    if (valor === "" & (coordsUser.lat === "" || coordsUser.lng === "")) {
        mostrarMensajeError(mensajeError);
        return false;
    }
    return true;
}

// function validarEmail(selector, mensajeError) {
//     var email = $(selector).val();
//     var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     if (!emailRegex.test(email)) {
//         mostrarMensajeError(mensajeError);
//         return false;
//     }
//     return true;
// }

// Función para validar el campo de teléfono
// function validarTelefono(selector, mensajeError) {
//     var telefono = $(selector).val().trim();
    
//     // Expresión regular que permite solo números
//     var regexTelefono = /^\d+$/;

//     if (telefono === "" || !regexTelefono.test(telefono)) {
//         mostrarMensajeError(mensajeError);
//         return false;
//     }
    
//     return true;
// }

function mostrarMensajeError(mensaje) {
    // Aquí puedes mostrar el mensaje de error en algún elemento específico o en la consola del navegador.
    console.error(mensaje);
    swal( "Validación de formulario",mensaje, "error" ) ;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
