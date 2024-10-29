const url = {
  documento: "/controller/evento.php?op=get_documentos"
}


// Diccionario de iconos según la extensión del archivo
const iconosArchivo = {
  "pdf": "📄",
  "doc": "📃",
  "docx": "📃",
  "xls": "📊",
  "xlsx": "📊",
  "jpg": "🖼️",
  "png": "🖼️",
  "gif": "🖼️",
  "default": "📁"
}

function cargar_documentos(evento_id) {
  const form = new FormData();
  form.append("evento_id", evento_id);

  fetch(url.documento, {
    method: "POST",
    body: form
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    // Verificamos si el resultado es exitoso y contiene las propiedades esperadas
    if (data.status === "success" && data.result && (data.result.inicio_documento || data.result.cierre_documento)) {
      mostrarModalDocumentos(data.result);
    } else {
      Swal.fire("Sin Documentos", "No se encontraron documentos para este evento.", "info");
    }
  })
  .catch(error => {
    console.error("Error en la petición:", error);
    Swal.fire("Error", "Hubo un problema al conectar con el servidor.", "error");
  });
}

// Función para mostrar el modal con los documentos
function mostrarModalDocumentos(result) {
  const documentos = [
    { nombre: "Documento de Apertura", url: result.inicio_documento },
    { nombre: "Documento de Cierre", url: result.cierre_documento }
  ];

  // Generamos el contenido HTML
  let htmlContent = documentos.map(doc => {
    if (doc.url) {
      const extension = doc.url.split('.').pop().toLowerCase();
      const icono = iconosArchivo[extension] || iconosArchivo["default"];
      return `
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
          <span style="font-size: 24px; margin-right: 10px;">${icono}</span>
          <a href="/public/${doc.url}" download>${doc.nombre}</a>
        </div>
      `;
    }
    return ''; // Si no hay URL, no mostrar nada para este documento
  }).join('');

  // Si no hay contenido, mostrar un mensaje alternativo
  if (!htmlContent.trim()) {
    htmlContent = "<p>No hay documentos disponibles para este evento.</p>";
  }

  // Mostrar el modal usando SweetAlert2
  Swal.fire({
    title: 'Documentos del Evento',
    html: htmlContent,
    icon: 'info',
    confirmButtonText: 'Cerrar'
  });
}
