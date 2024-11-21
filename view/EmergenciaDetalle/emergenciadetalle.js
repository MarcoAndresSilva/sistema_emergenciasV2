function init() {
    // Inicialización de funciones o variables si es necesario
}

$(document).ready(function() {

    var enlace = document.querySelector('.ControlEventos');
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

function listarDetalle(ev_id) {
    // Mostrar los detalles del evento
    $.post("../../controller/emergenciaDetalle.php?op=listar_detalle_emergencias", {ev_id: ev_id}, function(data) {
        $('#lblDetalle').html(data);
    });

    // Obtener los datos del evento, incluyendo las unidades asignadas
    $.post("../../controller/emergenciaDetalle.php?op=mostrar", {ev_id: ev_id}, function(data) {
        data = JSON.parse(data);
        console.log(data);
        $("#lblNomIdTicket").html("Trazabilidad Evento Emergencia N° ID: " + data.ev_id);
        $('#lblEstado').html(data.ev_est);
        $('#lblNomUsuario').html(data.usu_nom + ' ' + data.usu_ape);
        $('#lblFechaCrea').html(data.ev_inicio);
        $("#cat_nom").val(data.cat_nom);
        $("#ev_direc").val(data.ev_direc);
        $("#tic_descripUsu").summernote("code", data.ev_desc);

        // Mostrar las unidades asignadas en la lista de participantes
        const listaParticipantes = $("#listaParticipantes");
        listaParticipantes.empty(); // Limpiar lista antes de agregar nuevas unidades
        let usuarioTieneUnidad = false; // Verificar si la unidad del usuario está en la lista de participantes
        if (data.unidades && data.unidades.length > 0) {
            data.unidades.forEach(function(unidad) {
                listaParticipantes.append(`<li class="list-group-item">${unidad}</li>`);
                // Verifica si la unidad del usuario está en la lista
                if (unidad === data.usu_unidad_nom) {
                    usuarioTieneUnidad = true;
                }
            });
        } else {
            listaParticipantes.append(`<li class="list-group-item">No hay unidades asignadas</li>`);
        }

        // Condicional para verificar si el evento tiene fecha de finalización
        if (data.ev_final) {
            $("#ev_desc").summernote("disable");
            $("#btnEnviar").prop("disabled", true);
            $("#btnPanelCerrar").prop("disabled", true);
        } else {
            $("#ev_desc").summernote("enable");
            $("#btnEnviar").prop("disabled", false);
            $("#btnPanelCerrar").prop("disabled", false);
        }
    });
}
$(document).on("click", "#btnEnviar", function () {
    console.log("test");
    var ev_id = getUrlParameter("ID");
    var usu_id = $("#user_idx").val();
    var ev_desc = $("#ev_desc").val();
    var check_privado = $("#value_privado").is(':checked');
    var privado = check_privado ? 1 : 0;
  
    if ($("#ev_desc").summernote("isEmpty")) {
      swal("Advertencia!", "Ingresa una descripción", "warning");
    } else {
      $.post(
        "../../controller/emergenciaDetalle.php?op=insertdetalle",
        { ev_id: ev_id, usu_id: usu_id, ev_desc: ev_desc , privado: privado },
        function () {
          listarDetalle(ev_id);
          swal("Correcto!", "Resgistro actualizado correctamente", "success");
          $("#ev_desc").summernote("reset");
        }
      );
    }
  });



// Modal Cerrar
function mostrarModal(modalId) {
    var modal = $(modalId);
    if (modal.length) {
        modal.removeClass('fade');
        modal.modal('show');
    } else {
        console.error('Modal not found: ' + modalId);
    }
}

// REDERIVAR EVENTO
$(document).on("click", "#btnPanelDerivar", function(e) {
    console.log('Button Derivar clicked');
    mostrarModal('#modalDerivar');
    mostrarIdEvento(id_evento);
    consultarCategoria(id_evento);
    consultarNivelPeligro(id_evento);
    consultarUnidadDisponible(id_evento);
});

var id_evento = getUrlParameter('ID');

// CERRAR EVENTO
$(document).on("click", "#btnPanelCerrar", function(e) {
    console.log('Button Cerrar clicked');
    mostrarModal('#modalCerrar');
    $('#ev_id_cierre').text(id_evento);
    consultarCategoriaCierre(id_evento);
});


// Función para obtener y mostrar la categoría de cierre del evento
function consultarCategoriaCierre(ev_id) {
    $.post("../../controller/categoria.php?op=get_cat_nom_by_ev_id", { ev_id: ev_id }, function(data, status) {
        try {
            var jsonData = JSON.parse(data);
            if (jsonData && jsonData.cat_nom) {
                $('#cat_nom_cierre').text(jsonData.cat_nom);
                cargarMotivosCierre(jsonData.cat_nom); // Cargar los motivos de cierre según la categoría
            } else {
                console.log("No se encontró el cat_nom correspondiente para el evento con ID: " + ev_id);
            }
        } catch (error) {
            console.log("Error al analizar la respuesta JSON:", error);
        }
    });
}

// Inicialización y agrupación de motivos de cierre por categoría
var motivosCierre = {};

function fillAndGroupByCategory() {
    var agrupados = {};
    $.post("../../controller/cierreMotivo.php?op=get_cierre_motivo_categoria", {}, function(data) {
        var response = JSON.parse(data);
        if (Array.isArray(response)) {
            response.forEach(function(item) {
                var categoria = item.categoria;
                var motivo = { id: item.mov_id, nombre: item.motivo };
                if (!agrupados[categoria]) {
                    agrupados[categoria] = [];
                }
                agrupados[categoria].push(motivo);
            });
            motivosCierre = agrupados;
        } else {
            console.error("La respuesta no es un arreglo.");
        }
    });
}

// Llamada inicial para cargar las categorías
fillAndGroupByCategory();

// Función para cargar los motivos de cierre en el select
function cargarMotivosCierre(categoria) {
    var motivos = motivosCierre[categoria] || [];
    var $select = $('#motivo_cierre');
    $select.empty();
    if (motivos.length > 0) {
        motivos.forEach(function(motivo) {
            $select.append($('<option>', { value: motivo.id, text: motivo.nombre }));
        });
    }else if(motivos.length === 0){
        $select.append($('<option>', { value: 0, text: '------------------------------------------' }));
        $select.prop('disabled', true);
        $('#mensaje_error').show();
        $('#categoria_cierre').text(categoria);
    }
}

document.getElementById('imagen').addEventListener('change', function() {
    var label = document.getElementById('archivoAdjuntado');
    if (this.files && this.files.length > 0) {
        label.textContent = this.files[0].name;
    } else {
        label.textContent = 'No hay archivo adjunto (.JPG/.JPEG/.PNG)';
    }
});

// Botón para cerrar el evento
$('.btnCerrarEvento').off('click').on('click', function() {
    if (validarFormulario()) {
        CerrarEvento();
        swal({
            title: "Evento Cerrado",
            text: "El evento ha sido cerrado con éxito.",
            icon: "warning",
            button: "Aceptar",
            closeOnClickOutside: false,
            closeOnEsc: false
        });
        $('#modalCerrar').modal('hide');
        // cargarTablaGeneral(); // Comentar o actualizar según sea necesario
    }
});

// Validación del formulario antes de cerrar el evento
function validarFormulario() {
    return validarCampoVacio('#detalle_cierre', 'Debes ingresar un detalle para cerrar el evento.');
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
    console.error(mensaje);
    swal("Validación de formulario", mensaje, "warning");
}

// Función para cerrar el evento y enviar datos al backend
function CerrarEvento() {
    var ev_id = getUrlParameter('ID');
    var detalle_cierre = $('#detalle_cierre').val();
    var motivo_cierre = $('#motivo_cierre').val();
    var nombre_apellido = $('#nombre_apellido').val();

    // Fecha y Hora
    var ev_final = new Date();
    var fechaFormateada = ev_final.getFullYear() + '-' +
        (ev_final.getMonth() + 1) + '-' +
        ev_final.getDate() + ' ' +
        ev_final.getHours() + ':' +
        ev_final.getMinutes() + ':' +
        ev_final.getSeconds();

    // Estado del evento al cerrarse
    var ev_est = 2;

    // Crear un objeto FormData para enviar todos los datos, incluyendo la imagen
    var formData = new FormData();
    formData.append('ev_id', ev_id);
    formData.append('ev_final', fechaFormateada);
    formData.append('ev_est', ev_est);
    formData.append('detalle_cierre', detalle_cierre);
    formData.append('motivo_cierre', motivo_cierre);
    formData.append('nombre_apellido', nombre_apellido);

    // Capturar la imagen del campo #imagen
    var imagen = $('#imagen')[0].files[0];
    if (imagen) {
        formData.append('adjunto', imagen);
    }

    // Enviar la solicitud POST con todos los datos
    $.ajax({
        url: '../../controller/evento.php?op=cerrar_evento',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data == 1) {
                swal("Evento Finalizado", "El evento se ha cerrado correctamente", "success");
            } else {
                swal("No Finalizado", "El evento no se ha podido cerrar correctamente", "error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud: " + error);
        }
    });
}

// Inicialización general del script
function init() {
    fillAndGroupByCategory();
    // Otras inicializaciones que necesites
}

init();
