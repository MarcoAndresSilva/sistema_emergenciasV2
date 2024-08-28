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

// Función para cargar las reglas de validación y actualizar información del usuario
function loadValidationAndUserInfo() {
    // Cargar y aplicar reglas de validación dinámicas
    fetchCriteriosSeguridad();

    // Actualizar información del usuario
    fetchUserInfo();
}

// Función para obtener y aplicar los criterios de seguridad dinámicos
function fetchCriteriosSeguridad() {
    fetch('../../controller/usuario.php?op=get_criterios_seguridad')
    .then(response => response.json())
    .then(data => {
        // Verificar que los datos sean válidos y aplicar las reglas de validación
        if (data && typeof data === 'object') {
            // Definir reglas de validación basadas en los criterios recibidos
            let rules = {
                minlength: data.largo || 8 // Longitud mínima
            };

            // Agregar reglas de validación según los criterios dinámicos
            if (data.mayuscula !== undefined && data.mayuscula) {
                $.validator.addMethod("requireMayuscula",
                    function(value, element) {
                        return this.optional(element) || /[A-Z]+/.test(value);
                    },
                    "Falta al menos una letra mayúscula."
                );
                rules.requireMayuscula = true;
            }

            if (data.minuscula !== undefined && data.minuscula) {
                $.validator.addMethod("requireMinuscula",
                    function(value, element) {
                        return this.optional(element) || /[a-z]+/.test(value);
                    },
                    "Falta al menos una letra minúscula."
                );
                rules.requireMinuscula = true;
            }

            if (data.numero !== undefined && data.numero) {
                $.validator.addMethod("requireNumero",
                    function(value, element) {
                        return this.optional(element) || /\d+/.test(value);
                    },
                    "Falta al menos un número."
                );
                rules.requireNumero = true;
            }

            if (data.especiales !== undefined && data.especiales) {
                $.validator.addMethod("requireEspecial",
                    function(value, element) {
                        return this.optional(element) || /[^A-Za-z0-9]+/.test(value);
                    },
                    "Falta al menos un caracter especial."
                );
                rules.requireEspecial = true;
            }

            // Agregar reglas de validación al formulario de actualización de contraseña
            $('#updatePasswordForm').validate({
                rules: {
                    new_pass: rules,
                    confirm_new_pass: {
                        equalTo: '#new_pass'
                    }
                },
                messages: {
                    new_pass: {
                        minlength: "La contraseña debe tener al menos {0} caracteres.",
                        requireMayuscula: "Falta al menos una letra mayúscula.",
                        requireMinuscula: "Falta al menos una letra minúscula.",
                        requireNumero: "Falta al menos un número.",
                        requireEspecial: "Falta al menos un caracter especial."
                    },
                    confirm_new_pass: {
                        equalTo: "Las contraseñas no coinciden."
                    }
                },
                errorPlacement: function(error, element) {
                    // Colocar el mensaje de error junto al elemento
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    // Procesar el envío del formulario después de la validación
                }
            });
        }
    })
    .catch(error => {
        console.error('Error al obtener los criterios de seguridad:', error);
        // Manejar el error, por ejemplo mostrar un mensaje al usuario
    });
}

// Función para obtener y mostrar la información del usuario
function fetchUserInfo() {
    fetch('../../controller/usuario.php?op=get_info_usuario')
        .then(response => response.json())
        .then(data => {
            let userInfo = '';
            if (data.status === 'success') {
                for (let key in data.result) {
                    if (key !== 'status' && key !== 'message') {
                        userInfo += `
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${key}</h5>
                                        <p class="card-text">${data.result[key]}</p>
                                    </div>
                                </div>
                            </div>`;
                    }
                }
            } else {
                userInfo = `<div class="alert alert-danger" role="alert">${data.message}</div>`;
            }
            document.getElementById('userInfo').innerHTML = userInfo;
        })
        .catch(error => {
            console.error('Error al obtener los datos del usuario:', error);
            document.getElementById('userInfo').innerHTML = `<div class="alert alert-danger" role="alert">Error al obtener los datos del usuario.</div>`;
        });
}

// Llama a la función para cargar reglas de validación y actualizar información del usuario al cargar la página
$(document).ready(function() {
    loadValidationAndUserInfo();

    // Llama a la función cada 5 segundos para actualizar la información del usuario en tiempo real
    setInterval(function() {
        fetchUserInfo();
    }, 5000);
});

