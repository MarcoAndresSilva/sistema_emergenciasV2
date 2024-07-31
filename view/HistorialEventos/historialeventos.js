$(document).ready(function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.HistorialEventos');
    // Añadir una clase al enlace
    enlace.classList.add('selected');

    cargarTablaGeneral();
});

function cargarTablaGeneral() {
    // Funcion para cargar los datos de las tablas
    $.post("../../controller/evento.php?op=tabla-historial-eventos", function(respuesta, status) {
        // Parsear la respuesta JSON
        var data = JSON.parse(respuesta);
        
        $('#datos-criticos').html(data.critico);
        $('#datos-medios').html(data.medio);
        $('#datos-bajos').html(data.bajo);
        $('#datos-generales').html(data.comun);

        // Agregar el evento click a los botones de ver detalle
        $('.btnDetalleEmergencia').click(function() {
            var ev_id = $(this).data('ev-id');
            ver(ev_id);
        });
    });
}

function ver(ev_id) {
    // Abrir una nueva pestaña con la ruta especificada
    window.open(`../EmergenciaDetalle?ID=${ev_id}`, '_blank');
}

