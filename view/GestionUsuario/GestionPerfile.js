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
document.addEventListener('DOMContentLoaded', () => {
    loadUserTypes();
    FnOpetenerUsuarios(); 
});

let tiposDeUsuarios = [];

// Función para cargar los tipos de usuarios una vez al cargar la página
function loadUserTypes() {
    fetch('../../controller/usuario.php?op=get_tipo_usuarios')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            tiposDeUsuarios = data;
        })
        .catch(error => {
            console.error('Error fetching user types:', error);
        });
}
function FnOpetenerUsuarios() {
    const url = '../../controller/usuario.php';
    const params = new URLSearchParams({ op: 'get_full_info_usuario' });
    const fetchUrl = `${url}?${params}`;

    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({})
        };

    fetch(fetchUrl, requestOptions)
       .then(response => response.ok ? response.json() : Promise.reject('Error en la solicitud.'))
       .then(data => {
           if (data.status === 'success') {
               renderTable(data.result);
           } else {
               Swal.fire('Error', data.message, 'error');
           }
           })
           .catch(error => {
                Swal.fire('Error', 'Error al realizar la consulta.', 'error');
                console.error('Error al obtener la información de los usuarios:', error);
           });
}

function renderTable(users) {
    const userInfo = document.getElementById('userInfo');
    userInfo.innerHTML = '';

    // Crear contenedores para filtros
    const filterContainer = document.createElement('div');
    filterContainer.className = 'mb-3';
    filterContainer.innerHTML = `
        <label for="filterEstado" class="ml-3">Filtrar por Estado:</label>
        <select id="filterEstado" class="form-select d-inline-block w-auto ml-2">
            <option value="">Todos</option>
            <option value="Desactivado">Desactivado</option>
            <option value="Activo">Activo</option>
        </select>
    `;
    userInfo.appendChild(filterContainer);

    const table = createTable(users);
    const tableContainer = document.createElement('div');
    tableContainer.className = 'table-container';
    tableContainer.appendChild(table);
    userInfo.appendChild(tableContainer);

    initializeDataTable();
}

function initializeDataTable() {
    setTimeout(() => {
        if ($.fn.dataTable.isDataTable('table')) {
            $('table').DataTable().destroy();
        }

        const responsive = window.innerWidth <= 1920;

        const dataTable = $('table').DataTable({
      language: { url: "../registrosLog/spanishDatatable.json"},
      responsive: responsive,
      columnDefs: [
        { targets: [0, 1,2], className: 'all' },
      ]
        });

        const userInfo = document.getElementById('userInfo');
        userInfo.addEventListener('click', function(event) {
            const target = event.target.closest('button');
            if (target) {
                if (target.matches('button.btn-primary')) {
                    const userId = target.dataset.userId;
                    editUser(userId);
                } else if (target.matches('button.btn-info')) {
                    const userId = target.dataset.userId;
                    ChangedPasswordUser(userId);
                } else if (target.matches('button.btn-sm')) {
                    const userId = target.dataset.userId;
                    const currentStatus = target.dataset.status === 'active' ? 1 : 0;
                    toggleUserStatus(userId, currentStatus);
                }
            }
        });

        const filterEstado = document.getElementById('filterEstado');
        filterEstado.addEventListener('change', function () {
            const val = this.value;
            dataTable.column(7).search(val ? `^${val}$` : '', true, false).draw();
        });
    }, 0);
}

function createTable(users) {
    const table = document.createElement('table');
    table.className = 'table table-striped';

    table.appendChild(createTableHeader());
    table.appendChild(createTableBody(users));

    return table;
}

function createTableHeader() {
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['ID', 'Nombre', 'Apellido', 'Tipo', 'Unidad' ,'Seccion','Teléfono','Estado' ,'Correo', 'Usuario', 'Cambiar Contraseña', 'Editar usuario', 'Cambiar estado'];

    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);
    return thead;
}

function createTableBody(users) {
    const tbody = document.createElement('tbody');

    users.forEach(user => {
        const row = document.createElement('tr');

        row.setAttribute('data-user-id', user.usu_id);
        row.appendChild(createTableCell(user.usu_id));
        row.appendChild(createTableCell(user.Nombre));
        row.appendChild(createTableCell(user.Apellido));
        row.appendChild(createTableCell(user.Tipo));
        row.appendChild(createTableCell(user.Unidad));
        row.appendChild(createTableCell(user.Seccion));
        row.appendChild(createTableCell(user.Telefono));
        row.appendChild(createStatusBadge(user.estado));
        row.appendChild(createTableCell(user.Correo));
        row.appendChild(createTableCell(user.Usuario));

        row.appendChild(createTableCell(createChangePasswordButton(user.usu_id)) || createTableCell(''));
        row.appendChild(createTableCell(createEditButton(user.usu_id)) || createTableCell(''));
        row.appendChild(createTableCell(createToggleStatusButton(user.usu_id, user.estado)) || createTableCell(''));

        tbody.appendChild(row);
    });

    return tbody;
}

function createTableCell(content) {
    const cell = document.createElement('td');
    if (content instanceof Node) {
        cell.appendChild(content);
    } else {
        cell.textContent = content;
    }
    return cell;
}

function createTypeCell(id_tipo, userId) {
    const cell = document.createElement('td');
    const select = document.createElement('select');
    select.className = 'form-select select-fixed-size';

    try {
        tiposDeUsuarios.forEach(optionData => {
            const option = document.createElement('option');
            option.value = optionData.usu_tipo_id;
            option.textContent = optionData.usu_tipo_nom;
            if (id_tipo === optionData.usu_tipo_id) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error creating select options:', error);
    }

    select.addEventListener('change', () => {
        handleTypeChange(userId, select.value);
    });

    cell.appendChild(select);
    return cell;
}

function createSpanTextButton(text) {
    const span = document.createElement('span');
    span.className = 'button-text d-none d-md-inline-block'; // Oculta en dispositivos móviles
    span.textContent = text;
    return span;
}

function createEditButton(userId) {
    const editButton = document.createElement('button');
    editButton.className = 'btn btn-primary btn-sm mr-2';
    editButton.dataset.userId = userId;

    const editIcon = document.createElement('i');
    editIcon.className = 'fas fa-edit';
    editIcon.style.marginRight = '5px'; // Espacio adicional entre el icono y el texto

    editButton.appendChild(editIcon);
    editButton.appendChild(createSpanTextButton('Editar'));

    return editButton;
}

function createChangePasswordButton(userId) {
    const changedPasswordButton = document.createElement('button');
    changedPasswordButton.className = 'btn btn-info btn-sm mr-2';
    changedPasswordButton.dataset.userId = userId;

    const passwordIcon = document.createElement('i');
    passwordIcon.className = 'fas fa-key';
    passwordIcon.style.marginRight = '5px'; // Espacio adicional entre el icono y el texto

    changedPasswordButton.appendChild(passwordIcon);
    changedPasswordButton.appendChild(createSpanTextButton('Cambiar Contraseña'));

    return changedPasswordButton;
}

function createToggleStatusButton(userId, status) {
    const actionButton = document.createElement('button');
    actionButton.className = `btn btn-sm ${status === 0 ? 'btn-secondary' : 'btn-danger'}`;
    actionButton.dataset.userId = userId;
    actionButton.dataset.status = status === 0 ? 'inactive' : 'active';

    const actionIcon = document.createElement('i');
    actionIcon.className = 'fas fa-power-off';
    actionIcon.style.marginRight = '5px'; // Espacio adicional entre el icono y el texto

    actionButton.appendChild(actionIcon);
    actionButton.appendChild(createSpanTextButton(status === 0 ? 'Activar' : 'Desactivar'));

    return actionButton;
}

function toggleUserStatus(userId, currentStatus) {
    const action = currentStatus === 0 ? 'activar' : 'desactivar';
    const actionOp = currentStatus === 0 ? 'enable_usuario' : 'disabled_usuario';
    const confirmButtonText = currentStatus === 0 ? 'Sí, activarlo!' : 'Sí, desactivarlo!';

    Swal.fire({
        title: `¿Estás seguro de que quieres ${action} este usuario?`,
        text: "No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmButtonText
    }).then((result) => {
        if (result.isConfirmed) {
            fetchData(actionOp, { usu_id: userId })
            .then(data => {
                if (data.status === 'success') {
                    const actionButton = document.querySelector(`button[data-user-id="${userId}"][data-status="${currentStatus === 0 ? 'inactive' : 'active'}"]`);
                    actionButton.textContent = currentStatus === 0 ? 'Activar' : 'Desactivar';
                    actionButton.className = `btn btn-sm ${currentStatus === 0 ? 'btn-danger' : 'btn-secondary'}`;
                    actionButton.dataset.status = currentStatus === 0 ? 'active' : 'inactive';
                    Swal.fire('Cambiado!', 'El usuario ha sido cambiado correctamente.', 'success');
                    FnOpetenerUsuarios();
                } else {
                        Swal.fire('Error', 'Hubo un problema al cambiar el estado del usuario.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error al cambiar el estado del usuario:', error);
                });
        }
    });
}

function createStatusBadge(status) {
    const cell = document.createElement('td');
    const badge = document.createElement('span');
    badge.className = 'badge';
    badge.textContent = status === 0 ? 'Desactivado' : 'Activo';
    badge.classList.add(status === 0 ? 'badge' : 'badge');
    badge.classList.add(status === 0 ? 'bg-danger' : 'bg-success');
    badge.classList.add(status === 0 ? 'bg-text-danger' : 'bg-text-success');
    cell.appendChild(badge);
    return cell;
}
function createInput(label, id, type, placeholder, value) {
    return `
<div class="form-floating mb-3">
    <input type="${type}" id="${id}" class="form-control" placeholder="${placeholder}" value="${value}">
    <label for="${id}">${label}</label>
</div>
    `;
}

function createSelect(id, selectedValue) {
    let selectHTML = `<div class="form-floating">
<select class="form-select" id="${id}" aria-label="Floating label select example">`;
    tiposDeUsuarios.forEach(optionData => {
        selectHTML += `<option value="${optionData.usu_tipo_id}" ${selectedValue === optionData.usu_tipo_nom ? 'selected' : ''}>${optionData.usu_tipo_nom}</option>`;
    });
    selectHTML += `</select>
<label for="${id}">Tipo</label>
</div>`;
    return selectHTML;
}

// Definir la variable selectedUnidad con los datos precargados
var selectedUnidad = [];

// Función para obtener los datos de la API y asignarlos a la variable selectedUnidad
function fetchUnidades() {
    var request = new XMLHttpRequest();
    request.open('GET', '../../controller/unidad.php?unidad=listar', false); // false para sincrónico
    request.onload = function() {
        if (request.status >= 200 && request.status < 400) {
            // Éxito en la solicitud
            selectedUnidad = JSON.parse(request.responseText);
            console.log('Datos recibidos:', selectedUnidad);
        } else {
            // Error en la solicitud
            console.error('Error en la solicitud:', request.status);
        }
    };
    request.onerror = function() {
        // Error en la conexión
        console.error('Hubo un problema con la solicitud fetch.');
    };
    request.send();
}

// Llamada a la función fetchUnidades para obtener los datos
fetchUnidades();

// Definir la función createSelectUnidad utilizando selectedUnidad
function createSelectUnidad(nombreUnidad) {
    try {
        const data = selectedUnidad;

        const options = data.map(unidad => ({
            value: unidad.unid_id,
            text: unidad.unid_nom
        }));

        let selectHTML = `<div class="form-floating mb-3">
<select class="form-select" id="usu_unidad" aria-label="Floating label select example">`;
        options.forEach(opt => {
            selectHTML += `<option value="${opt.value}" ${nombreUnidad === opt.text ? 'selected' : ''}>${opt.text}</option>`;
        });
        selectHTML += `</select>
<label for="unidadSelect">Unidad</label>
</div>`;
        return selectHTML;
    } catch (error) {
        console.error('Error creating select HTML:', error);
        return '<div>Error loading data</div>';
    }
}
function ChangedPasswordUser(userId) {
    Swal.fire({
        title: 'Cambiar Contraseña',
        html: `
             <div style="display: flex; flex-direction: column; align-items: center;">
                <input id="new_password" type="password" class="swal2-input mb-2" placeholder="Nueva Contraseña">
                <div>
                    <input id="show_password" class="form-check-input" type="checkbox" style="margin-right: 8px; display:block;">
                    <label for="show_password">Mostrar contraseña</label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Cambiar',
        preConfirm: () => {
            const new_password = document.getElementById('new_password').value;
            return new_password;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const new_password = result.value;
            fetchData('update_password_force', { usu_id: userId, new_pass: new_password })
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Contraseña Cambiada', text: 'La contraseña ha sido cambiada exitosamente.' });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    }
                })
                .catch(error => {
                    console.error('Error al cambiar la contraseña:', error);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error al cambiar la contraseña.' });
                });
        }
    });
    const showPasswordCheckbox = document.getElementById('show_password');
    const newPasswordInput = document.getElementById('new_password');
    showPasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            newPasswordInput.type = 'text';
        } else {
            newPasswordInput.type = 'password';
        }
    });
}
function editUser(userId) {
    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    const nombre = userRow.querySelector('td:nth-child(2)').textContent;
    const apellido = userRow.querySelector('td:nth-child(3)').textContent;
    const correo = userRow.querySelector('td:nth-child(9)').textContent;
    const unidad = userRow.querySelector('td:nth-child(5)').textContent;
    const seccion = userRow.querySelector('td:nth-child(6)').textContent;
    const telefono = userRow.querySelector('td:nth-child(7)').textContent;
    const usuario = userRow.querySelector('td:nth-child(10)').textContent;
    const tipo = userRow.querySelector('td:nth-child(4)').textContent;

    Swal.fire({
        title: 'Editar Usuario',
        html: `
            ${createInput('Nombre', 'usu_nom', 'text', 'Nombre', nombre)}
            ${createInput('Apellido', 'usu_ape', 'text', 'Apellido', apellido)}
            ${createInput('Correo', 'usu_correo', 'email', 'Correo', correo)}
            ${createSelectUnidad(unidad)} 
            <div class="form-floating mb-3" id="seccion-select-container">
                ${createSelectSeccion([],seccion)}
            </div>
            ${createInput('Teléfono', 'usu_telefono', 'text', 'Teléfono', telefono)}
            ${createInput('Usuario', 'usu_name', 'text', 'Usuario', usuario)}
            ${createSelect('usu_tipo', tipo)}
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        preConfirm: () => {
            const usu_nom = document.getElementById('usu_nom').value;
            const usu_ape = document.getElementById('usu_ape').value;
            const usu_correo = document.getElementById('usu_correo').value;
            const usu_telefono = document.getElementById('usu_telefono').value;
            const usu_unidad = document.getElementById('usu_unidad').value;
            const usu_seccion = document.getElementById('usu_seccion').value;
            const usu_name = document.getElementById('usu_name').value;
            const usu_tipo = document.getElementById('usu_tipo').value;

            return { usu_nom, usu_ape, usu_correo, usu_telefono, usu_unidad, usu_seccion, usu_name, usu_tipo };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { usu_nom, usu_ape, usu_correo, usu_telefono, usu_seccion, usu_name, usu_tipo, usu_unidad} = result.value;
            fetchData('update_usuario', {
                usu_id: userId,
                usu_nom,
                usu_ape,
                usu_correo,
                usu_unidad,
                usu_telefono,
                usu_seccion,
                usu_name,
                usu_tipo
            }).then(data => {
                if (data.status === 'success') {
                    FnOpetenerUsuarios();
                }
            }).catch(error => {
                console.error('Error al actualizar el usuario:', error);
            });
        }
    });

    // Cargar las secciones correspondientes a la unidad actual al iniciar
    fetchSeccionesPorUnidad(unidad,seccion);

    // Añadir evento para cambiar las secciones cuando cambie la unidad
    document.getElementById('usu_unidad').addEventListener('change', function () {
        const nuevaUnidadSeleccionada = this.value;
        fetchSeccionesPorUnidad(nuevaUnidadSeleccionada); // Cargar secciones dinámicamente
    });
}
function fetchSeccionesPorUnidad(unidadSeleccionada, seccionActual = '') {
    let unidadId;

    // Verificamos si `unidadSeleccionada` es un número (unid_id) o un string (unid_nom)
    if (isNaN(unidadSeleccionada)) {
        // Si es un string, buscamos el `unid_id` correspondiente en `selectedUnidad`
        const unidadEncontrada = selectedUnidad.find(unidad => unidad.unid_nom === unidadSeleccionada);
        if (unidadEncontrada) {
            unidadId = unidadEncontrada.unid_id;
        } else {
            console.error('Unidad no encontrada en selectedUnidad');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se encontró la unidad seleccionada. Contacta al administrador.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
    } else {
        // Si ya es un número, lo asignamos directamente
        unidadId = unidadSeleccionada;
    }

    console.log('Unidad ID seleccionada:', unidadId);

    // Crear un objeto FormData para enviar los datos como un formulario
    const formData = new FormData();
    formData.append('unidad', unidadId);

    // Hacer la petición para obtener las secciones correspondientes
    fetch(`/controller/seccion.php?op=get_secciones`, {
        method: 'POST',
        body: formData // Enviar los datos como FormData
    })
    .then(response => response.json())
    .then(secciones => {
        if (secciones.length > 0) {
            // Si hay secciones, pasamos también la sección actual para preseleccionarla
            const seccionSelect = createSelectSeccion(secciones, seccionActual);
            document.getElementById('seccion-select-container').innerHTML = seccionSelect;
        } else {
            // Si no hay secciones, mostramos una alerta con SweetAlert
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'No hay secciones disponibles para esta unidad. Contacta al administrador para agregar nuevas secciones.',
                confirmButtonText: 'Aceptar'
            });

            // Limpiamos el select de secciones
            document.getElementById('seccion-select-container').innerHTML = createSelectSeccion([], seccionActual);
        }
    })
    .catch(error => {
        console.error('Error al cargar las secciones:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al cargar las secciones. Inténtalo de nuevo más tarde.',
            confirmButtonText: 'Aceptar'
        });
    });
}
function createSelectSeccion(secciones = [], seccionSeleccionada = '') {
    let selectHTML = `<select id="usu_seccion" class=" form-select" name="usu_seccion">`;

    selectHTML += '<option value="" selected >Seleccione una sección</option>';

    secciones.forEach(seccion => {
        const selected = (seccion.sec_id === seccionSeleccionada || seccion.sec_nombre === seccionSeleccionada) ? 'selected' : '';
        selectHTML += `<option value="${seccion.sec_id}" ${selected}>${seccion.sec_nombre} - ${seccion.sec_detalle}</option>`;
    });

    // Cerrar el select
    selectHTML += '</select><label for="usu_seccion">Sección</label>';

    return selectHTML;
}
function handleTypeChange(userId, newType) {
    fetchData('update_usuario_tipo', { usu_id: userId, usu_tipo: newType })
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tipo de usuario actualizado',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        })
        .catch(error => {
            console.error('Error al actualizar el tipo de usuario:', error);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Error al actualizar el tipo de usuario',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        });
}
