$(document).ready(function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.Unidad-Municipal');

    // Añadir una clase al enlace
    enlace.classList.add('selected');

    //Funcion para cargar los datos de las tablas

    $.post("../../controller/evento.php?op=tabla-general",function(data,status){
        $('#datos-generales').html(data);
    });
    
});
