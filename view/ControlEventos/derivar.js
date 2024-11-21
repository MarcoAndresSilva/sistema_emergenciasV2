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
function seccionesAsignadasEvento(id_evento) {
    $.post("../../controller/evento.php?op=informacion_evento_completo", { id_evento: id_evento }, function(data) {
        try {
            // Asegúrate de que `data` ya esté en formato JSON
            if (typeof data === "string") {
                data = JSON.parse(data);
            }

            const listaParticipantes = $("#listaParticipantesderivar");
            listaParticipantes.empty();

            // Verificar si la respuesta tiene éxito y tiene las secciones asignadas
            if (data.status === "success" && data.secciones_asignadas && data.secciones_asignadas.secciones) {
                const secciones = data.secciones_asignadas.secciones;

                if (secciones.length > 0) {
                    // Iterar a través de las secciones asignadas y mostrarlas en la lista de participantes
                    secciones.forEach(function(seccion) {
                        listaParticipantes.append(`
                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>
                                <span>${seccion.nombre}</span>
                            </div>
                        `);
                    });

                    // Guardar los IDs de las secciones asignadas en la variable global
                    seccionesAsignadas = data.secciones_asignadas.id_secciones_asignadas;
                } else {
                    listaParticipantes.append(`<li class="list-group-item">No hay unidades asignadas</li>`);
                    seccionesAsignadas = [];
                }
            } else {
                console.error("No se obtuvieron las secciones asignadas o hubo un error en la solicitud.");
                listaParticipantes.append(`
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        <span>No se pudo obtener la información del evento.</span>
                    </div>
                `);
            }
        } catch (error) {
            console.error("Error al analizar la respuesta JSON:", error);
            listaParticipantes.append(`<li class="list-group-item">Ocurrió un error al cargar las unidades</li>`);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        // Manejar el fallo de la solicitud AJAX
        console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
        $("#listaParticipantesderivar").empty().append(`
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <span>No hay secciones asignadas a este evento</span>
            </div>
        `);
    });
}

function cargarsecciones(ev_id) {
    fetch('../../controller/seccion.php?op=lista_secciones_con_unidad', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            const tablaSecciones = $('#tablaSecciones');

            // Destruir DataTable si ya está inicializado
            if ($.fn.DataTable.isDataTable('#tablaSecciones')) {
                tablaSecciones.DataTable().clear().destroy();
            }

            const tablaSeccionesBody = tablaSecciones.find('tbody');
            tablaSeccionesBody.empty();

            data.forEach(unidad => {
                if (unidad.secciones.length > 0) {
                    unidad.secciones.forEach(seccion => {
                        // Determinar el estado como texto
                        const estadoTexto = Number(seccion.sec_est) === 0 ? 'Ocupado' : 'Disponible';

                        // Definir el botón de acción dependiendo del estado
                        const botonAccion = Number(seccion.sec_est) === 0
                            ? `<button type="button" class="btn btn-danger btn-sm btnEliminar" data-sec-id="${seccion.sec_id}" data-ev-id="${ev_id}">Eliminar</button>`
                            : `<button type="button" class="btn btn-success btn-sm btnAgregar" data-sec-id="${seccion.sec_id}" data-ev-id="${ev_id}">Agregar</button>`;

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

            tablaSecciones.DataTable({
                pageLength: 5,
                language: {
                    url: "../registrosLog/spanishDatatable.json"
                },
                destroy: true
            });

            $('.btnEliminar').on('click', function(event) {
                event.preventDefault();
                const sec_id = $(this).data('sec-id');
                const ev_id = $(this).data('ev-id');
                eliminarderivado(sec_id, ev_id);
            });

            $('.btnAgregar').on('click', function(event) {
                event.preventDefault();
                const sec_id = $(this).data('sec-id');
                const ev_id = $(this).data('ev-id');
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
                    cargarsecciones(ev_id);
                    seccionesAsignadasEvento(ev_id); 
                    if (typeof cargarTablaGeneral === "function") {
                        cargarTablaGeneral();
                    }
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
                    cargarsecciones(ev_id);
                    seccionesAsignadasEvento(ev_id);
                    if (typeof cargarTablaGeneral === "function") {
                        cargarTablaGeneral();
                    }
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
