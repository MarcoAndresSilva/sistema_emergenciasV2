async function getEventos() {
    const response = await fetch('../../controller/usuario.php?op=mis_eventos');
    const data = await response.json();
    inicializarDataTables(data);
}

// Inicializar DataTables con datos dinámicos
function inicializarDataTables(data) {
    $('#tabla_eventos').DataTable({
        data: data,
        columns: [
            { data: 'evento_id', title: 'ID Evento' },
            { data: 'categoria_nombre', title: 'Categoría' },
            { data: 'creador_nombre', title: 'Creador (Nombre)' },
            { data: 'creador_apellido', title: 'Creador (Apellido)' },
            { data: 'creador_correo', title: 'Correo del Creador' },
            { data: 'evento_direccion', title: 'Dirección' },
            { data: 'evento_descripcion', title: 'Descripción' },
            {
                data: null,
                title: 'Nivel',
                render: function (data, type, row) {
                    return renderNivel(row.nivel_id, row.nivel_nombre);
                }
            },
            {
                data: 'evento_id',
                title: 'Acciones',
                render: function (data, type, row) {
                    return renderAcciones(data);
                }
            }
        ],
        pageLength: 10,
        language: {
            url: "../registrosLog/spanishDatatable.json"
        }
    });
}

// Renderizar el nivel con estilos según el ID
function renderNivel(nivelId, nivelNombre) {
    var span = document.createElement('span');
    span.textContent = nivelNombre;

    switch (nivelId) {
        case 1:
            span.classList.add('badge', 'bg-danger');
            break;
        case 2:
            span.classList.add('badge', 'bg-warning', 'text-dark');
            break;
        case 3:
            span.classList.add('badge', 'bg-success');
            break;
        default:
            span.classList.add('badge', 'bg-secondary', 'text-white');
            break;
    }

    return span.outerHTML;
}

// Renderizar acciones
function renderAcciones(eventoId) {
    return `
        <a href="../EmergenciaDetalle/?ID=${eventoId}" class="btn btn-primary btn-sm"><i class='fa-regular fa-comments'></i> Ver Chat</a>
        <a href="../GenerarPdf/?id_evento=${eventoId}" class="btn btn-secondary btn-sm"><i class='fa-regular fa-file'></i> Informe</a>
    `;
}

// Crear el contenedor para DataTables
document.getElementById('informacion_evento').innerHTML = `
    <table id="tabla_eventos" class="table table-striped table-bordered" style="width:100%"></table>
`;

// Llamar a la función para cargar los eventos
getEventos();
