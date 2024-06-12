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

function fetchData(op, postData, sendAsJson = false) {
    // URL del controlador
    const url = '../../controller/cierreMotivo.php';

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


function fn_delete_motivo(id){
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, bórralo!',
        cancelButtonText: 'No, cancelar!'
    }).then((result) => {
        if (result.isConfirmed) {
            let data = {'mov_id': id};
            fetchData('delete_cierre_motivo', data)
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
    })
}
function fn_edit_motivo(mov_id, motivo_original) {
    Swal.fire({
        title: 'Renombrar motivo',
        input: 'text',
        inputPlaceholder: 'Escriba el nuevo nombre',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Motivo Renombrado', `Se procesa cambio`, 'success');
            let data = {
                'mov_id':mov_id,
                'motivo_original':motivo_original ,
                'motivo_edit': result.value
            };
            // Hacer la solicitud a fetchData
            fetchData('update_cierre_motivo', data)
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
function actualizarTabla(data) {
    // Encuentra la tabla
    let tabla = document.getElementById('miTabla');

    // Encuentra los hijos de la tabla
    let hijosTabla = tabla.children;

    // Itera sobre los hijos y elimina solo las filas (tr)
    for (let i = hijosTabla.length - 1; i >= 0; i--) {
        if (hijosTabla[i].tagName.toLowerCase() === 'tbody') {
            tabla.removeChild(hijosTabla[i]);
        }
    }

    // Crea un nuevo tbody
    let tbody = document.createElement('tbody');

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
         let textoedit= document.createTextNode("Renombrar");
        buttonedit.appendChild(textoedit);
        buttonedit.onclick= function(){
            fn_edit_motivo(item.mov_id,item.motivo)
        }

        let buttonCatego = document.createElement('button');
        buttonCatego.id = "buttoncatego";
        buttonCatego.className = "btn btn-info";
        buttonCatego.type = "button";
        let imgCatego = document.createElement('img');
        imgCatego.src = "../../public/img/categoria.svg";
        buttonCatego.appendChild(imgCatego);
        let textoCatego = document.createTextNode("Categoria");
        buttonCatego.appendChild(textoCatego);
        buttonCatego.onclick = function(){
            showSelection(item.mov_id)
        }
        
        let buttondelete = document.createElement('button');
        buttondelete.id = "buttondelete";
        buttondelete.className = "btn btn-danger";
        buttondelete.type = "button";
        let imgDelete = document.createElement('img');
        imgDelete.src = "../../public/img/trash.svg";
        buttondelete.appendChild(imgDelete);
        buttondelete.onclick = function(){
            fn_delete_motivo(item.mov_id)
        }

        let celdaAccion = document.createElement('td');
        celdaAccion.appendChild(buttonedit);
        celdaAccion.appendChild(buttonCatego);
        celdaAccion.appendChild(buttondelete);
        fila.appendChild(celdaAccion);
 
        tbody.appendChild(fila);
    });

    // Agrega el nuevo tbody a la tabla
    tabla.appendChild(tbody);
    $('#miTabla').DataTable({
        "columns": [
            { "width": "50%" },
            { "width": "50%" }
        ],
        "language":{
            "url":'../registrosLog/spanishDatatable.json'
        },
    });
}

function showSelection(mov_id) {
    datosCategoria(mov_id);
}


function datosCategoria(mov_id) {
    var url = '../../controller/categoria.php?op=get_categoria_gestion_motivo';

    var data = new FormData();
    data.append('mov_id', mov_id);

    fetch(url, {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        mostrarDialogo(data, mov_id);
    })
    .catch(error => {
        console.error('Error al obtener datos de categoría:', error);
    });
}
// Función para generar los checkboxes de las categorías
function generarCheckboxes(categorias) {
    var checkboxes = '';
    categorias.forEach(function (categoria) {
        if (categoria.cat_nom && categoria.cat_nom.trim() !== "") {
            checkboxes += `<li><label style="display:flex;"><input type="checkbox" name="${categoria.cat_nom}" data-cat-id="${categoria.cat_id}" style="margin-right:6px;" value="${categoria.cat_id}" ${categoria.activo === 1 ? 'checked' : ''}> ${categoria.cat_nom}</label></li>`;
        }
    });
    return checkboxes;
}

// Función para obtener las opciones seleccionadas
function obtenerOpcionesSeleccionadas() {
    var selectedOptions = {};
    document.querySelectorAll('input[type=checkbox]').forEach(function (checkbox) {
        if (checkbox.name.trim() !== "") {
            selectedOptions[checkbox.name] = {
                id: checkbox.getAttribute('data-cat-id'),
                value: checkbox.checked
            };
        }
    });
    return selectedOptions;
}

// Función para mostrar el diálogo de selección de opciones
function mostrarDialogo(categorias, mov_id) {
    var checkboxes = generarCheckboxes(categorias);

    Swal.fire({
        title: 'Selecciona tus opciones',
        html: '<input type="text" id="searchInput" class="swal2-input" placeholder="Buscar...">' +
            '<ul id="optionsContainer" style="max-height: 200px; overflow-y: auto;">' +
            checkboxes +
            '</ul>',
        showCancelButton: true,
        preConfirm: function () {
            var selectedOptions = obtenerOpcionesSeleccionadas();
            console.log(selectedOptions); // Agregar este console.log para depurar
            return selectedOptions;
        }
    }).then(function (result) {
        if (result.isConfirmed) {
            var selectedOptions = result.value;
            // Eliminar cualquier categoría vacía del objeto selectedOptions
            for (var categoria in selectedOptions) {
                if (!selectedOptions.hasOwnProperty(categoria)) continue;
                if (categoria.trim() === "") {
                    delete selectedOptions[categoria];
                }
        }
 
            // Aquí puedes enviar los datos seleccionados mediante una solicitud POST
            var op = 'asociar_motivos_categoria';
            var postData = {
                mov_id: mov_id,
                categorias: selectedOptions // Enviar el objeto como un array
            };
            fetchData(op, postData, true);
        }
    });

    // Agregar funcionalidad de búsqueda
    document.getElementById('searchInput').addEventListener('input', function () {
        var searchText = this.value.toLowerCase();
        document.querySelectorAll('#optionsContainer label').forEach(function (label) {
            var optionText = label.textContent.toLowerCase();
            if (optionText.includes(searchText)) {
                label.style.display = 'flex';
            } else {
                label.style.display = 'none';
            }
        });
    });
}
// Llamada a fetchData al inicio
OptenerMotivos();

