function init() {
    // Inicialización de funciones o variables si es necesario
}

$(document).ready(function() {

    var enlace = document.querySelector('.HistorialEventos');
    enlace.classList.add('selected');

    var ev_id = getUrlParameter('ID');
    console.log(ev_id);

    listarDetalle(ev_id);
    
    $("#tic_descripUsu").summernote({
        lang: "es-ES",
        height: 100,
    }); 
    
    $("#tic_descripUsu").summernote("disable");

    $("#ev_desc").summernote({
        lang: "es-ES",
        height: 200,
    });

});


var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split("&"),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split("=");

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function listarDetalle(ev_id){
    $.post("../../controller/emergenciaDetalle.php?op=listar_detalle_emergencias", {ev_id : ev_id}, function(data) {
        $('#lblDetalle').html(data);
    });

    $.post("../../controller/emergenciaDetalle.php?op=mostrar", {ev_id : ev_id}, function(data) {
        data = JSON.parse(data);
        console.log(data);
        $("#lblNomIdTicket").html("Trazabilidad Evento Emergencia N° ID: " + data.ev_id);
        $('#lblEstado').html(data.ev_est);
        
        $('#lblNomUsuario').html(data.usu_nom + ' ' + data.usu_ape);
        $('#lblFechaCrea').html(data.ev_inicio);

        $("#cat_nom").val(data.cat_nom);
        $("#ev_direc").val(data.ev_direc);
        $("#tic_descripUsu").summernote("code", data.ev_desc); 

        // Verifica si el evento está cerrado
        if (data.ev_est == 2 || data.ev_final) {
            // Deshabilita el campo Summernote
            $("#ev_desc").summernote("disable");

            // Oculta los botones
            $("#btnEnviar").hide();
            $("#btnPanelCerrar").hide();
        } else {
            // Habilita el campo Summernote en caso de que el evento esté abierto
            $("#ev_desc").summernote("enable");

            // Muestra los botones
            $("#btnEnviar").show();
            $("#btnPanelCerrar").show();
        }

    });
}

$(document).on("click", "#btnEnviar", function () {
    console.log("test");
    var ev_id = getUrlParameter("ID");
    var usu_id = $("#user_idx").val();
    var ev_desc = $("#ev_desc").val();
  
    if ($("#ev_desc").summernote("isEmpty")) {
      swal("Advertencia!", "Ingresa una descripción", "warning");
    } else {
      $.post(
        "../../controller/emergenciaDetalle.php?op=insertdetalle",
        { ev_id: ev_id, usu_id: usu_id, ev_desc: ev_desc },
        function () {
          listarDetalle(ev_id);
          swal("Correcto!", "Resgistro actualizado correctamente", "success");
          $("#ev_desc").summernote("reset");
        }
      );
    }
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


  $(document).on("click", "#btnPanelCerrar", function(e) {
    console.log('Button Cerrar clicked');
    mostrarModal('#modalCerrar');

    // Obtener el valor del ID del evento desde la url
    var id_evento = getUrlParameter('ID');

    // Llama a la función para consultar la categoría y otros detalles
    consultarCategoriaCierre(id_evento);
    mostrarCatIdEventoCierre(id_evento);
});

// Función para obtener el cat_id del evento y mostrarlo en el div cat_id
function mostrarCatIdEventoCierre(ev_id) {
    // $('#ev_id_cierre').text(ev_id);
    ev_id = getUrlParameter('ID');
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

// Mapeo de motivos de cierre según la categoría

var motivosCierre = {}; // Variable para almacenar los datos agrupados por categoría

function fillAndGroupByCategory() {
  var agrupados = {};

  // Realizar la solicitud POST
  $.post(
    "../../controller/cierreMotivo.php?op=get_cierre_motivo_categoria",
    {},
    function(data) {
      // Parsear la respuesta a JSON
      var response = JSON.parse(data);

      // Verificar si la respuesta es un arreglo
      if (Array.isArray(response)) {
        response.forEach(function(item) {
          var categoria = item.categoria;
          var motivo = { id: item.mov_id, nombre: item.motivo }; // Modificar la estructura del motivo

          if (!agrupados[categoria]) {
            agrupados[categoria] = [];
          }
          agrupados[categoria].push(motivo);
        });
        console.log(agrupados);
        // Asignar los datos agrupados a motivosCierre
        motivosCierre = agrupados;
      } else {
        console.error("La respuesta no es un arreglo.");
      }
    }
  );
}

// Llamar a la función para llenar y agrupar los datos por categoría
fillAndGroupByCategory();

// Función para cargar los motivos de cierre en el select
function cargarMotivosCierre(categoria) {
    var motivos = motivosCierre[categoria] || [];
    var $select = $('#motivo_cierre');
    $select.empty();

    motivos.forEach(function(motivo) {
        $select.append($('<option>', { value: motivo.id, text: motivo.nombre }));
    });
}

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


//Btn Cerrar evento (Añade hora cierre)

$('.btnCerrarEvento').off('click').on('click',function(){

    
    if(validarFormulario()){
         //Llama a la funcion cerrar evento Añade la hora final
    CerrarEvento();
          
      // Mostrar mensaje de éxito
    swal({
        title: "Evento Cerrado",
        text: "El evento ha sido cerrado con éxito.",
        icon: "warning",
        button: "Aceptar",
        closeOnClickOutside: false,
        closeOnEsc: false
    });
    $('#modalCerrar').modal('hide');
    // cargarTablaGeneral();
    }
});

//Validación del formulario
function validarFormulario() { 
    return (
        validarCampoVacio('#detalle_cierre', 'Debes ingresar un detalle para cerrar el evento.')
    );
}

function validarCampoVacio(selector, mensajeError) {
    var valor = $(selector).val().trim();
    if (valor === "") {
        mostrarMensajeError(mensajeError);
        return false;
    }
    return true;
}
function mostrarMensajeError(mensaje) {
    // Aquí puedes mostrar el mensaje de error en algún elemento específico o en la consola del navegador.
    console.error(mensaje);
    swal( "Validación de formulario",mensaje, "warning" ) ;
}

function CerrarEvento() {
    // var ev_id = $('#ev_id_cierre').text();
    ev_id = getUrlParameter('ID');
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
        // Después de cerrar el evento con éxito, cargar la imagen
        var formData = new FormData($('#event_form')[0]);
        formData.append('ev_id', ev_id);
        $.ajax({
            url: '../../controller/evento.php?op=carga-imagen-cierre',
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
        if(data == 1) {
            swal("Evento Finalizado", "El evento se ha cerrado correctamente", "success");
        } else {
            swal("No Finalizado", "El evento no se ha podido cerrar correctamente", "error");
        }
    });
}
init();
