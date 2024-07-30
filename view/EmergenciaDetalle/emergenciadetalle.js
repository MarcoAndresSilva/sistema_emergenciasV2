function init() {
    // Inicialización de funciones o variables si es necesario
}

$(document).ready(function() {

    // Obtener el elemento <a> por su clase
    var enlace = document.querySelector('.HistorialEventos');
    // Añadir una clase al enlace
    enlace.classList.add('selected');

    var ev_id = getUrlParameter('ID');
    console.log(ev_id);

    listarDetalle(ev_id);

    $("#ev_desc").summernote({
        lang: "es-ES",
        height: 200,
    });
    
    $("#tic_descripUsu").summernote({
        lang: "es-ES",
        height: 100,
    });
    
    $("#tic_descripUsu").summernote("disable");

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
    $.post("../../controller/eventoDos.php?op=listar_detalle_emergencias", {ev_id : ev_id}, function(data) {
        // console.log(data);
        $('#lblDetalle').html(data);
    });

    $.post("../../controller/eventoDos.php?op=mostrar", {ev_id : ev_id}, function(data) {
        data = JSON.parse(data);
        console.log(data);
        $('#lblEstado').html(data.ev_est);
        $('#lblNomUsuario').html(data.usu_nom + ' ' + data.usu_ape);
        $('#lblFechaCrea').html(data.ev_inicio);
        $("#lblNomIdTicket").html("Trazabilidad Evento Emergencia N° ID: " + data.ev_id);

        ("#cat_nom").val(data.cat_nom);
        $("#unidadAsignada").val(data.unid_nom);
        $("#tic_descripUsu").summernote("code", data.ev_desc);
      console.log(data.tic_descrip);

      
    });


}

init();
