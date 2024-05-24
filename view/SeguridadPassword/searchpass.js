$.post('../../controller/seguridadPassword.php', { op: 'password_status' }, function(data) {
    if (data.length === 0) {
        $('#table-data tbody').append('<tr><td colspan="100%">No hay datos disponibles</td></tr>');
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

    // Generar las filas de la tabla dinámicamente
    var tableBody = $('#table-data tbody');
    transformedData.forEach(function(rowData) {
        var row = $('<tr>');
        row.append($('<td>').text(rowData.nombre));
        row.append($('<td>').text(rowData.apellido));
        row.append($('<td>').text(rowData.correo));
        row.append($('<td>').text(rowData.detalle));
        tableBody.append(row);
    });

    // Inicializa DataTables
    var table = $('#table-data').DataTable({
        language:{
            url:'../registrosLog/spanishDatatable.json'
        }
    });
}, 'json').fail(function(error) {
    console.log('Error: ', error);
});
