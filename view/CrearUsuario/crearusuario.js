$(document).ready(function() {
    // Función para cargar los datos de la tabla categoría
    $.post("../../controller/usuario.php?op=get_todos_usuarios", function(data) {
        $('#usu_tipo').html(data);
    });

    
$.post("../../controller/unidad.php?unidad=listar", function(data) {
    const unidades = JSON.parse(data);
    const selectElement = document.getElementById("usu_unidad");

    unidades.forEach((unidad) => {
        const option = document.createElement("option");
        option.value = unidad.unid_id;
        option.textContent = unidad.unid_nom;
        selectElement.appendChild(option);
    });
});

});

$('#btnGuardar').off('click').on('click', function() {
    if (validarFormulario()) {
        add_usuario();
    }
});

$('#usu_unidad').on('change', function() {
    const unidadId = $(this).val();
    data = {
        unidad: unidadId
    };
    $('#usu_seccion').html('');
    abilitarSelectSeccion();
  $.post("../../controller/seccion.php?op=get_secciones", data, function(data) {
        const secciones = data;
        const selectElement = document.getElementById("usu_seccion");
        console.log( "secciones", secciones);
        if (secciones.status === 'warning') {
            $('#usu_seccion').prop('disabled', true);
            swal("Cuidado", "Pida al administrador que cree una seccion para esta unidad", "warning");
            return;
        }
        selectElement.innerHTML = "<option value=''>-------------</option>";
        secciones.forEach((seccion) => {
            const option = document.createElement("option");
            option.value = seccion.sec_id;
            option.textContent = seccion.sec_nombre;
            selectElement.appendChild(option);
        });
    });
})

function abilitarSelectSeccion(){
  $('#usu_seccion').prop('disabled', false);
}

function add_usuario() {
    const usuarioData = {
        usu_nom: $('#nombre').val(),
        usu_ape: $('#apellido').val(),
        usu_correo: $('#mail').val(),
        usu_name: $('#usuario').val(),
        usu_pass: $('#contrasena').val(),
        usu_telefono: $('#telefono').val(),
        usu_seccion: $('#usu_seccion').val(),
        estado: 1,
        usu_unidad: $('#usu_unidad').val(),
        fecha_crea: getFormattedDate(),
        usu_tipo: $('#usu_tipo').val()
    };

    fetchData('add_usuario', usuarioData).then(data => {
        if (data.status === 'success') {
            limpiarFormulario();
        }
    });
}

function getFormattedDate() {
    const ev_inicio = new Date();
    const año = ev_inicio.getFullYear();
    const mes = (ev_inicio.getMonth() + 1).toString().padStart(2, '0');
    const dia = ev_inicio.getDate().toString().padStart(2, '0');
    const horas = ev_inicio.getHours().toString().padStart(2, '0');
    const minutos = ev_inicio.getMinutes().toString().padStart(2, '0');
    const segundos = ev_inicio.getSeconds().toString().padStart(2, '0');
    return `${año}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
}

function limpiarFormulario() {
    $('#nombre').val('');
    $('#apellido').val('');
    $('#mail').val('');
    $('#usuario').val('');
    $('#contrasena').val('');
    $('#telefono').val('');
    $('#usu_tipo').val(1);
}

function validarFormulario() {
    const campos = [
        { selector: '#nombre', mensaje: 'Debes ingresar un nombre sin caracteres especiales', validacion: validarNombre },
        { selector: '#apellido', mensaje: 'Debes ingresar un apellido sin caracteres especiales', validacion: validarNombre },
        { selector: '#mail', mensaje: 'Debes ingresar una dirección de correo electrónico válida', validacion: validarEmail },
        { selector: '#usuario', mensaje: 'Debes ingresar un nombre de usuario', validacion: validarCampoVacio },
        { selector: '#contrasena', mensaje: 'Debes ingresar una contraseña', validacion: validarCampoVacio },
        { selector: '#usu_unidad', mensaje: 'Debes ingresar una unidad', validacion: validarCampoVacio },
        { selector: '#usu_seccion', mensaje: 'Debes seleccionar una sección', validacion: validarCampoVacio }
    ];

    return campos.every(campo => {
        const valor = $(campo.selector).val().trim();
        if (!campo.validacion(valor)) {
            mostrarMensajeError(campo.mensaje);
            return false;
        }
        return true;
    });
}

function validarNombre(nombre) {
    const regexNombre = /^[A-Za-zÁ-Úá-ú\s]+$/;
    return regexNombre.test(nombre);
}

function validarCampoVacio(valor) {
    return valor !== "";
}

function validarEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function mostrarMensajeError(mensaje) {
    console.error(mensaje);
    swal("Validación de formulario", mensaje, "error");
}

function fetchData(op, postData, sendAsJson = false) {
    const url = '../../controller/usuario.php';
    const params = new URLSearchParams({ op });
    const fetchUrl = `${url}?${params}`;

    let formData;
    let contentType;
    if (sendAsJson) {
        formData = JSON.stringify(postData);
        contentType = 'application/json';
    } else {
        formData = new URLSearchParams(postData).toString();
        contentType = 'application/x-www-form-urlencoded';
    }

    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': contentType },
        body: formData
    };

    Swal.fire({
        title: 'Cargando...',
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    return fetch(fetchUrl, requestOptions)
        .then(response => {
            if (!response.ok) throw new Error('Error en la solicitud.');
            return response.json();
        })
        .then(data => {
            Swal.close();
            const alertType = data.status === 'success' ? 'success' :
                              data.status === 'error' ? 'error' :
                              data.status === 'warning' ? 'warning' : 'info';
            Swal.fire(data.status.charAt(0).toUpperCase() + data.status.slice(1), data.message, alertType);
            return data;
        })
        .catch(error => {
            Swal.close();
            Swal.fire('Error', 'Error al realizar la consulta.', 'error');
            console.error('Error al realizar la consulta:', error);
        });
}
