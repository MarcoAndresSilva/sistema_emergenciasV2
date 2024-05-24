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

    var table = $('#table-data').DataTable({
        language:{
            url:'../registrosLog/spanishDatatable.json'
        }
    });
}, 'json').fail(function(error) {
    console.log('Error: ', error);
});
