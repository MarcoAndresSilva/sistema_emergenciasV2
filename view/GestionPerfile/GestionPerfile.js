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
 document.addEventListener('DOMContentLoaded', FnOpetenerUsuarios);

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

            const table = createTable(users);
            userInfo.appendChild(table);
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
            const headers = ['ID', 'Nombre', 'Apellido', 'Tipo', 'Teléfono','Estado' ,'Correo', 'Usuario', 'Acciones'];

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

                row.appendChild(createTableCell(user.usu_id));
                row.appendChild(createTableCell(user.Nombre));
                row.appendChild(createTableCell(user.Apellido));
                row.appendChild(createTypeCell(user.id_tipo));
                row.appendChild(createTableCell(user.Telefono));
                row.appendChild(createStatusBadge(user.estado));
                row.appendChild(createTableCell(user.Correo));
                row.appendChild(createTableCell(user.Usuario));
                row.appendChild(createActionButtons(user.usu_id, user.estado));

                tbody.appendChild(row);
            });

            return tbody;
        }

        function createTableCell(content) {
            const cell = document.createElement('td');
            cell.textContent = content;
            return cell;
        }

        function createTypeCell(id_tipo) {
            const cell = document.createElement('td');
            const select = document.createElement('select');
            select.className = 'form-control';

            const options = [
                { value: 1, text: 'Emergencias' },
                { value: 2, text: 'Informática' },
                { value: 3, text: 'Territorial' }
            ];

            options.forEach(optionData => {
                const option = document.createElement('option');
                option.value = optionData.value;
                option.textContent = optionData.text;
                if (id_tipo === optionData.value) {
                    option.selected = true;
                }
                select.appendChild(option);
            });

            cell.appendChild(select);
            return cell;
        }


function createActionButtons(userId, status) {
    const cell = document.createElement('td');

    const editButton = document.createElement('button');
    editButton.className = 'btn btn-primary btn-sm mr-2';
    editButton.textContent = 'Editar';
    editButton.onclick = () => editUser(userId);

    const actionButton = document.createElement('button');
    actionButton.className = `btn btn-sm ${status === 0 ? 'btn-secondary' : 'btn-danger'}`;
    actionButton.textContent = status === 0 ? 'Activar' : 'Desactivar';
    actionButton.onclick = () => toggleUserStatus(userId, status);

    cell.appendChild(editButton);
    cell.appendChild(actionButton);

    return cell;
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
                // Aquí puedes manejar la respuesta si es necesario
                if (data.status === 'success') {
                    // Volver a cargar los usuarios o actualizar la tabla
                    FnOpetenerUsuarios();
                }
            })
            .catch(error => {
                console.error(`Error al ${action} el usuario:`, error);
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
