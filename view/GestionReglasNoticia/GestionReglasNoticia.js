document.addEventListener("DOMContentLoaded", function () {
    let dataMappings = {
        unidad: {},
        tipo_usuario: {},
        usuarios: {},
        seccion: {}
    };

    // Initialize DataTable
    const table = $('#reglas-table').DataTable({
        responsive: true,
        ajax: {
            url: '../../controller/noticia.php?op=get_reglas',
            dataSrc: '',
            method: 'GET',
            cache: false
        },
        columns: [
            { title: 'ID Regla', data: 'id_regla' },
            { title: 'Asunto', data: 'asunto' },
            { title: 'Unidad', data: 'unidad', render: function (data, type, row) {
                if (data) {
                    const ids = data.split(',').map(id => id.trim());
                    return ids.map(id => dataMappings.unidad[id] || "Sin Asignar").join(', ');
                }
                return "Sin Asignar";
            }},
            { title: 'Sección', data: 'seccion', render: function (data, type, row) {
                if (data) {
                    const ids = data.split(',').map(id => id.trim());
                    return ids.map(id => dataMappings.seccion[id] || "Sin Asignar").join(', ');
                }
                return "Sin Asignar";
            }},
            { title: 'Usuario', data: 'usuario', render: function (data, type, row) {
                if (data) {
                    const ids = data.split(',').map(id => id.trim());
                    return ids.map(id => dataMappings.usuarios[id] || "Sin Asignar").join(', ');
                }
                return "Sin Asignar";
            }},
            { title: 'Tipo Usuario', data: 'tipo_usuario', render: function (data, type, row) {
                if (data) {
                    const ids = data.split(',').map(id => id.trim());
                    return ids.map(id => dataMappings.tipo_usuario[id] || "Sin Asignar").join(', ');
                }
                return "Sin Asignar";
            }},
            { // Add Edit button with data-id attribute
                title: 'Acciones',
                data: 'id_regla',
                render: function (data, type, row) {
                    return `<button class="edit-btn btn btn-primary" data-id="${data}">Editar</button>`;
                }
            }
        ],
         language: {
                url: "../registrosLog/spanishDatatable.json"
            },
            destroy: true // Permite volver a inicializar la tabla si ya ha sido creada
    });

    // Function to fetch and map filter data
    async function fetchMappings() {
        try {
            const response = await fetch('../../controller/noticia.php?op=get_reglas_relaciones');
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            
            // Map units
            dataMappings.unidad = {};
            data.unidad.forEach(item => {
                dataMappings.unidad[item.unid_id] = item.unid_nom;
            });

            // Map user types
            dataMappings.tipo_usuario = {};
            data.tipo_usuario.forEach(item => {
                dataMappings.tipo_usuario[item.usu_tipo_id] = item.usu_tipo_nom;
            });

            // Map users
            dataMappings.usuarios = {};
            data.usuarios.result.forEach(item => {
                dataMappings.usuarios[item.usu_id] = `${item.Nombre} ${item.Apellido}`;
            });

            // Map sections if provided
            dataMappings.seccion = {};
            if (data.seccion) {
                data.seccion.forEach(item => {
                    dataMappings.seccion[item.sec_id] = item.sec_nombre;
                });
            }

        } catch (error) {
            console.error('Fetch error:', error);
        }
    }

    // Fetch mappings and then initialize DataTable
    fetchMappings().then(() => {
        // Reload DataTable data after mappings are fetched
        table.ajax.reload();
    });

    // Add click event listener for edit button
    $('#reglas-table tbody').on('click', '.edit-btn', async function () {
        const idRegla = $(this).data('id');

        // Fetch all rules and find the specific rule by id_regla
        try {
            const response = await fetch('../../controller/noticia.php?op=get_reglas');
            if (!response.ok) throw new Error('Network response was not ok');
            
            const rules = await response.json();
            const ruleData = rules.find(rule => rule.id_regla == idRegla); // Find the rule with the specific id
            
            if (ruleData) {
                openEditModal(ruleData); // Open modal with fetched data
            } else {
                console.error('Regla no encontrada con el ID proporcionado.');
            }
        } catch (error) {
            console.error('Error fetching rule data:', error);
        }
    });

function openEditModal(rowData) {
    Swal.fire({
        title: 'Editar Regla',
        html: `
            <label for="unidad">Unidad:</label>
            <select id="unidad" class="swal2-input" multiple style="width: 100%"></select>
            
            <label for="seccion">Sección:</label>
            <select id="seccion" class="swal2-input" multiple style="width: 100%"></select>
            
            <label for="usuario">Usuario:</label>
            <select id="usuario" class="swal2-input" multiple style="width: 100%"></select>
            
            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select id="tipo_usuario" class="swal2-input" multiple style="width: 100%"></select>
        `,
        showCancelButton: true,
        preConfirm: () => {
            const popup = Swal.getPopup();  // Obtener el contenedor del SweetAlert

            // Obtener los valores seleccionados de los selectores dentro del modal
            const unidad = $(popup).find('#unidad').val() ? $(popup).find('#unidad').val().join(',') : '';
            const seccion = $(popup).find('#seccion').val() ? $(popup).find('#seccion').val().join(',') : '';
            const usuario = $(popup).find('#usuario').val() ? $(popup).find('#usuario').val().join(',') : '';
            const tipo_usuario = $(popup).find('#tipo_usuario').val() ? $(popup).find('#tipo_usuario').val().join(',') : '';

            // Retornar los datos formateados
            return {
                id_regla: rowData.id_regla,
                unidad: unidad,
                seccion: seccion,
                usuario: usuario,
                tipo_usuario: tipo_usuario
            };
        },
        didOpen: () => {
            // Inicializar Select2 con los valores actuales
            initializeSelect2('#unidad', dataMappings.unidad, rowData.unidad || "");
            initializeSelect2('#seccion', dataMappings.seccion, rowData.seccion || "");
            initializeSelect2('#usuario', dataMappings.usuarios, rowData.usuario || "");
            initializeSelect2('#tipo_usuario', dataMappings.tipo_usuario, rowData.tipo_usuario || "");
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Llamar a la función para actualizar la regla
            updateRegla(result.value);
        }
    });
}

    function initializeSelect2(selector, data, selectedData) {
        const selectElement = $(selector);
        selectElement.empty(); // Clear previous options
        for (const id in data) {
            if (data.hasOwnProperty(id)) {
                const isSelected = selectedData.split(',').includes(id) ? 'selected' : '';
                selectElement.append(`<option value="${id}" ${isSelected}>${data[id]}</option>`);
            }
        }
        selectElement.select2({ width: '100%' }); // Initialize Select2
    }

async function updateRegla(data) {
    try {
        // Crear un objeto FormData
        const formData = new FormData();
        formData.append('id_regla', data.id_regla);
        formData.append('unidad', data.unidad);
        formData.append('seccion', data.seccion);
        formData.append('usuario', data.usuario);
        formData.append('tipo_usuario', data.tipo_usuario);
        // Asegúrate de incluir el campo "asunto" si es necesario
        formData.append('asunto', data.asunto || ''); 

        // Realizar la solicitud de actualización con el formato adecuado
        const response = await fetch('../../controller/noticia.php?op=update_reglas', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        if (result.status === 'error') {
            Swal.fire('Error', result.message, 'error');
        } else {
            Swal.fire('Éxito', 'Regla actualizada con éxito', 'success');
            table.ajax.reload(); // Recargar los datos de la tabla después de la actualización
        }
    } catch (error) {
        console.error('Error updating rule:', error);
    }
}
});
