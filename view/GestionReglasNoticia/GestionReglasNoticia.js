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
            }}
        ]
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
                    dataMappings.seccion[item.seccion_id] = item.seccion_nom;
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

    // Function to apply filters
    function applyFilter(filterType, filterValues) {
        table.columns().search(''); // Clear all existing filters

        if (filterType && filterValues) {
            const filterIndex = {
                'unidad': 2,  // Assuming 'unidad' is in the 3rd column (index 2)
                'seccion': 3, // Assuming 'seccion' is in the 4th column (index 3)
                'usuario': 4, // Assuming 'usuario' is in the 5th column (index 4)
                'tipo_usuario': 5 // Assuming 'tipo_usuario' is in the 6th column (index 5)
            }[filterType];

            if (filterIndex !== undefined) {
                // Handle single ID or multiple IDs
                const values = filterValues.split(',').map(v => v.trim());
                const searchString = values.length > 1 ? `^(${values.join('|')})$` : values[0];
                table.column(filterIndex).search(searchString, true, false).draw(); // Apply filter to specific column
            }
        } else {
            fetchAllData(); // Fetch all data if no filter is applied
        }
    }

    // Function to fetch all data
    async function fetchAllData() {
        try {
            const response = await fetch('../../controller/noticia.php?op=get_reglas');
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            table.clear().rows.add(data).draw();  // Clear old data and add new data
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }
});
