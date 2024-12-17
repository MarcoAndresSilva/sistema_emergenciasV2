function mostrarIdEventoNivelPeligro(ev_id) {
  $('#nivelpeligro_ev_id').text(ev_id);  
}

function consultarCategoriaNivelPeligro(ev_id) {
  $.post("../../controller/categoria.php?op=get_cat_nom_by_ev_id", { ev_id: ev_id }, function(data, status) {
    try {
      var jsonData = JSON.parse(data);
      if (jsonData && jsonData.cat_nom) {
        $('#nivelpeligro_cat_nombre').text(jsonData.cat_nom); // Asegúrate de que el ID es correcto en el HTML
      } else {
        console.log("No se encontró el cat_nom correspondiente para el evento con ID: " + ev_id);
      }
    } catch (error) {
      console.log("Error al analizar la respuesta JSON:", error);
    }
  });
}

function consultarNivelesDisponibles(callback) {
    $.post("../../controller/nivelPeligro.php?op=get_nivel_peligro_json", function(data) {
        try {
            const niveles = JSON.parse(data);
            callback(niveles);
        } catch (error) {
            console.error("Error parsing niveles disponibles:", error);
        }
    });
}


function cargarDatosNivelPeligro(id_evento) {
    $.post("../../controller/evento.php?op=informacion_evento_completo", { id_evento: id_evento }, function(response) {
        if (response.status === "success") {
            consultarNivelesDisponibles(function(nivelesDisponibles) {
                const $select = $('#niv_id');
                $select.empty(); // Limpia las opciones previas

                nivelesDisponibles.forEach(nivel => {
                    $select.append(`<option value="${nivel.ev_niv_id}">${nivel.ev_niv_nom}</option>`);
                });

                // Agrega comportamiento para que siempre despliegue hacia abajo
                $select.off('focus').on('focus', function () {
                    $(this).css('overflow-y', 'scroll');
                });
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message,
            });
        }
    }, 'json');
}



function actualizarNivelPeligro(id_evento, nuevoNivel) {
    if (nuevoNivel) {
        $.post("../../controller/evento.php?op=update_nivelpeligro_evento", 
            { id_evento: id_evento, id_nivel: nuevoNivel }, 
            function(respuesta) {
                if (respuesta.status === "success") {
                    $('#nivel_actual').text($('#niv_id option:selected').text()); 
                    Swal.fire({
                        icon: 'success',
                        title: 'Nivel de peligro actualizado',
                        text: respuesta.message,
                    });
                    $('#modalNivelPeligro').modal('hide');
                } else if (respuesta.status === "info") {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin cambios',
                        text: 'El nivel de peligro seleccionado ya es el mismo.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta.message,
                    });
                }
            }, 
            'json'
        );
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Selección inválida',
            text: 'Por favor, seleccione un nivel de peligrosidad válido.',
        });
    }
}



if (!window.eventoNivelPeligroInicializado) {
    window.eventoNivelPeligroInicializado = true;
    $(document).on("click", ".btnActualizarNivelPeligro", function (event) {
        event.preventDefault();
        console.log("Button Nivel Peligro clicked");
        const id_evento_peligro = $('#nivelpeligro_ev_id').text();
        const nuevoNivelSeleccionado = $('#niv_id').val();
        actualizarNivelPeligro(id_evento_peligro, nuevoNivelSeleccionado);
        recargar(id_evento_peligro);
    });
}

