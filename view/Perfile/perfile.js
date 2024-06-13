function fetchData(op, postData, sendAsJson = false) {
    // URL del controlador
    const url = '../../controller/usuario.php';

    // Construir la URL con los parámetros GET
    const params = new URLSearchParams({
        op: op,
    });

    // Agregar los parámetros GET a la URL del controlador
    const fetchUrl = `${url}?${params}`;

    // Convertir el objeto postData a formato x-www-form-urlencoded o JSON
    let formData;
    let contentType;
    if (sendAsJson) {
        formData = JSON.stringify(postData);
        contentType = 'application/json';
    } else {
        formData = new URLSearchParams(postData).toString();
        contentType = 'application/x-www-form-urlencoded';
    }

    // Configurar la solicitud FETCH
    const requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': contentType, // Tipo de contenido del cuerpo de la solicitud
        },
        body: formData, // Usar formData en lugar de JSON
    };

    // Mostrar un mensaje de carga
    Swal.fire({
        title: 'Cargando...',
        allowEscapeKey: false,
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    });

    // Realizar la solicitud FETCH
    return fetch(fetchUrl, requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud.');
            }
            return response.json(); // Convertir la respuesta a formato JSON
        })
        .then(data => {
            // Cerrar el mensaje de carga
            Swal.close();

            // Mostrar un mensaje de alerta según el estado de la respuesta
            if (data.status === 'success') {
                Swal.fire('Éxito', data.message, 'success');
            } else if(data.status === 'error'){
                Swal.fire('Error', data.message, 'error');
            }else if(data.status === 'warning'){
                Swal.fire('Cuidado', data.message, 'warning');
            }else if(data.status === 'info'){
                Swal.fire('Informacion', data.message, 'info');
            }

            return data; // Devolver la respuesta del servidor
        })
        .catch(error => {
            // Cerrar el mensaje de carga
            Swal.close();

            // Mostrar un mensaje de error
            Swal.fire('Error', 'Error al realizar la consulta.', 'error');

            console.error('Error al realizar la consulta:', error);
        });
}

document.getElementById('updatePasswordForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Recoger los datos del formulario
    const old_pass = document.getElementById('old_pass').value;
    const new_pass = document.getElementById('new_pass').value;
    const confirm_new_pass = document.getElementById('confirm_new_pass').value;

    // Verificar si las contraseñas coinciden
    if (new_pass !== confirm_new_pass) {
        Swal.fire({
            title: 'Cuidado',
            text: "Las contraseñas no coinciden",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Mostrar un mensaje de confirmación
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Estás seguro de que quieres cambiar tu contraseña?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar contraseña'
    }).then((result) => {
        if (result.isConfirmed) {
            // Llamar a la función fetchData
            fetchData('update_password', { old_pass: old_pass, new_pass: new_pass});
        }
    });
});

$.validator.addMethod("passwordCheck",
    function(value, element) {
        return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/.test(value);
    },
    "La contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número"
);

$(document).ready(function() {
    $("#updatePasswordForm").validate({
        rules: {
            old_pass: {
                required: true,
            },
            new_pass: {
                required: true,
                minlength: 8,
                passwordCheck: true
            },
            confirm_new_pass: {
                required: true,
                minlength: 8,
                equalTo: "#new_pass",
                passwordCheck: true
            }
        },
        messages: {
            old_pass: {
                required: "Por favor, introduce tu contraseña antigua",
            },
            new_pass: {
                required: "Por favor, introduce tu nueva contraseña",
                minlength: "Tu contraseña debe tener al menos 8 caracteres",
                passwordCheck: "La contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número"
            },
            confirm_new_pass: {
                required: "Por favor, confirma tu nueva contraseña",
                minlength: "Tu contraseña debe tener al menos 8 caracteres",
                equalTo: "Las contraseñas no coinciden",
                passwordCheck: "La contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número"
            }
        }
    });
});
function showPassword(id) {
    var x = document.getElementById(id);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
document.getElementById('updatePhoneForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Recoger los datos del formulario
    const new_phone = document.getElementById('new_phone').value;

    // Mostrar un mensaje de confirmación
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Estás seguro de que quieres cambiar tu número de teléfono?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar número de teléfono'
    }).then((result) => {
        if (result.isConfirmed) {
            // Llamar a la función fetchData
            fetchData('update_phone', { new_phone: new_phone});
        }
    });
});
