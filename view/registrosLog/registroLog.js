$.post('../../controller/registroLog.php', { op: 'registro_log' }, function(data) {
    // Verificar que data no esté vacío y sea un arreglo
    if (data.length === 0) {
        $('#tabla-log').append('<tr><td colspan="100%">No hay datos disponibles</td></tr>');
        return;
    }

    // Generar las columnas dinámicamente
    var columns = [];
    for (var key in data[0]) {
        columns.push({ title: key, data: key });
    }

    // Inicializa DataTables
    var table = $('#tabla-log').DataTable({
        data: data,
        columns: columns,
        initComplete: function () {
            var column = this.api().column(6); // Índice de la columna "operacion"

            var select = $('<select><option value="">todos</option></select>')
                .appendTo($(column.header()))
                .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });

            // Agregar lista de opciones
            column
                .data()
                .unique()
                .sort()
                .each(function (d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
        }
    });

}, 'json').fail(function(error) {
    console.log('Error: ', error);
});
