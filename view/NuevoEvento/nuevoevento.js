var defaultLocation = { lat: -33.7402, lng: -71.2060 }; // Coordenadas de Melipilla
var mapContainer = document.getElementById('map');
var map;
var marker;
var currentLat; // Variable para almacenar la latitud actual
var currentLng; // Variable para almacenar la longitud actual

function initMap() {
  map = new google.maps.Map(mapContainer, {
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
    currentLat = event.latLng.lat(); // Actualizar latitud
    currentLng = event.latLng.lng(); // Actualizar longitud
    console.log('Nuevas coordenadas: ' + currentLat + ',' + currentLng);
    // Actualizar los campos ocultos de latitud y longitud
    $('#ev_latitud').val(currentLat);
    $('#ev_longitud').val(currentLng);
    // Actualizar el campo de dirección
    actualizarDireccion(currentLat, currentLng);
  });
}

function obtenerUbicacionUsuario() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var userLocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };

      map.setCenter(userLocation);
      marker.setPosition(userLocation);
      currentLat = userLocation.lat;
      currentLng = userLocation.lng;
      // Actualizar los campos ocultos de latitud y longitud
      $('#ev_latitud').val(currentLat);
      $('#ev_longitud').val(currentLng);
      // Actualizar el campo de dirección
      actualizarDireccion(currentLat, currentLng);
    }, function(error) {
      console.error("Error al obtener la ubicación del usuario: ", error);
      swal("Error de Geolocalización", "No se pudo obtener la ubicación del usuario", "error");
    });
  } else {
    console.error('Error: El navegador no soporta geolocalización.');
  }
}

function initAutocomplete() {
  var input = document.getElementById('address');
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.setFields(['address_component', 'geometry']);

  autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();

    if (!place.geometry) {
      console.error("No se pudo obtener la información de la dirección.");
      return;
    }

    var formattedAddress = place.formatted_address;
    if ($('#address').val() !== formattedAddress) {
      $('#address').val(formattedAddress);
    }
  });
}

function cargarCategorias() {
  $.get("../../controller/categoria.php?op=combo", function(data) {
    $('#cat_id').html(data);
  }).fail(function(jqXHR, textStatus, errorThrown) {
    console.error("Error en la solicitud de categorías: ", textStatus, errorThrown);
    swal("Error al cargar categorías", "No se pudo cargar la lista de categorías.", "error");
  });
}

$(document).ready(function() {
  initMap();
  initAutocomplete();
  cargarCategorias();
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

  document.querySelectorAll('input[name="ubicacion"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
      toggleMap();

      if (this.value === 'permitir' || this.value === 'permitirActual') {
        obtenerUbicacionUsuario();
      }
    });
  });

  $('#btnGuardar').off('click').on('click', function() {
    if (validarFormulario()) {
      var direccion = $('#address').val();
      var latitud = currentLat; // Usar la variable de latitud actual
      var longitud = currentLng; // Usar la variable de longitud actual
      $('#ev_latitud').val(latitud);
      $('#ev_longitud').val(longitud);
      add_evento();
    }
  });

  document.getElementById('btnCargarArchivo').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('imagen').click();
  });

  document.getElementById('imagen').addEventListener('change', function() {
    var label = document.getElementById('archivoAdjuntado');
    if (this.files && this.files.length > 0) {
      label.textContent = this.files[0].name;
    } else {
      label.textContent = 'No hay archivo adjunto (.JPG/.JPEG/.PNG)';
    }
  });
});

function toggleMap() {
  var permitirUbicacion = $('input[name="ubicacion"]:checked').val() === 'permitir' || $('input[name="ubicacion"]:checked').val() === 'permitirActual';
  mapContainer.style.display = permitirUbicacion ? 'block' : 'none';
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

async function actualizarDireccion(lat, lng) {
  try {
    const direccion = await obtenerDireccionDesdeCoordenadas(lat, lng);
    $('#address').val(direccion);
  } catch (error) {
    console.error(error);
    $('#address').val('Sin dirección');
  }
}



async function add_evento() {
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


      if (data.status === 'success') {
        swal("Éxito", data.message, "success").then(() => {
          window.location.reload();
        });
      } else if (data.status === 'error') {
        swal("Error", data.message, "error");
      } else {
        swal("Error", "Respuesta del servidor no contiene el campo 'status'", "error");
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud de agregar evento: ", textStatus, errorThrown);
      swal("Error al agregar evento", "No se pudo agregar el evento.", "error");
    }
  });
}
