import { GeneradorPDF } from "./generar_pdf.js";
import { mapafijo } from "./mapafijo.js"

// Inicializa la clase de generación de PDF

function buscarDatosEvento(query) {
    const formData = new FormData();
    formData.append("id_evento", query);
    return fetch(`../../controller/evento.php?op=informacion_evento_completo`, {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error al obtener datos del evento: ${response.status}`);
        }
        return response.json();
    });
}

async function convertirImagenABase64(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Error al obtener la imagen: ${response.status}`);
        }
        const blob = await response.blob();
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.onerror = () => reject("Error al convertir imagen a Base64");
            reader.readAsDataURL(blob);
        });
    } catch (error) {
        console.error("Error en convertirImagenABase64:", error);
        throw error;
    }
}

// Función para renderizar contenido dinámico
async function renderizarContenido(contenedor, datos) {
    try {
        let date = new Date();

        contenedor.querySelectorAll("#numero-ticket").forEach(elemento => {
            elemento.textContent = datos.evento.ev_id;
        });
        contenedor.querySelector("#categoria").textContent = datos.evento.cat_nom;
        contenedor.querySelector("#descripcion").textContent = datos.evento.ev_desc;
        contenedor.querySelector("#nivel").textContent = datos.evento.niv_nom;
        contenedor.querySelector("#usuario-creador").textContent =  `${datos.creador["Nombre Completo"]} (${datos.creador.Unidad} - ${datos.creador.Seccion})`;
        contenedor.querySelector("#direccion").textContent = datos.evento.ev_direc;
        contenedor.querySelector("#latitud").textContent = datos.evento.ev_latitud;
        contenedor.querySelector("#longitud").textContent = datos.evento.ev_longitud;
        contenedor.querySelector("#fecha-inicio").textContent = datos.evento.ev_inicio;
        contenedor.querySelector("#fecha-cierre").textContent = datos.evento.ev_final || "En Proceso";
        contenedor.querySelector("#fecha-informe").textContent = date.toLocaleDateString("es-ES", {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        contenedor.querySelector("#lista-participantes").textContent = 
            datos.secciones_asignadas?.secciones?.length > 0
                ? datos.secciones_asignadas.secciones
                      .map(seccion => `${seccion.nombre} (${seccion.unidad})`)
                      .join(", ")
                : "Sin asignar";

        const imagenBase64 = await convertirImagenABase64(mapafijo(datos.evento.ev_latitud, datos.evento.ev_longitud));
        contenedor.querySelector("#image-mapa").src = imagenBase64;

        contenedor.style.display = "block";
    } catch (error) {
        console.error("Error al renderizar contenido:", error);
        alert("Hubo un problema al cargar los datos del evento.");
    }
}

// Manejar búsqueda y mostrar resultados
document.getElementById("search-btn").addEventListener("click", async () => {
    const id_evento = document.getElementById("id-evento").value.trim();
    if (!id_evento) {
        alert("Por favor, ingrese un ID de evento válido.");
        return;
    }

    try {
        const datosEvento = await buscarDatosEvento(id_evento);
        const pdfContenedor = document.getElementById("pdf-container");
        renderizarContenido(pdfContenedor, datosEvento);
    } catch (error) {
        console.error("Error al buscar:", error.message);
        alert("No se encontró el evento. Verificar ID.");
    }
});

// Verificar al cargar la página si ya hay un valor en el input
document.addEventListener("DOMContentLoaded", async () => {
    const idEventoInput = document.getElementById("id-evento");
    if (idEventoInput.value.trim()) {
        // Si hay un valor en el input, realizar la búsqueda automáticamente
        try {
            const datosEvento = await buscarDatosEvento(idEventoInput.value.trim());
            const pdfContenedor = document.getElementById("pdf-container");
            renderizarContenido(pdfContenedor, datosEvento);
        } catch (error) {
            console.error("Error al buscar:", error.message);
            alert("No se encontró el evento. Verificar ID.");
        }
    }
});

// Manejar generación de PDF
document.getElementById("generate-pdf-btn").addEventListener("click", () => {
    const pdfContenedor = document.getElementById("pdf-container");
    let nameFile = "Informe_Evento_ticket_" + document.getElementById("id-evento").value + ".pdf";
    const pdfGenerator = new GeneradorPDF({ filename: nameFile });
    pdfGenerator.generar(pdfContenedor);
});
