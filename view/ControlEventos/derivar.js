// Mostrar el ID del evento en el modal
function mostrarIdEvento(ev_id) {
    $('#derivar_ev_id').text(ev_id);
}

// Mostrar el nombre de la categoría
function consultarCategoria(ev_id) {
    $.post("../../controller/categoria.php?op=get_cat_nom_by_ev_id", { ev_id: ev_id }, function(data) {
        try {
            const jsonData = JSON.parse(data);
            if (jsonData && jsonData.cat_nom) {
                $('#derivar_cat_nombre').text(jsonData.cat_nom);
            } else {
                console.log("No se encontró el cat_nom para el evento con ID: " + ev_id);
            }
        } catch (error) {
            console.log("Error al analizar la respuesta JSON:", error);
        }
    });
}

// Función para cargar solo las unidades asignadas en el evento
function seccionesAsignadasEvento(ev_id) {
    $.post("../../controller/emergenciaDetalle.php?op=mostrar", { ev_id: ev_id }, function(data) {
        data = JSON.parse(data);
        
        // Aquí extraemos solo las unidades y las mostramos, ignorando el resto
        const listaParticipantes = $("#listaParticipantes");
        listaParticipantes.empty();

        if (data.unidades && data.unidades.length > 0) {
            data.unidades.forEach(function(unidad) {
                listaParticipantes.append(`<li class="list-group-item">${unidad}</li>`);
            });
            seccionesAsignadas = data.unidades.map(u => u.sec_id); // Guardamos los IDs asignados en la variable global
        } else {
            listaParticipantes.append(`<li class="list-group-item">No hay unidades asignadas</li>`);
            seccionesAsignadas = [];
        }
    });
}

let seccionesIniciales = [];

function cargarsecciones(ev_id) {
    fetch('../../controller/seccion.php?op=lista_secciones_con_unidad', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            const tablaSeccionesBody = $('#tablaSecciones tbody');
            tablaSeccionesBody.empty();

            data.forEach(unidad => {
                if (unidad.secciones.length > 0) {
                    unidad.secciones.forEach(seccion => {
                        // Determinar el estado como texto
                        const estadoTexto = Number(seccion.sec_est) === 0 ? 'Ocupado' : 'Disponible';

                        // Definir el botón de acción dependiendo del estado
                        const botonAccion = Number(seccion.sec_est) === 0
                            ? `<button class="btn btn-danger btn-sm btnEliminar" data-sec-id="${seccion.sec_id}" data-ev-id="${ev_id}">Eliminar</button>`
                            : `<button class="btn btn-success btn-sm btnAgregar" data-sec-id="${seccion.sec_id}" data-ev-id="${ev_id}">Agregar</button>`;

                        // Agregar fila a la tabla
                        const row = `
                            <tr>
                                <td>${unidad.unidad}</td>
                                <td>${seccion.sec_nombre}</td>
                                <td>${seccion.sec_detalle}</td>
                                <td>${estadoTexto}</td> <!-- Aquí usamos estadoTexto -->
                                <td>${botonAccion}</td>
                            </tr>
                        `;
                        tablaSeccionesBody.append(row);
                    });
                } else {
                    // Mostrar mensaje cuando no hay secciones
                    const row = `
                        <tr>
                            <td>${unidad.unidad}</td>
                            <td colspan="4">Esta unidad no tiene secciones</td>
                        </tr>
                    `;
                    tablaSeccionesBody.append(row);
                }
            });

            // Inicializar DataTables con configuraciones específicas
            $('#tablaSecciones').DataTable({
                pageLength: 5,
                language: {
                    url: "../registrosLog/spanishDatatable.json"
                },
                destroy: true
            });

            // Añadir los eventos para los botones dinámicos de eliminar y agregar
            $('.btnEliminar').on('click', function(event) {
                event.preventDefault();
                const sec_id = $(this).data('sec-id');
                const ev_id = $(this ).data('ev-id');
                eliminarderivado(sec_id, ev_id);
            });

            $('.btnAgregar').on('click', function(event) {
                event.preventDefault();
                const sec_id = $(this).data('sec-id');
                const ev_id = $(this ).data('ev-id');

                agregarderivado(sec_id, ev_id);
            });
        })
        .catch(error => console.error('Error al cargar las secciones:', error));
}


// Función para eliminar derivado
async function eliminarderivado(id_seccion, ev_id) {
    try {
        let formData = new FormData();
        formData.append('ev_id', ev_id);
        formData.append('sec_id', id_seccion);

        let response = await fetch('../../controller/derivar.php?op=delete_derivado', {
            method: 'POST',
            body: formData
        });

        let resultado = await response.json();

        if (resultado.status === "success") {
            Swal.fire({
                title: 'Sección eliminada',
                text: resultado.message,
                icon: 'success',
                confirmButtonText: 'Aceptar',
                willClose: () => {
                    $('#modalDerivar').modal('hide'); // Cerrar el modal
                }
            });
        } else {
            Swal.fire({
                title: 'Advertencia',
                text: resultado.message,
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (error) {
        console.error("Error al eliminar derivado:", error);
    }
}

// Función para agregar derivado
async function agregarderivado(id_seccion, ev_id) {
    try {
        let formData = new FormData();
        formData.append('ev_id', ev_id);
        formData.append('sec_id', id_seccion);

        let response = await fetch('../../controller/derivar.php?op=agregar_derivado', {
            method: 'POST',
            body: formData
        });

        let resultado = await response.json();

        if (resultado.status === "success") {
            Swal.fire({
                title: 'Sección añadida',
                text: resultado.message,
                icon: 'success',
                confirmButtonText: 'Aceptar',
                willClose: () => {
                    $('#modalDerivar').modal('hide'); // Cerrar el modal
                }
            });
        } else {
            Swal.fire({
                title: 'Advertencia',
                text: resultado.message,
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (error) {
        console.error("Error al agregar derivado:", error);
    }
}
