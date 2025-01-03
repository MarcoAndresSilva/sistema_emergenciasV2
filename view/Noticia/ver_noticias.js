document.addEventListener("DOMContentLoaded", function() {
    const tableElement = document.getElementById("noticias-table");

    // Inicializar la tabla usando DataTables
    const noticiasTable = $(tableElement).DataTable({
        serverSide: false,
        ajax: {
            url: '../../controller/noticia.php?op=get_noticia',
            type: 'GET',
            dataSrc: function (json) {
                return json.map(noticia => [
                    noticia.asunto,
                    noticia.mensaje,
                    noticia.leido ? "Leído" : "No leído",
                    noticia.url,
                    noticia.id
                ]);
            }
        },
        columns: [
            { title: "Asunto" },
            { title: "Mensaje" },
            { title: "Estado" },
            {
                title: "Acciones",
                render: function(data, type, row) {
                    if (row[3] === "#") {
                        return `<button class="btn btn-sm btn-primary mark-as-read" data-id="${row[4]}">Marcar como leído</button>`;
                    } else {
                        return `<a href="${row[3]}" target="_blank" class="btn btn-sm btn-info">Ver más</a>`;
                    }
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
        },
        paging: true,
        searching: true,
        ordering: true
    });

    // Función para actualizar la tabla automáticamente
    function fetchNoticias() {
        noticiasTable.ajax.reload(null, false); // false evita el reseteo de la paginación
    }

    // Actualizar la tabla cada 10 segundos
    setInterval(fetchNoticias, 10000);

    // También actualiza la tabla si se hace clic en un botón
    document.addEventListener("click", function(event) {
        if (event.target.tagName.toLowerCase() === "button") {
            fetchNoticias();
        }
    });

    // Marcar noticia como leída
    $(document).on('click', '.mark-as-read', function() {
        const noticiaId = $(this).data('id');

        const formData = new URLSearchParams();
        formData.append('noticia_id', noticiaId);

        fetch('../../controller/noticia.php?op=read_noticia', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al marcar como leída: ' + response.status);
            }
            fetchNoticias(); // Recargar la tabla después de marcar como leída
        })
        .catch(error => console.error('Error al marcar como leída:', error));
    });
});

// Función para marcar todas las noticias como leídas
document.getElementById('mark-all-read').addEventListener('click', function() {
    // Obtener todas las noticias que no están marcadas como leídas
    const rows = $("#noticias-table").DataTable().rows().data();

    // Crear un array con los IDs de las noticias que no están leídas
    const noticiasNoLeidas = [];
    rows.each(function(rowData, index) {
        if (rowData[2] !== "Leído") {  // Estado no leído
            noticiasNoLeidas.push(rowData[4]);  // Agregar ID de la noticia
        }
    });

    // Si hay noticias no leídas, enviamos una solicitud para marcarlas como leídas
    if (noticiasNoLeidas.length > 0) {
        // Iterar sobre los IDs de las noticias no leídas y enviar una solicitud por cada uno
        noticiasNoLeidas.forEach(function(noticiaId) {
            const formData = new URLSearchParams();
            formData.append('noticia_id', noticiaId);  // Enviar el ID de la noticia

            fetch('../../controller/noticia.php?op=read_noticia', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error al marcar la noticia con ID ${noticiaId} como leída`);
                }
                // Aquí puedes agregar alguna lógica si lo necesitas para cada respuesta exitosa
                console.log(`Noticia con ID ${noticiaId} marcada como leída`);
            })
            .catch(error => console.error(`Error al marcar la noticia con ID ${noticiaId} como leída:`, error));
        });

        // Recargar la tabla después de intentar marcar todas las noticias como leídas
        fetchNoticias(); // Esta función recarga la tabla para reflejar los cambios
    } else {
        alert("No hay noticias no leídas para marcar.");
    }
});

