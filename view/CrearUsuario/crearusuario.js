

$(document).ready(function() {
    
    //Funcion para cargar los datos de la tabla categoria
    $.post("../../controller/usuario.php?op=get_todos_usuarios",function(data,status){

        $('#usu_tipo').html(data);

    });

    
});


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//ID para realizar una correcta asignación de tipo de usuario al usuario
var usu_tipo;
var alerta = true;
//Activacion del boton guardar
$('#btnGuardar').off('click').on('click', function() {
    if (validarFormulario()) {
        // Llama a la función pasa add_evento 
        add_usuario();
        
        // insert_asignacion_unidades(ev_id, cat_id);

        if(alerta){
            swal(" Usuario Registrado", "Se creo el usuario de forma correcta", "success");
        }else{
            swal("Algo Salio Mal", "No se logro registrar al usuario", "error");
        }
    }
});

//Funcion para tomar datos y concretar funcion add_evento
function add_usuario(){

    var usu_nom =$('#nombre').val();
    var usu_ape = $('#apellido').val();
    var usu_correo = $('#mail').val();
    var usu_name = $('#usuario').val();
    var usu_pass = $('#contrasena').val();
    
    var estado = 1;
    
    //Fecha y Hora
    var ev_inicio = new Date();
    var año = ev_inicio.getFullYear();
    var mes = ev_inicio.getMonth() + 1; // Mes en JavaScript es 0-indexado, así que suma 1
    var dia = ev_inicio.getDate();
    var horas = ev_inicio.getHours();
    var minutos = ev_inicio.getMinutes();
    var segundos = ev_inicio.getSeconds();
    // Formatear la fecha y hora como desees
    var fechaFormateada = año + '-' + mes + '-' + dia + ' ' + horas + ':' + minutos + ':' + segundos;
    var fecha_crea = fechaFormateada;
    
    //Variable Categoría
    usu_tipo = $('#usu_tipo').val();
    
    $.post("../../controller/usuario.php?op=add_usuario",{usu_nom:usu_nom,usu_ape:usu_ape,usu_correo:usu_correo,usu_name:usu_name,usu_pass:usu_pass,fecha_crea:fecha_crea,estado:estado, usu_tipo:usu_tipo},function(data,status){
        if(data == 1){
            
            $('#nombre').val('');
            $('#mail').val('');
            $('#assignation').val('');
            $('#address').val('');
            $('#cat_id').val(1);
            
        }else {
            alerta == false;
        }
    });

    
    
    
   
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Validación del formulario
function validarFormulario() {
    return (
        validarCampoNombre('#nombre', 'Debes ingresar un nombre sin caracteres especiales') &&
        validarCampoNombre('#apellido', 'Debes ingresar un apellido sin caracteres especiales') &&
        validarEmail('#mail', 'Debes ingresar una dirección de correo electrónico válida.') &&
        validarCampoVacio('#usuario', 'Debes ingresar un nombre de usuario.') &&
        validarCampoVacio('#contrasena', 'Debes ingresar una contraseña.')
    );
}

function validarCampoNombre(selector, mensajeError) {
    var valor = $(selector).val().trim();
    
    // Expresión regular que permite letras, espacios y tildes
    var regexNombre = /^[A-Za-zÁ-Úá-ú\s]+$/;

    if (valor === "" || !regexNombre.test(valor)) {
        mostrarMensajeError(mensajeError);
        return false;
    }
    
    return true;
}
function validarCampoVacio(selector, mensajeError) {
    var valor = $(selector).val().trim();
    if (valor === "") {
        mostrarMensajeError(mensajeError);
        return false;
    }
    return true;
}
function validarCampoVacioDireccion(selector, mensajeError) {
    var valor = $(selector).val().trim();
    if (valor === "" & (coordsUser.lat === "" || coordsUser.lng === "")) {
        mostrarMensajeError(mensajeError);
        return false;
    }
    return true;
}

function validarEmail(selector, mensajeError) {
    var email = $(selector).val();
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        mostrarMensajeError(mensajeError);
        return false;
    }
    return true;
}

function mostrarMensajeError(mensaje) {
    // Aquí puedes mostrar el mensaje de error en algún elemento específico o en la consola del navegador.
    console.error(mensaje);
    swal( "Validación de formulario",mensaje, "error" ) ;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
