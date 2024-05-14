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
    
    // Recorre los datos recibidos y crea nuevas filas para la tabla
    datos.forEach(function (fila) {
        let tr = $("<tr></tr>");
        tr.append($("<td></td>").text(fila.cat_id));
        tr.append($("<td></td>").text(fila.cat_nom));
        tr.append($("<td></td>").text(fila.est));
        
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
        tbody.append(tr);
    });
}
