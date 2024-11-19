// Función para obtener el id del evento y mostrarlo en el label del segundo modal
function mostrarIdEventoNivelPeligro(ev_id) {
  $('#nivelpeligro_ev_id').text(ev_id);  // Asegúrate de que el ID es correcto en el HTML
}

// Función para mostrar el cat_nom en el label del segundo modal
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


function consultarNivelPeligro(id_evento) {

  //Realiza la recopilación y añade las unidades al select
  $.post("../../controller/nivelPeligro.php?op=get_nivel_peligro",function(data,status){
    // Limpiar el contenido actual del select
    $('#niv_id').empty();

    $('#niv_id').html(data);
  });

  // Obtener unidades asignadas al evento
  $.post("../../controller/evento.php?op=get_evento_id", { ev_id: id_evento }, function(asignadas, status) {

    // Marcar como seleccionado el nivel de peligro del evento
    if (typeof asignadas[0]['ev_niv'] !== 'undefined') {

      //Obtener el id del nivel de peligro que esta en el evento
      var nivelPeligroEvento = asignadas[0]['ev_niv'];

      // Seleccionar el nivel de peligro en el select
      $('#niv_id').val(nivelPeligroEvento);

    }        
  }, 'json');
}


