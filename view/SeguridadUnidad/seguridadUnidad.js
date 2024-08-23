// Función para generar la etiqueta de estado con clase badge
function generarBadge(clase, texto) {
    return `<span class="badge ${clase}">${texto}</span>`;
}

// Función para obtener los datos de seguridad y llenar la tabla
function obtenerSeguridadRobusta() {
    const params = new URLSearchParams();
    params.append('op', 'get_seguridad_unidad');

    fetch("../../controller/seguridadPassword.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
        // Inicializar DataTable
        const table = $('#example').DataTable();
        table.clear();

        // Agregar datos a la tabla
        data.forEach(item => {
            const unidadNombre = unidades.find(unid => unid.unid_id === item.usu_unidad)?.unid_nom || 'sin definir';
            table.row.add([
                item.rob_id,
                unidadNombre,
                generarBadge(item.mayuscula ? 'text-bg-success' : 'text-bg-danger', item.mayuscula ? 'activado' : 'desactivado'),
                generarBadge(item.minuscula ? 'text-bg-success' : 'text-bg-danger', item.minuscula ? 'activado' : 'desactivado'),
                generarBadge(item.especiales ? 'text-bg-success' : 'text-bg-danger', item.especiales ? 'activado' : 'desactivado'),
                generarBadge(item.numeros ? 'text-bg-success' : 'text-bg-danger', item.numeros ? 'activado' : 'desactivado'),
                item.largo,
                item.camb_dias,
                item.fecha_modi,
                `<button class="btn btn-primary" onclick="actualizar(${item.rob_id})"><i class="fas fa-edit"></i> Editar</button>`
            ]).draw();
        });
    })
    .catch(error => console.error('Error:', error));
}

// Función para capturar datos y mostrar el formulario de actualización
function actualizar(id) {
    const row = $(`#example button[onclick="actualizar(${id})"]`).closest('tr');
    const data = $('#example').DataTable().row(row).data();

    const unidadOptions = unidades.map(unid => 
        `<option value="${unid.unid_id}" ${data[1] === unid.unid_nom ? 'selected' : ''}>${unid.unid_nom}</option>`
    ).join('');
    
    Swal.fire({
        title: 'Actualizar Datos',
        html: `
            <label for="usu_unidad">Unidad de Usuario:</label>
            <select id="usu_unidad" class="swal2-input">
                ${unidadOptions}
            </select>
            <label for="mayuscula">Mayúsculas:</label>
            <select id="mayuscula" class="swal2-input">
                <option value="1" ${data[2].includes('success') ? 'selected' : ''}>Activado</option>
                <option value="0" ${data[2].includes('danger') ? 'selected' : ''}>Desactivado</option>
            </select>
            <label for="minuscula">Minúsculas:</label>
            <select id="minuscula" class="swal2-input">
                <option value="1" ${data[3].includes('success') ? 'selected' : ''}>Activado</option>
                <option value="0" ${data[3].includes('danger') ? 'selected' : ''}>Desactivado</option>
            </select>
            <label for="especiales">Especiales:</label>
            <select id="especiales" class="swal2-input">
                <option value="1" ${data[4].includes('success') ? 'selected' : ''}>Activado</option>
                <option value="0" ${data[4].includes('danger') ? 'selected' : ''}>Desactivado</option>
            </select>
            <label for="numeros">Números:</label>
            <select id="numeros" class="swal2-input">
                <option value="1" ${data[5].includes('success') ? 'selected' : ''}>Activado</option>
                <option value="0" ${data[5].includes('danger') ? 'selected' : ''}>Desactivado</option>
            </select>
            <label for="largo">Largo:</label>
            <input id="largo" type="number" class="swal2-input" value="${data[6]}">
            <label for="dias">Días:</label>
            <input id="dias" type="number" class="swal2-input" value="${data[7]}"> <!-- Nuevo campo 'dias' -->
        `,
        focusConfirm: false,
        preConfirm: () => {
            const usu_unidad = document.getElementById('usu_unidad').value;
            const mayuscula = document.getElementById('mayuscula').value;
            const minuscula = document.getElementById('minuscula').value;
            const especiales = document.getElementById('especiales').value;
            const numeros = document.getElementById('numeros').value;
            const largo = document.getElementById('largo').value;
            const dias = document.getElementById('dias').value;

            return {
                rob_id: id,
                usu_unidad: usu_unidad,
                mayuscula: mayuscula,
                minuscula: minuscula,
                especiales: especiales,
                numeros: numeros,
                largo: largo,
                op: "update_unidad_robusta",
                camb_dias: dias
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let postData = result.value;
            fetchData(op="update_unidad_robusta", postData=postData);
            obtenerSeguridadRobusta();
        }
    });
}

// Función para obtener los datos de unidades y guardarlos
function obtenerDatosUnidades() {
    fetch("../../controller/unidad.php?unidad=listar", {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        unidades = data;
        obtenerSeguridadRobusta();
    })
    .catch(error => console.error('Error:', error));
}
function fetchData(op, postData, sendAsJson = false) {
    // URL del controlador
    const url = '../../controller/seguridadPassword.php';

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

// Llamar a las funciones para obtener y mostrar los datos al cargar la página
$(document).ready(function() {
    $('#example').DataTable({
        responsive: true
    });
    obtenerDatosUnidades();
});

