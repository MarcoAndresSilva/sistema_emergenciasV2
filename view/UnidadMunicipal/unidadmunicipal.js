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
                Swal.fire('Éxito', data.message, 'Exito');
            } else if(data.status === 'error'){
                Swal.fire('Error', data.message, 'error');
            }else if(data.status === 'warning'){
                Swal.fire('Cuidado', data.message, 'Cuidado');
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
function renderTable(unidad) {
  const unidadInfo = document.getElementById('unidadInfo');
  unidadInfo.innerHTML = '';

    const filterContainer = document.createElement('div');
    filterContainer.className = 'mb-3';
    filterContainer.innerHTML = `
    `;
    unidadInfo.appendChild(filterContainer);

    const table = createTable(unidad);
    unidadInfo.appendChild(table);

}
// Mapeo de nombres de columnas y transformaciones
const columnConfig = {
  unid_id: 'ID',
  unid_nom: 'Nombre',
  unid_est: 'Estado',
  responsable_rut: 'Responsable RUT',
  reemplazante_rut: 'Reemplazante RUT',
  acciones: 'Acciones'
};

// Transformaciones para ciertas columnas
const transformValue = (key, value) => {
  switch (key) {
    case 'unid_est':
      return transformStatus(value);
    case 'responsable_rut':
    case 'reemplazante_rut':
      return formatRUT(value);
    default:
      return value;
  }
};

// Función para transformar el estado
const transformStatus = (status) => {
  const statuses = {
    1: 'En servicio',
    2: 'En proceso',
    3: 'Sin servicio'
  };
  return statuses[status] || 'Desconocido';
};

// Función para calcular el DV del RUT
const calculateDV = (rut) => {
  let suma = 0;
  let multiplicador = 2;

  for (let i = rut.toString().length - 1; i >= 0; i--) {
    suma += rut.toString().charAt(i) * multiplicador;
    multiplicador = multiplicador < 7 ? multiplicador + 1 : 2;
  }

  const dv = 11 - (suma % 11);
  return dv === 11 ? '0' : dv === 10 ? 'K' : dv.toString();
};

// Función para formatear el RUT con puntos y guion
const formatRUT = (rut) => {
  const dv = calculateDV(rut);
  const rutStr = rut.toString();
  let formattedRUT = '';

  for (let i = rutStr.length - 1, j = 1; i >= 0; i--, j++) {
    formattedRUT = rutStr.charAt(i) + formattedRUT;
    if (j % 3 === 0 && i > 0) {
      formattedRUT = '.' + formattedRUT;
    }
  }

  return `${formattedRUT}-${dv}`;
};

// Funciones para editar y eliminar
const editItem = (id) => {
  // Obtener la fila correspondiente a la unidad
  const row = document.getElementById(`unidad_${id}`);
  const cells = row.getElementsByTagName('td');

  // Mostrar la alerta de SweetAlert2 para editar
  Swal.fire({
    title: 'Editar Unidad',
    html:
      `<input id="editUnidNom" class="swal2-input" value="${cells[1].textContent}" placeholder="Nombre" required>` +
      `<select id="editUnidEst" class="swal2-input">
         <option value="1" ${cells[2].textContent === 'En servicio' ? 'selected' : ''}>En servicio</option>
         <option value="2" ${cells[2].textContent === 'En proceso' ? 'selected' : ''}>En proceso</option>
         <option value="3" ${cells[2].textContent === 'Sin servicio' ? 'selected' : ''}>Sin servicio</option>
       </select>` +
      `<input id="editResponsableRut" class="swal2-input" value="${cells[3].textContent.split('-')[0].replace(/\./g, '')}" placeholder="Responsable RUT" required>` +
      `<input id="editReemplazanteRut" class="swal2-input" value="${cells[4].textContent.split('-')[0].replace(/\./g, '')}" placeholder="Reemplazante RUT" required>`,
    focusConfirm: false,
    preConfirm: () => {
      const nombre = document.getElementById('editUnidNom').value;
      const estado = document.getElementById('editUnidEst').value;
      const responsableRut = document.getElementById('editResponsableRut').value;
      const reemplazanteRut = document.getElementById('editReemplazanteRut').value;

      // Validar los datos si es necesario antes de enviar
      if (!nombre || !estado || !responsableRut || !reemplazanteRut) {
        Swal.showValidationMessage('Todos los campos son obligatorios');
        return false;
      }

      const postData = {
        unid_id: id,
        unid_nom: nombre,
        unid_est: estado,
        responsable_rut: responsableRut,
        reemplazante_rut: reemplazanteRut
      };

      // Realizar la solicitud para actualizar los datos
      return fetchData('edit_unidad', postData, true)
        .then(data => {
          if (data.status !== 'success') {
            throw new Error(data.message || 'Error al actualizar la unidad');
          }
          return data;
        })
        .catch(error => {
          Swal.fire('Error', error.message || 'Error al actualizar la unidad', 'error');
        });
    }
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire('¡Actualizado!', 'La unidad ha sido actualizada correctamente', 'success');
      // Aquí podrías actualizar la fila de la tabla si lo deseas
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
            // Actualizar la tabla o realizar alguna acción adicional
            document.getElementById(`unidad_${id}`).remove();
          }
        });
    }
  });
};

function createTable(data) {
  const table = document.createElement('table');
  table.className = 'table table-striped';

  // Crear el encabezado de la tabla
  const headers = Object.keys(columnConfig);
  const headerRow = document.createElement('tr');
  headers.forEach(headerKey => {
    const header = document.createElement('th');
    header.textContent = columnConfig[headerKey];
    headerRow.appendChild(header);
  });
  table.appendChild(headerRow);

  // Crear filas con los datos de las unidades
  data.forEach(item => {
    let row = document.createElement('tr');
    row.id = `unidad_${item.unid_id}`;
    headers.forEach(key => {
      const cell = document.createElement('td');
      if (key === 'acciones') {
        const editButton = document.createElement('button');
        editButton.className = 'btn btn-primary btn-sm';
        editButton.textContent = 'Editar';
        editButton.onclick = () => editItem(item.unid_id);

        const deleteButton = document.createElement('button');
        deleteButton.className = 'btn btn-danger btn-sm ml-2';
        deleteButton.textContent = 'Eliminar';
        deleteButton.onclick = () => deleteItem(item.unid_id);

        cell.appendChild(editButton);
        cell.appendChild(deleteButton);
      } else {
        cell.textContent = transformValue(key, item[key]);
      }
      row.appendChild(cell);
    });
    table.appendChild(row);
  });

  return table;
}
