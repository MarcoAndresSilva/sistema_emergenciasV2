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
                $('#tabla-log').DataTable({
                    data: data,
                    columns: columns
                });
            }, 'json').fail(function(error) {
                console.log('Error: ', error);
            });
