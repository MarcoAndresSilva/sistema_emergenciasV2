$(document).ready(function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.ControlEventos');
    // Añadir una clase al enlace
    enlace.classList.add('selected');

    cargarTablaGeneral();
});

function cargarTablaGeneral() {
      //Funcion para cargar los datos de las tablas
      $.post("../../controller/evento.php?op=tabla-general",function(respuesta,status){

        // Parsear la respuesta JSON
        var data = JSON.parse(respuesta);
        
        $('#datos-criticos').html(data.critico);
        $('#datos-medios').html(data.medio);
        $('#datos-bajos').html(data.bajo);
        $('#datos-generales').html(data.comun);
    });
}

$(document).on("click", "#btnPanelDerivar", function(e) {
    console.log('Button Derivar clicked');
    mostrarModal('#modalDerivar');
});

$(document).on("click", "#btnPanelCerrar", function(e) {
    console.log('Button Cerrar clicked');
    mostrarModal('#modalCerrar');
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