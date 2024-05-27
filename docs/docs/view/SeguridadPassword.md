# seguridad Password
Vista que muestra los datos del nivel de robuste de las contraseñas de los usuarios

## Index.php

*funcionalidades agregadas*:

- Filtrar por nombre
- Filtrar por usuario
- Filtrar por correo
- Marcar en rojo y verde las contraseñas que cumplan el tiempo

## Searchpass.js

las funciones principales que cumplen son

peticion de los datos al controlador

ajustar la vista para demostrar la seguridad y la vulneravidilas

filtrar los datos con buscadores

***requerimientos***

- Jquery: Para las peticiones post y funciones dinamicas
- api datatable: Para la tabla dinamica y funciones de filtrado

### fn_img (detalleTipo, DetalleTexto)

La funcion Retorna una estructura html con `<li><img src='ICONO'></li>` el cual segun el tipo de condicion se le agrega
un icono SVG del array `iconos` al tag `img`

```js

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
```
### Creando tabla
la funcion hace la consulta por el metodo `post` al controller [seguridadPassword](../controllers/seguridadPassword.md)
recibiendo una Array de los datos de todos los usuarios, el cual es ordenado y guardado en  `transformedData`

La tabla es captura en una variable global con el nombre `tableBody` se le agrega las filas con los datos ala tabla

con la variable table seguarda el formato transofrmado por el `API DataTable` generando una tabla dinamica con filtros.

``` js
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
```
### meses expirado

el codigo espera cualquier cambio en el formulario con la id `mesesexpiracion`
para los datos de la tabla que cumplan con el cliterio de los meses agregarle un color
rojo o verde segun el caso, la funcion agrega la clase de boodstrap `table-success` y `table-danger`
Si los dato son vacios se le quita las dos clases agregadas

``` js
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
```
### filtrar por estado

la funcion genera un filtrado acorde a las selecciones que tiene el usuario disponible

``` js
// Controlador de eventos para el cambio en #selectStatus
$('#selectStatus').on('change', function() {
    var selectedStatus = $(this).val();
    // Comprobar si la tabla está definida antes de intentar usarla
    if (table) {
        // Filtrar la tabla basada en el estado seleccionado
        table.column(3).search(selectedStatus).draw();
    }
});
```
