function fetchData(op, postData, sendAsJson = false) {
    // URL del controlador
    const url = '../../controller/unidad.php';

    // Construir la URL con los parámetros GET
    const params = new URLSearchParams({
        op: op,
    });

    // Agregar los parámetros GET a la URL del controlador
    const fetchUrl = `${url}?${params}`;

    // Convertir el objeto postData a formato x-www-form-urlencoded o JSON
    let formData, contentType;
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
            if (!response.ok) throw new Error('Error en la solicitud.');
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
document.addEventListener('DOMContentLoaded', FnOpetenerUnidad);
function FnOpetenerUnidad() {
  const url = '../../controller/unidad.php';
  const params = new URLSearchParams({ op: 'get_unidad' });
  const fetchUrl = `${url}?${params}`;

  const requestOptions = {
    method: 'GET',
    headers: { 'Content-Type': 'application/json' },
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
    console.error('Error al obtener la información de los usuarios:', error);
  });
}
function renderTable(data) {
  const unidadInfo = document.getElementById('unidadInfo');
  unidadInfo.innerHTML = '';

// Función para transformar el estado
const transformStatus = (status) => {
  const statuses = {
    1: 'En servicio',
    2: 'En proceso',
    3: 'Sin servicio'
  };
  return statuses[status] || 'Desconocido';
};
    const tableContainer = document.createElement('div');
    tableContainer.className = 'table-responsive';

    const table = document.createElement('table');
    table.className = 'table table-striped table-bordered';
    table.id = 'unidadTable';

    unidadInfo.appendChild(tableContainer);
    tableContainer.appendChild(table);

    // Inicialización de DataTable

    $(table).DataTable({
        data: data,
        columns: [
            { data: 'unid_id', title: 'ID' },
            { data: 'unid_nom', title: 'Nombre' },
            { data: 'unid_est', title: 'Estado', render: transformStatus },
            {
                data: null,
                title: 'editar',
                render: function (data) {
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${data.unid_id}">Editar</button>
                    `;
                }
            },
            {
                data: null,
                title: 'eliminar',
                render: function (data) {
                    return `
                        <button class="btn btn-danger btn-sm ml-2 delete-btn" data-id="${data.unid_id}">Eliminar</button>
                    `;
                }
            }
        ],
        columnDefs: [
            { targets: [0], visible: false } // Ocultar ID
        ],
        language: {
            url: '../registrosLog/spanishDatatable.json'
        }
    });


    $(table).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        editItem(id);
    });

    $(table).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        deleteItem(id);
    });
}


const editItem = (id) => {

  Swal.fire({
    title: 'Editar Unidad',
    html:
      `<input id="editUnidNom" class="swal2-input" value="" placeholder="Nombre" required>
      <select id="editUnidEst" class="swal2-input">
         <option value="1">En servicio</option>
         <option value="2">En proceso</option>
         <option value="3">Sin servicio</option>
      </select>`,
    focusConfirm: false,
    preConfirm: () => {
      const nombre = document.getElementById('editUnidNom').value;
      const estado = document.getElementById('editUnidEst').value;
      if (!nombre || !estado) {
        Swal.showValidationMessage('Todos los campos son obligatorios');
        return false;
      }

      const postData = {
        unid_id: id,
        unid_nom: nombre,
        unid_est: estado,
      };

      return fetchData('update_unidad', postData)
       .then(data => {
         if (data.status === 'success') {
            return FnOpetenerUnidad();
          } else {
          throw new Error(data.message || 'Error al actualizar');
        }
        })
        .catch(error => Swal.fire('Error', error.message || 'Error al actualizar', 'error'));
      }
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire('¡Actualizado!', 'La unidad ha sido actualizada correctamente', 'success');
    }
  });
};

const deleteItem = (id) => {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "¡No podrás revertir esto!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, eliminarlo!',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      fetchData('delete_unidad', { unid_id: id })
        .then(data => {
          if (data.status === 'success') {
            $('#unidadTable').DataTable().ajax.reload();
          }
        });
    }
  });
};

function fn_agregar_unidad() {
  Swal.fire({
    title: 'Agregar Nueva Unidad',
    html:
      `<input id="addUnidNom" class="swal2-input" placeholder="Nombre" required>` +
      `<select id="addUnidEst" class="swal2-input">
         <option value="1">En servicio</option>
         <option value="2">En proceso</option>
         <option value="3">Sin servicio</option>
       </select>`,
    focusConfirm: false,
    preConfirm: () => {
      const nombre = document.getElementById('addUnidNom').value;
      const estado = document.getElementById('addUnidEst').value;

      // Validar los datos si es necesario antes de enviar
      if (!nombre || !estado ) {
        Swal.showValidationMessage('Todos los campos son obligatorios');
        return false;
      }

      const postData = {
        unid_nom: nombre,
        unid_est: estado,
      };

      // Realizar la solicitud para agregar la nueva unidad
      return fetchData('add_unidad', postData)
        .then(data => {
          if (data.status === 'success') {
            return FnOpetenerUnidad();
          } else {
             throw new Error(data.message || 'Error al agregar');
          }
        })
        .catch(error => Swal.fire('Error', error.message || 'Error al agregar', 'error'));
    }
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire('¡Agregada!', 'La unidad ha sido agregada correctamente', 'success');
      // Aquí podrías realizar alguna acción adicional si lo deseas
    }
  });
}
