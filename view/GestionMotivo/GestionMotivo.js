function fn_agregar_motivo_cierre() {
    Swal.fire({
        title: 'Ingrese el motivo',
        input: 'text',
        inputPlaceholder: 'Escriba su motivo aquí',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Motivo ingresado', `Su motivo fue: ${result.value}`, 'success');
            let data = {'motivo': result.value};
            // Hacer la solicitud a fetchData
            fetchData('add_cierre_motivo', data)
                .then(response => {
                    // Verificar el estado de la respuesta
                    if (response.status === 'success') {
                        console.log('La consulta fue exitosa:', response.data);
                        OptenerMotivos()
                    } else {
                        console.error('Error en la consulta:', response.error);
                    }
                })
                .catch(error => {
                    console.error('Error al realizar la consulta:', error);
                });
        }
    });
}

function fetchData(op, postData) {
    // URL del controlador
    const url = '../../controller/cierreMotivo.php';

    // Construir la URL con los parámetros GET
    const params = new URLSearchParams({
        op: op,
    });

    // Agregar los parámetros GET a la URL del controlador
    const fetchUrl = `${url}?${params}`;
    
    // Convertir el objeto postData a formato x-www-form-urlencoded
    const formData = new URLSearchParams(postData).toString();

    // Configurar la solicitud FETCH
    const requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Tipo de contenido del cuerpo de la solicitud
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

let tabla = document.getElementById("miTabla");
async function OptenerMotivos() {
    let response = await fetch('../../controller/cierreMotivo.php?op=get_cierre_motivo');

    if (response.ok) {
        let data = await response.json();
        console.log(data)
        actualizarTabla(data);
    } else {
        console.error('Error en la petición:', response.status, response.statusText);
    }
}

function actualizarTabla(data) {
    // Limpia la tabla
    while (tabla.firstChild) {
        tabla.removeChild(tabla.firstChild);
    }
    // Agrega los nuevos datos a la tabla 
    data.forEach(item => {
        let fila = document.createElement('tr');

        let celdaMotivo = document.createElement('td');
        celdaMotivo.textContent = item.motivo;
        fila.appendChild(celdaMotivo);

        // Crear botones
        let buttonedit = document.createElement('button');
        buttonedit.id = "buttonedit";
        buttonedit.className = "btn btn-warning";
        buttonedit.type = "button";
        let imgEdit = document.createElement('img');
        imgEdit.src = "../../public/img/edit.svg";
        buttonedit.appendChild(imgEdit);

        let buttonCatego = document.createElement('button');
        buttonCatego.id = "buttoncatego";
        buttonCatego.className = "btn btn-info";
        buttonCatego.type = "button";
        let imgCatego = document.createElement('img');
        imgCatego.src = "../../public/img/categoria.svg";
        buttonCatego.appendChild(imgCatego);
        let textoCatego = document.createTextNode("Categoria");
        buttonCatego.appendChild(textoCatego);
        
        let buttondelete = document.createElement('button');
        buttondelete.id = "buttondelete";
        buttondelete.className = "btn btn-danger";
        buttondelete.type = "button";
        let imgDelete = document.createElement('img');
        imgDelete.src = "../../public/img/trash.svg";
        buttondelete.appendChild(imgDelete);

        let celdaAccion = document.createElement('td');
        celdaAccion.appendChild(buttonedit);
        celdaAccion.appendChild(buttonCatego);
        celdaAccion.appendChild(buttondelete);
        fila.appendChild(celdaAccion);
 
        tabla.appendChild(fila);
    });

}

// Llamada a fetchData al inicio
OptenerMotivos();

