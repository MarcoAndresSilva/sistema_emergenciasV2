function fn_img(detalleTipo, detalleTexto){
    let iconos = {
        'mayúsculas': 'icon-letter-case-upper.svg',
        'minúsculas': 'icon-case-lower.svg',
        'números': 'icon-numbers.svg',
        'caracteres especiales': 'icon-at.svg',
        'longitud mínima': 'icon-number-8.svg',
        'seguro':'icon-shield-check.svg'
    };
    let img = iconos[detalleTipo];
    let html = `<li><img src='../../public/img/${img}'> ${detalleTexto}</li>`;
    return html;
}

$.post('../../controller/seguridadPassword.php', { op: 'password_status' }, function(data) {
    if (data.length === 0) {
        $('#table-data tbody').append('<tr><td colspan="100%">No hay datos disponibles</td></tr>');
        return;
    }

    console.log(data);
    const transformedData = data.map(item => {
        const { nombre, apellido, correo, mayuscula, minuscula, numero, especiales, largo, fecha } = item;
        let estado = 'seguro';
        let detalles = [];

        if (!mayuscula || !minuscula || !numero || !especiales || !largo) {
            estado = 'Vulnerable';
            if (!mayuscula) detalles.push(fn_img('mayúsculas', 'mayúsculas'));
            if (!minuscula) detalles.push(fn_img('minúsculas', 'minúsculas'));
            if (!numero) detalles.push(fn_img('números', 'números'));
            if (!especiales) detalles.push(fn_img('caracteres especiales', 'caracteres especiales'));
            if (!largo) detalles.push(fn_img('longitud mínima', 'longitud mínima'));
        }

        let detalle = detalles.length > 0 ? detalles.join('') : fn_img('seguro','La contraseña robusta') ;
        return { nombre, apellido, correo, estado, detalle, fecha };
    });

    var tableBody = $('#table-data tbody');
    console.table(transformedData);
    transformedData.forEach(function(rowData) {
        var row = $('<tr>');
        row.append($('<td>').text(rowData.nombre));
        row.append($('<td>').text(rowData.apellido));
        row.append($('<td>').text(rowData.correo));
        row.append($('<td>').text(rowData.estado));
        row.append($('<td>').text(rowData.fecha));
        row.append($('<td>').html(rowData.detalle));
        tableBody.append(row);
    });

    table = $('#table-data').DataTable({
        language:{
            url:'../registrosLog/spanishDatatable.json'
        }
    });
}, 'json').fail(function(error) {
    console.log('Error: ', error);
});
// Controlador de eventos para el cambio en #mesesexpiracion
$('#mesesexpiracion').on('change', function() {
    var selectedMonths = $(this).val();
    // Comprobar si la tabla está definida antes de intentar usarla
    if (table) {
        // Filtrar la tabla basada en el número de meses
        table.rows().every(function() {
            var data = this.data();
            // Acceder a la columna de 'meses' usando data[4]
            if (selectedMonths === '') {
                // Si el valor seleccionado es null, quitar todas las clases de colores
                $(this.node()).removeClass('table-danger table-success');
            } else if (data[4] >= selectedMonths) {
                // Agregar la clase 'table-danger' a la fila
                $(this.node()).removeClass('table-success').addClass('table-danger');
            } else {
                // Agregar la clase 'table-success' a la fila
                $(this.node()).removeClass('table-danger').addClass('table-success');
            }
        });
        table.draw();
    }
});
// Controlador de eventos para el cambio en #selectStatus
$('#selectStatus').on('change', function() {
    var selectedStatus = $(this).val();
    // Comprobar si la tabla está definida antes de intentar usarla
    if (table) {
        // Filtrar la tabla basada en el estado seleccionado
        table.column(3).search(selectedStatus).draw();
    }
});

