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

function createTable(data) {
  const table = document.createElement('table');
  table.className = 'table table-striped';

  // Crear el encabezado de la tabla
  const headers = Object.keys(data[0]);
  const headerRow = document.createElement('tr');
  headers.forEach(headerText => {
    const header = document.createElement('th');
    header.textContent = headerText;
    headerRow.appendChild(header);
  });
  table.appendChild(headerRow);

  // Crear filas con los datos de las unidades
  data.forEach(item => {
    let row = document.createElement('tr'); 
    row.id = `unidad_${item.unid_id}`;
    headers.forEach(key => {
      const cell = document.createElement('td');
      cell.textContent = item[key];
      row.appendChild(cell);
    });
    table.appendChild(row);
  });

  return table;
}
