let selectedUnits = [];
const url = {
    datos: "../../controller/seccion.php?op=lista_secciones_con_unidad",
    editSeccion: "../../controller/seccion.php?op=update_seccion",
    deleteSeccion: "../../controller/seccion.php?op=eliminar_seccion",
    addSeccion: "../../controller/seccion.php?op=agregar_seccion",
    listaunidad: "../../controller/unidad.php?op=get_unidad",
    infoSeccion: "../../controller/seccion.php?op=info_seccion"
}

function fetchData() {
  fetch(url.datos)
    .then(response => response.json())
    .then(data => {
      createButtons(data);
      renderTable(data.flatMap(unidad => unidad.secciones));
    })
    .catch(error => console.error('Error al obtener los datos:', error));
}

function createButtons(data) {
  const buttonContainer = document.getElementById('button-container');
  buttonContainer.innerHTML = '';

  const showAllButton = createButton('Mostrar todas las unidades', 'btn-primary', showAllUnits);
  buttonContainer.appendChild(showAllButton);

  data.forEach(unidad => {
    const button = createButton(`Mostrar ${unidad.unidad}`, 'btn-secondary', () => toggleUnitFilter(unidad, button));
    buttonContainer.appendChild(button);
  });

  const applyFilterButton = createButton('Aplicar Filtro', 'btn-warning', () => applyFilter(data));
  buttonContainer.appendChild(applyFilterButton);
}

function createButton(text, className, onClick) {
  const button = document.createElement('button');
  button.textContent = text;
  button.classList.add('btn', className, 'm-2');
  button.onclick = onClick;
  return button;
}

function showAllUnits() {
  selectedUnits = [];
  document.querySelectorAll('.unit-button').forEach(btn => btn.classList.remove('btn-success', 'btn-secondary'));
  renderTable(data.flatMap(unidad => unidad.secciones));
}

fetchData();

function toggleUnitFilter(unidad, button) {
  const index = selectedUnits.indexOf(unidad.unidad);
  if (index > -1) {
    selectedUnits.splice(index, 1);
    button.classList.remove('btn-success');
    button.classList.add('btn-secondary');
  } else {
    selectedUnits.push(unidad.unidad);
    button.classList.remove('btn-secondary');
    button.classList.add('btn-success');
  }
}

function applyFilter(data) {
  const filteredSections = data.flatMap(unidad =>
    selectedUnits.includes(unidad.unidad) ? unidad.secciones : []
  );

  renderTable(filteredSections);
}

function renderTable(secciones) {
  const tableContainer = document.getElementById('table-container');
  tableContainer.innerHTML = '<table id="dataTable" class="display" style="width:100%"></table>';

  $('#dataTable').DataTable({
    data: secciones,
    language: {
      url: "../registrosLog/spanishDatatable.json"
    },
    responsive: true,
    columns: [
      { title: "ID", data: "sec_id" },
      { title: "Unidad", data: "sec_unidad_nombre" },
      { title: "Nombre", data: "sec_nombre" },
      { title: "Detalle", data: "sec_detalle" },
      {
        title: "Estado",
        data: "sec_est",
        render: function(data) {
          return formatState(data);
        }
      },
      {
        title: "Acciones",
        data: null,
        render: function(data, type, row) {
          return `
            <button class="btn btn-warning btn-sm" onclick="openModalEditar(${row.sec_id})">Editar</button>
            <button class="btn btn-danger btn-sm" onclick="eliminarSeccion(${row.sec_id})">Eliminar</button>
          `;
        }
      }
    ],
    order: [[1, 'asc'], [0, 'asc']],
    destroy: true
  });
}

function formatState(state){
  if (state === "1") {
    return '<span class="badge bg-success">Disponible</span>';
  } else if (state === "0") {
    return '<span class="badge bg-danger">Ocupado</span>';
  }
  return '<span class="badge bg-secondary">Desconocido</span>';
}

function openModalAgregar() {
  openModal(null);
}

function openModalEditar(secId) {
  const formData = new FormData();
  formData.append('id', secId);

  fetch(url.infoSeccion, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => openModal(data))
  .catch(error => console.error("Error al obtener datos de la sección:", error));
}

function openModal(seccion = null) {
  fetch(url.listaunidad)
    .then(response => response.json())
    .then(unidades => {
      const unidadOptions = unidades.result.map(unidad =>
        `<option value="${unidad.unid_id}" ${seccion && seccion.sec_unidad == unidad.unid_id ? "selected" : ""}>${unidad.unid_nom}</option>`
      ).join("");

      Swal.fire({
        title: seccion ? "Editar Sección" : "Agregar Sección",
        html: `
          <form id="sectionForm">
            <label>Unidad</label>
            <select id="unidadSelect" class="swal2-select">
            <option>---------------</option>
            ${unidadOptions}
            </select>
            <label>Nombre</label>
            <input id="nombreInput" class="swal2-input" placeholder="Nombre de la sección" value="${seccion ? seccion.sec_nombre : ""}">
            <label>Detalle</label>
            <input id="detalleInput" class="swal2-input" placeholder="Detalle de la sección" value="${seccion ? seccion.sec_detalle : ""}">
          </form>`,
        showCancelButton: true,
        confirmButtonText: seccion ? "Guardar cambios" : "Agregar",
        preConfirm: () => {
          const unidadId = document.getElementById('unidadSelect').value;
          const nombre = document.getElementById('nombreInput').value;
          const detalle = document.getElementById('detalleInput').value;

          if (!unidadId || !nombre || !detalle) {
            Swal.showValidationMessage("Todos los campos son obligatorios");
            return false;
          }
          
          return { secId: seccion ? seccion.sec_id : null, unidadId, nombre, detalle };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const { secId, unidadId, nombre, detalle } = result.value;
          const actionUrl = secId ? url.editSeccion : url.addSeccion;

          // Llamar al controlador con fetch
          fetch(actionUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ secId, unidadId, nombre, detalle })
          })
            .then(response => response.json())
            .then(responseData => {
              Swal.fire("Éxito", responseData.message, "success");
              fetchData(); // Recargar la tabla después de agregar/editar
            })
            .catch(error => Swal.fire("Error", "Hubo un problema con la solicitud", "error"));
        }
      });
    })
    .catch(error => console.error("Error al cargar unidades:", error));
}

// Funciones para eliminar
function eliminarSeccion(secId) {
  Swal.fire({
    title: "¿Estás seguro?",
    text: "Esta acción no se puede deshacer",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(url.deleteSeccion, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ secId })
      })
        .then(response => response.json())
        .then(responseData => {
          Swal.fire("Eliminado", responseData.message, "success");
          fetchData(); // Recargar la tabla después de eliminar
        })
        .catch(error => Swal.fire("Error", "No se pudo eliminar la sección", "error"));
    }
  });
}
