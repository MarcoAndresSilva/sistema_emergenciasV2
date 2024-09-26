$(document).ready(function() {

  // Obtener el elemento <a> por su ID
  var enlace = document.querySelector('.NuevoEvento');
  // Añadir una clase al enlace
  enlace.classList.add('selected');
});



var map, marker;
var melipilla = {lat: -33.68546006255509, long: -71.21451520290904};
var currentLat = melipilla.lat;
var currentLng = melipilla.long;

$(document).ready(function() {
  initMap();
  initAutocomplete();
  cargarCategorias();

  $('#solicitarUbicacion').on('change', function() {
    if ($(this).is(':checked')) {
      obtenerUbicacionUsuario();
    } else {
      resetMapToDefault();
    }
  });

  $('#address').on('input', function() {
    var address = $(this).val();
    if (address) {
      geocodeAddress(address);
    }
  });

  $('#btnGuardar').on('click', function() {
    if (validarFormulario()) {
      var direccion = $('#address').val();
      var latitud = currentLat;
      var longitud = currentLng;
      $('#ev_latitud').val(latitud);
      $('#ev_longitud').val(longitud);
      add_evento();
    }
  });

  $('#imagen').on('change', function() {
    var label = document.getElementById('archivoAdjuntado');
    if (this.files && this.files.length > 0) {
      label.textContent = this.files[0].name;
    } else {
      label.textContent = 'No hay archivo adjunto (.JPG/.JPEG/.PNG)';
    }
  });
});

function initMap() {
  var defaultLocation = { lat: currentLat, lng: currentLng };
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 17,
    center: defaultLocation
  });

  marker = new google.maps.Marker({
    position: defaultLocation,
    map: map,
    title: 'Arrastrar',
    draggable: true
  });

  google.maps.event.addListener(marker, 'dragend', function(event) {
    currentLat = event.latLng.lat();
    currentLng = event.latLng.lng();
    $('#ev_latitud').val(currentLat);
    $('#ev_longitud').val(currentLng);
    $('#address').val(updateAddressFromLatLng(currentLat, currentLng));
  });
}

function obtenerUbicacionUsuario() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      map.setCenter(pos);
      marker.setPosition(pos);
      currentLat = pos.lat;
      currentLng = pos.lng;
      $('#ev_latitud').val(currentLat);
      $('#ev_longitud').val(currentLng);

      actualizarDireccion(currentLat, currentLng);
    });
  } else {
    alert('Geolocalización no soportada por tu navegador.');
  }
}

function resetMapToDefault() {
  var defaultLocation = { lat: melipilla.lat, lng: melipilla.long };
  map.setCenter(defaultLocation);
  marker.setPosition(defaultLocation);
  currentLat = melipilla.lat;
  currentLng = melipilla.long;
  $('#ev_latitud').val(currentLat);
  $('#ev_longitud').val(currentLng);
  $('#address').val('');
}

function initMap() {
  var defaultLocation = { lat: currentLat, lng: currentLng };
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 17,
    center: defaultLocation
  });

  marker = new google.maps.Marker({
    position: defaultLocation,
    map: map,
    title: 'Arrastrar',
    draggable: true
  });

  google.maps.event.addListener(marker, 'dragend', function(event) {
    currentLat = event.latLng.lat();
    currentLng = event.latLng.lng();
    $('#ev_latitud').val(currentLat);
    $('#ev_longitud').val(currentLng);
    $('#address').val(updateAddressFromLatLng(currentLat, currentLng));
  });
}

function geocodeAddress(address) {
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({ 'address': address }, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      var location = results[0].geometry.location;
      currentLat = location.lat();
      currentLng = location.lng();
      map.setCenter(location);
      marker.setPosition(location);
      $('#ev_latitud').val(currentLat);
      $('#ev_longitud').val(currentLng);
    } else {
      console.error('Geocoding falló debido a: ' + status);
    }
  });
}

function obtenerUbicacionUsuario() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        map.setCenter(pos);
        marker.setPosition(pos);
        currentLat = pos.lat;
        currentLng = pos.lng;
        $('#ev_latitud').val(currentLat);
        $('#ev_longitud').val(currentLng);

        actualizarDireccion(currentLat, currentLng);
      },
      function(error) {
        // Manejar diferentes tipos de errores
        switch (error.code) {
          case error.PERMISSION_DENIED:
            swal("Advertencia", "Necesitas permitir el acceso al GPS para usar esta función.", "warning");
            break;
          case error.POSITION_UNAVAILABLE:
            swal("Advertencia", "La información de ubicación no está disponible.", "warning");
            break;
          case error.TIMEOUT:
            swal("Advertencia", "El tiempo de espera para obtener la ubicación ha expirado.", "warning");
            break;
          default:
            swal("Error", "Error desconocido al obtener la ubicación.", "error");
            break;
        }
      }
    );
  } else {
    swal("Error", "Geolocalización no soportada por tu navegador.", "error");
  }
}

function initAutocomplete() {
  var input = document.getElementById('address');
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();
    if (place.geometry) {
      currentLat = place.geometry.location.lat();
      currentLng = place.geometry.location.lng();
      $('#ev_latitud').val(currentLat);
      $('#ev_longitud').val(currentLng);
      marker.setPosition(place.geometry.location);
      map.setCenter(place.geometry.location);
    }
  });
}

function updateAddressFromLatLng(lat, lng) {
  var geocoder = new google.maps.Geocoder();
  var latLng = new google.maps.LatLng(lat, lng);
  var address = '';

  geocoder.geocode({ 'latLng': latLng }, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK && results[0]) {
      address = results[0].formatted_address;
      $('#address').val(address);
    } else {
      console.error('Geocoding falló debido a: ' + status);
    }
  });
  return address;
}

function cargarCategorias() {
  $.get("../../controller/categoria.php?op=combo", function(data) {
    $('#cat_id').html(data);
  }).fail(function(jqXHR, textStatus, errorThrown) {
    console.error("Error en la solicitud de categorías: ", textStatus, errorThrown);
    swal("Error al cargar categorías", "No se pudo cargar la lista de categorías.", "error");
  });
}

function toggleMap() {
  if ($('#permitirActual').is(':checked')) {
    $('#map').show();
    obtenerUbicacionUsuario();
  } else {
    $('#map').hide();
  }
}

function validarFormulario() {
  var direccionValida = validarCampoDireccion();
  var categoriaValida = validarCampoVacio('#cat_id', 'Debes seleccionar una categoría.');
  return direccionValida && categoriaValida;
}

function validarCampoVacio(selector, mensajeError) {
  var valor = $(selector).val() || ''; // Agregar valor por defecto vacío
  valor = valor.trim();
  if (valor === "") {
    mostrarMensajeError(mensajeError);
    return false;
  }
  return true;
}

function validarCampoDireccion() {
  var valorUbicacion = $("input[name='ubicacion']:checked").val();
  var direccion = $('#address').val() || ''; // Agregar valor por defecto vacío
  direccion = direccion.trim();
  var latitud = $('#ev_latitud').val() || ''; // Agregar valor por defecto vacío
  latitud = latitud.trim();
  var longitud = $('#ev_longitud').val() || ''; // Agregar valor por defecto vacío
  longitud = longitud.trim();

  if ((valorUbicacion === 'permitir' || valorUbicacion === 'permitirActual') && (latitud !== "" && longitud !== "")) {
    // Si hay latitud y longitud, no es necesario tener una dirección.
    return true;
  } else if (direccion === "") {
    // Si no hay latitud y longitud, se requiere una dirección.
    mostrarMensajeError('Debes ingresar una dirección.');
    return false;
  }

  return true;
}

function mostrarMensajeError(mensaje) {
  console.error(mensaje);
  swal("Faltan datos", mensaje, "info");
}


function obtenerDireccionDesdeCoordenadas(lat, lng) {
  return new Promise((resolve, reject) => {
    const geocoder = new google.maps.Geocoder();
    const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
    geocoder.geocode({ location: latlng }, (results, status) => {
      if (status === "OK") {
        if (results[0]) {
          resolve(results[0].formatted_address);
        } else {
          reject("No results found");
        }
      } else {
        reject("Geocoder failed due to: " + status);
      }
    });
  });
}

function actualizarDireccion(lat, lng) {
  var geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(lat, lng);

  geocoder.geocode({ 'latLng': latlng }, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        $('#address').val(results[0].formatted_address);
      }
    }
  });
}



async function add_evento() {
  disableSubmit();

  Swal.fire({
    title: 'Guardando...',
    html: 'Por favor, espera mientras procesamos tu solicitud.',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  let ev_latitud = $('#ev_latitud').val();
  let ev_longitud = $('#ev_longitud').val();

  var ev_desc = $('#descripcion').val();
  var ev_est = 1;

  var ev_inicio = new Date();
  var anio = ev_inicio.getFullYear();
  var mes = ev_inicio.getMonth() + 1;
  var dia = ev_inicio.getDate();
  var horas = ev_inicio.getHours();
  var minutos = ev_inicio.getMinutes();
  var segundos = ev_inicio.getSeconds();
  var fechaFormateada = anio + '-' + mes + '-' + dia + ' ' + horas + ':' + minutos + ':' + segundos;
  ev_inicio = fechaFormateada;

  var cat_id = $('#cat_id').val();
  var ev_direc = $('#address').val();
  var valorUbicacion = $("input[name='ubicacion']:checked").val();

  if (valorUbicacion === 'permitir' || valorUbicacion === 'permitirActual') {
    if ($('#address').val() === "") {
      try {
        ev_direc = await obtenerDireccionDesdeCoordenadas(ev_latitud, ev_longitud);
      } catch (error) {
        ev_direc = "Sin dirección";
      }
    }
  } else if (valorUbicacion === 'no') {
    ev_latitud = "null";
    ev_longitud = "null";
    ev_direc = $('#address').val();
  }

  var ev_telefono = $('#telefono').val();
  if (ev_telefono === "") {
    ev_telefono = "-";
  }

  // Crear FormData para incluir todos los datos y archivos
  var formData = new FormData();
  formData.append('ev_desc', ev_desc);
  formData.append('ev_est', ev_est);
  formData.append('ev_inicio', ev_inicio);
  formData.append('ev_direc', ev_direc);
  formData.append('cat_id', cat_id);
  formData.append('ev_niv', 1);
  formData.append('ev_latitud', ev_latitud);
  formData.append('ev_longitud', ev_longitud);
  formData.append('ev_telefono', ev_telefono);

  // Agregar la imagen si existe
  var files = $('#imagen')[0].files[0];
  if (files) {
    formData.append('imagen', files);
  }

  $.ajax({
    url: "../../controller/evento.php?op=add_evento",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer contentType
    success: function(response) {

      let data;
      try {
        data = typeof response === 'string' ? JSON.parse(response) : response;
      } catch (e) {
        console.error('Error al parsear JSON:', e);
        swal("Error", "Respuesta del servidor no es un JSON válido", "error");
        return;
      }


      enableSubmit();
      Swal.close();
      if (data.status === 'success') {
        swal("Éxito", data.message, "success")
          formulario = document.getElementById("event_form");
          formulario.reset();
      } else if (data.status === 'error') {
        swal("Error", data.message, "error");
      } else {
        swal("Error", "Respuesta del servidor no contiene el campo 'status'", "error");
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud de agregar evento: ", textStatus, errorThrown);
      swal("Error al agregar evento", "No se pudo agregar el evento.", "error");
      Swal.close();
    }
  });
}

function disableSubmit() {
  $('#btnGuardar').prop('disabled', true);
  let spiner = `<div class="spinner-border text-secondary" role="status">
                  <span class="sr-only"></span>
                </div>
                <span>Enviando...</span>
              `;

  document.getElementById("btnGuardar").innerHTML = spiner;
}

function enableSubmit() {
  $('#btnGuardar').prop('disabled', false);
  changedtextsubimt("AGREGAR NUEVA EMERGENCIA");
}

function changedtextsubimt(text){
  $('#btnGuardar').text(text)
}
