console.log("institucionesemergencias.js");

let nivelPeligro = []; // Variable global para almacenar los niveles de peligro

// Obtener los niveles de peligro
$.get("../../controller/nivelPeligro.php", { op: "get_nivel_peligro_json" },
    function (data, textStatus, jqXHR) {
        console.log(data);
        if (data && Array.isArray(data)) {
            nivelPeligro = data;
        } else {
            console.error("Datos nivel inválido:", data);
        }
    },
    "json"
).fail(function(jqXHR, textStatus, errorThrown) {
    console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
});

// Obtener los datos de categoría y actualizar la tabla
$.get("../../controller/categoria.php", { op: "cateogia_nivel" },
    function (data, textStatus, jqXHR) {
        if (data && Array.isArray(data)) {
            actualizarTabla(data);
        } else {
            console.error("Datos categoría inválidos:", data);
        }
    },
    "json"
).fail(function(jqXHR, textStatus, errorThrown) {
    console.error("Error en la solicitud AJAX de categorías:", textStatus, errorThrown);
});

function actualizarTabla(datos) {
    // Selecciona el cuerpo de la tabla
    let tbody = $("#miTabla tbody");
    
    // Limpia cualquier contenido existente en el cuerpo de la tabla
    tbody.empty();
    const buttonedit   ='<button id="buttonedit"   class="btn btn-warning" type="button"><img src="../../public/img/edit.svg"></button>'
    const buttondelete ='<button id="buttondelete" class="btn btn-danger" type="button"><img src="../../public/img/trash.svg"></button>'
    const buttons = buttonedit+buttondelete
    // Recorre los datos recibidos y crea nuevas filas para la tabla
    datos.forEach(function (fila) {
        let tr = $("<tr></tr>");
        tr.append($("<td></td>").text(fila.cat_id));
        tr.append($("<td></td>").text(fila.cat_nom));
        
        // Crear el select para niveles de peligro
        let selectHtml = $("<select class='form-select'></select>");
        nivelPeligro.forEach(function (nivel) {
            let option = $("<option></option>")
                .val(nivel.ev_niv_id)
                .text(nivel.ev_niv_nom);
            if (nivel.ev_niv_id == fila.ev_niv_id) {
                option.attr("selected", "selected");
            }
            selectHtml.append(option);
        });
        tr.append($("<td></td>").append(selectHtml));
        tr.append($("<td></td>").append(buttons));
        tbody.append(tr);
    });
}

// Función para agregar el evento change a los selects
    $("body").on("change", ".form-select", function() {
        console.log("change")
        let tr = $(this).closest("tr");
        let cat_id = tr.find("td").eq(0).text();
        let cat_nom = tr.find("td").eq(1).text();
        let ev_niv_id = $(this).val();
        let data ={
            op:"update_categoria",
            cat_id: cat_id,
            cat_nom: cat_nom,
            ev_niv_id: ev_niv_id
        }
        console.table(data)
        // Enviar datos mediante una solicitud POST
        $.post("../../controller/categoria.php",data , function(response) {
            // Manejar la respuesta del servidor
            if (response.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.mensaje
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.mensaje
                });
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en la solicitud: ' + textStatus
            });
        });
    });
  // Agregar funcionalidad al botón para mostrar la ventana SweetAlert2
  $('#addButton').on('click', function() {
    let selectOptions = nivelPeligro.map(function(item) {
        return `<option value="${item.ev_niv_id}">${item.ev_niv_nom}</option>`;
    }).join('');

    Swal.fire({
        title: 'Agregar Categoría',
        html: `
            <input type="text" id="cat_nom" class="swal2-input" placeholder="Nombre de la Categoría">
            <select id="ev_niv_id" class="swal2-select">
                ${selectOptions}
            </select>
        `,
        showCancelButton: true,
        confirmButtonText: 'Agregar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            return {
                cat_nom: $('#cat_nom').val(),
                ev_niv_id: $('#ev_niv_id').val()
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('../../controller/categoria.php', {
                op: 'add_categoria',
                cat_nom: result.value.cat_nom,
                ev_niv_id: result.value.ev_niv_id
            }, function(response) {
                // Aquí puedes manejar la respuesta del servidor
                if (response.status === 'success') {
                    Swal.fire('¡Categoría Agregada!', '', 'success');
                    // Actualizar la tabla después de agregar la categoría
                    $.get("../../controller/categoria.php", { op: "cateogia_nivel" },
                        function (data, textStatus, jqXHR) {
                            if (data && Array.isArray(data)) {
                                actualizarTabla(data);
                            } else {
                                console.error("Datos categoría inválidos:", data);
                            }
                        },
                        "json"
                    ).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error("Error en la solicitud AJAX de categorías:", textStatus, errorThrown);
                    });
                } else {
                    Swal.fire('Error al agregar la categoría', response.mensaje, 'error');
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire('Error al agregar la categoría', 'Error en la solicitud: ' + textStatus, 'error');
            });
        }
    });
});
// Función para manejar el evento de clic en el botón de borrar
$("body").on("click", "#buttondelete", function() {
    // Obtener el ID de la categoría que se va a eliminar
    let cat_id = $(this).closest("tr").find("td:eq(0)").text();

    // Mostrar confirmación antes de eliminar
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, bórralo'
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar el cat_id al servidor para eliminar la categoría
            $.post("../../controller/categoria.php", { op: "delete_categoria", cat_id: cat_id },
                function(response) {
                    // Manejar la respuesta del servidor
                    if (response.status === "success") {
                        // Mostrar mensaje de éxito
                        Swal.fire('¡Eliminado!', 'La categoría ha sido eliminada.', 'success');
                        // Actualizar la tabla después de eliminar la categoría
                        $.get("../../controller/categoria.php", { op: "cateogia_nivel" },
                            function(data, textStatus, jqXHR) {
                                if (data && Array.isArray(data)) {
                                    actualizarTabla(data);
                                } else {
                                    console.error("Datos categoría inválidos:", data);
                                }
                            },
                            "json"
                        ).fail(function(jqXHR, textStatus, errorThrown) {
                            console.error("Error en la solicitud AJAX de categorías:", textStatus, errorThrown);
                        });
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire('Error', 'No se pudo eliminar la categoría.', 'error');
                    }
                },
                "json"
            ).fail(function(jqXHR, textStatus, errorThrown) {
                // Mostrar mensaje de error si la solicitud falla
                Swal.fire('Error', 'Error en la solicitud: ' + textStatus, 'error');
            });
        }
    });
});

// Función para manejar el evento de clic en el botón de editar
$("body").on("click", "#buttonedit", function() {
    // Obtener los datos de la fila actual
    let tr = $(this).closest("tr");
    let cat_id = tr.find("td:eq(0)").text();
    let cat_nom = tr.find("td:eq(1)").text();
    let ev_niv_id = tr.find("select").val();

    // Mostrar el formulario de edición usando SweetAlert2
    Swal.fire({
        title: 'Editar Categoría',
        html: `
            <input type="hidden" id="edit_cat_id" value="${cat_id}">
            <input type="text" id="edit_cat_nom" class="swal2-input" value="${cat_nom}">
            <select id="edit_ev_niv_id" class="swal2-select">
                ${nivelPeligro.map(item => `<option value="${item.ev_niv_id}" ${item.ev_niv_id === ev_niv_id ? 'selected' : ''}>${item.ev_niv_nom}</option>`).join('')}
            </select>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            return {
                cat_id: $('#edit_cat_id').val(),
                cat_nom: $('#edit_cat_nom').val(),
                ev_niv_id: $('#edit_ev_niv_id').val()
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar los datos al servidor para actualizar la categoría
            $.post("../../controller/categoria.php", {
                op: 'update_categoria',
                cat_id: result.value.cat_id,
                cat_nom: result.value.cat_nom,
                ev_niv_id: result.value.ev_niv_id
            }, function(response) {
                // Manejar la respuesta del servidor
                if (response.status === 'success') {
                    Swal.fire('¡Categoría Actualizada!', '', 'success');
                    // Actualizar la tabla después de editar la categoría
                    $.get("../../controller/categoria.php", { op: "cateogia_nivel" },
                        function (data, textStatus, jqXHR) {
                            if (data && Array.isArray(data)) {
                                actualizarTabla(data);
                            } else {
                                console.error("Datos categoría inválidos:", data);
                            }
                        },
                        "json"
                    ).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error("Error en la solicitud AJAX de categorías:", textStatus, errorThrown);
                    });
                } else {
                    Swal.fire('Error al editar la categoría', response.mensaje, 'error');
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire('Error al editar la categoría', 'Error en la solicitud: ' + textStatus, 'error');
            });
        }
    });
});
