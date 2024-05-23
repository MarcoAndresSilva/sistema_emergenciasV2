$.post('../../controller/seguridadPassword.php', { op: 'password_status' }, function(data) {
    if (data.length === 0) {
        $('#tabla-data').append('<tr><td colspan="100%">No hay datos disponibles</td></tr>');
        return;
    }

    // Transformar los datos para agregar estado y detalles
    const transformedData = data.map(item => {
        const { nombre, apellido, correo, mayuscula, minuscula, numero, especiales, largo } = item;
        let estado = 'seguro';
        let detalle = 'La contraseña es robusta';

        // Verificar las condiciones
        if (!mayuscula || !minuscula || !numero || !especiales || !largo) {
            estado = 'inseguro';
            detalle = 'La contraseña no cumple con: ';
            if (!mayuscula) detalle += 'mayúsculas, ';
            if (!minuscula) detalle += 'minúsculas, ';
            if (!numero) detalle += 'números, ';
            if (!especiales) detalle += 'caracteres especiales, ';
            if (!largo) detalle += 'longitud mínima, ';
            detalle = detalle.slice(0, -2);  // Eliminar la última coma y espacio
        }

        return { nombre, apellido, correo, estado, detalle };
    });

    // Generar las columnas dinámicamente
    var columns = [
        { title: 'Nombre', data: 'nombre' },
        { title: 'Apellido', data: 'apellido' },
        { title: 'Correo', data: 'correo' },
        { title: 'Estado', data: 'estado' },
        { title: 'Detalle', data: 'detalle' }
    ];

    // Inicializa DataTables
    var table = $.DataTable({
        data: transformedData,
        columns: columns,
        language:{
            url:'../registrosLog/spanishDatatable.json'
        }
    });
}, 'json').fail(function(error) {
    console.log('Error: ', error);
});
