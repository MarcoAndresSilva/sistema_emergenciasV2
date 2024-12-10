async function getEventos(){
    const response = await fetch('../../controller/usuario.php?op=mis_eventos');
    const data = await response.json();
    renderTabla(data);
}

function renderTabla(data){
    var tabla = document.getElementById('informacion_evento');
    tabla.innerHTML = '';
    var tabla = document.createElement('table');
    tabla.classList.add('table', 'table-striped', 'table-bordered');
    var tr = document.createElement('tr');
    tr.innerHTML = '<th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Teléfono</th><th>Unidad</th><th>Sección</th><th>Estado</th>';
    tabla.appendChild(tr);
    data.forEach(function(row){
        var tr = document.createElement('tr');
        tr.innerHTML = '<td>' + row.id + '</td><td>' + row.nombre + '</td><td>' + row.apellido + '</td><td>' + row.correo + '</td><td>' + row.telefono + '</td><td>' + row.unidad + '</td><td>' + row.seccion + '</td><td>' + row.estado + '</td>';
        tabla.appendChild(tr);
    });
    tabla.classList.add('table', 'table-striped', 'table-bordered');
    document.getElementById('informacion_evento').appendChild(tabla);
}

getEventos();

