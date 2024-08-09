var map, heatmaps = {}, markers = {}, infoWindow;
var currentView = 'heatmap'; // Puede ser 'heatmap' o 'markers'
var categoryColors = {}; // Almacenará los colores asignados a cada categoría
var showPOIs = false; // Estado de visibilidad de los puntos de interés
const disabledCategories = ['last_tiendas', 'otros']; // Lista de categorías a desactivar
const activeCategories = new Set(); // Almacena las categorías activas
var bounds; // Almacena los límites de los puntos en el mapa
var originalRowPositions = {};

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: { lat: -33.6866, lng: -71.2166 }, // Coordenadas del centro de Melipilla
    mapTypeId: 'roadmap',
    styles: [
      {
        featureType: 'poi',
        elementType: 'labels',
        stylers: [{ visibility: showPOIs ? 'on' : 'off' }]
      },
      {
        featureType: 'poi.business',
        elementType: 'labels',
        stylers: [{ visibility: showPOIs ? 'on' : 'off' }]
      }
    ]
  });

  infoWindow = new google.maps.InfoWindow();

  fetchAndGroupData().then(groupedData => {
    addHeatmapLayers(groupedData);
    addMarkers(groupedData);
    createCategoryButtons(groupedData);
    generateSummaryTable(groupedData);
    adjustMapBounds();
  });

  document.getElementById('toggleMapView').addEventListener('click', toggleView);
  document.getElementById('togglePOIs').addEventListener('click', togglePOIs);
  document.getElementById('dateFilterButton').addEventListener('click', showDateFilterDialog);
}

async function showDateFilterDialog() {
  const { niveles, unidades } = await fetchFilterOptions();

  const nivelOptions = Array.isArray(niveles) ? niveles.map(nivel => `<option value="${nivel}">${nivel}</option>`).join('') : '';
  const unidadOptions = Array.isArray(unidades) ? unidades.map(unidad => `<option value="${unidad}">${unidad}</option>`).join('') : '';

  Swal.fire({
    title: 'Filtrar Eventos',
    html:
      '<label for="swal-startDate">Fecha de Inicio:</label>' +
      '<input type="date" id="swal-startDate" class="swal2-input">' +
      '<label for="swal-endDate">Fecha de Cierre:</label>' +
      '<input type="date" id="swal-endDate" class="swal2-input">' +
      '<label for="swal-nivel">Nivel:</label>' +
      `<select id="swal-nivel" class="swal2-select" multiple>${nivelOptions}</select>` +
      '<label for="swal-unidad">Unidad:</label>' +
      `<select id="swal-unidad" class="swal2-select" multiple>${unidadOptions}</select>`,
    showCancelButton: true,
    confirmButtonText: 'Aplicar Filtro',
    didOpen: () => {
      // Inicializar Select2 y ajustar el tamaño
      const $nivelSelect = $('#swal-nivel').select2({
        placeholder: "Selecciona niveles",
        width: '100%'
      }).next('.select2-container').find('.select2-selection').css('min-height', '38px');

      const $unidadSelect = $('#swal-unidad').select2({
        placeholder: "Selecciona unidades",
        width: '100%'
      }).next('.select2-container').find('.select2-selection').css('min-height', '38px');

      // Agregar estilos personalizados para el hover
      const style = document.createElement('style');
      style.innerHTML = `
        .select2-results__option--highlighted {
          color: blue !important;
        }
      `;
      document.head.appendChild(style);
    },
    preConfirm: () => {
      const startDate = Swal.getPopup().querySelector('#swal-startDate').value;
      const endDate = Swal.getPopup().querySelector('#swal-endDate').value;
      const nivelesSeleccionados = $('#swal-nivel').val();
      const unidadesSeleccionadas = $('#swal-unidad').val();

      return { startDate, endDate, niveles: nivelesSeleccionados, unidades: unidadesSeleccionadas };
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const { startDate, endDate, niveles, unidades } = result.value;
      applyAdvancedFilter(startDate, endDate, niveles, unidades);
    }
  });
}

async function fetchFilterOptions() {
  const url = '../../controller/evento.php?op=get_filters_evento_map';

  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    return {
      niveles: Array.isArray(data.niveles) ? data.niveles : [],
      unidades: Array.isArray(data.unidades) ? data.unidades : []
    };
  } catch (error) {
    console.error('Fetch error:', error);
    return { niveles: [], unidades: [] }; 
  }
}

function applyAdvancedFilter(startDate, endDate, niveles, unidades) {
  fetchAndGroupData(startDate, endDate, niveles, unidades).then(groupedData => {
    clearMapData();
    addHeatmapLayers(groupedData);
    addMarkers(groupedData);
    createCategoryButtons(groupedData);
    restoreActiveCategories();
    generateSummaryTable(groupedData);
    adjustMapBounds();
  });
}

function addHeatmapLayers(categories) {
  Object.keys(categories).forEach(category => {
    if (!categoryColors[category]) {
      categoryColors[category] = generateColorFromCategory(category);
    }

    const points = categories[category].map(item => new google.maps.LatLng(item.latitud, item.longitud));

    heatmaps[category] = new google.maps.visualization.HeatmapLayer({
      data: points,
      map: null,
      radius: 20
    });

    google.maps.event.addListener(heatmaps[category], 'click', function(event) {
      showInfoWindow(event.latLng, category);
    });

    google.maps.event.addListener(heatmaps[category], 'mousemove', function(event) {
      showInfoWindow(event.latLng, category);
    });
  });
}

function addMarkers(categories) {
  bounds = new google.maps.LatLngBounds();
  Object.keys(categories).forEach(category => {
    if (disabledCategories.includes(category)) {
      return;
    }

    markers[category] = categories[category].map(item => {
      const marker = new google.maps.Marker({
        position: { lat: item.latitud, lng: item.longitud },
        map: null,
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          fillColor: categoryColors[category],
          fillOpacity: 0.8,
          strokeColor: categoryColors[category],
          strokeWeight: 1,
          scale: 7
        },
        title: item.categoria
      });

      marker.addListener('click', function() {
        showInfoWindow(marker.getPosition(), category, item.detalles, item.img, item.unidad, item.fecha_inicio, item.fecha_cierre, item.id);
      });

      bounds.extend(marker.getPosition());
      return marker;
    });
  });
}

function showInfoWindow(latLng, category, details = 'Sin detalles', img = '', unidad, fecha_inicio, fecha_cierre,id_evento) {
  let content = `<div><strong>Unidad:</strong> ${unidad}<br><strong>Categoria:</strong> ${category}<br><strong>Detalles:</strong> ${details}</div>`;
  if (img) {
    content += `<div><img src="../../public/${img}" alt="Imagen" style="max-width: 200px; max-height: 150px;"></div>`;
  }
  content += `<br> <strong>Fecha Creacion:</strong> ${fecha_inicio}`
  content += `<br> <strong>Fecha Cierre:</strong> ${fecha_cierre}`
  content += `<br> <a class="btn btn-sm btn-info" href="../EmergenciaDetalle/?ID=${id_evento}">Seguimiento</a>`

  infoWindow.setContent(content);
  infoWindow.setPosition(latLng);
  infoWindow.open(map);
}

function filterCategory(category, button) {
  if (disabledCategories.includes(category)) {
    return;
  }

  const row = document.getElementById(`row-${category}`);

  if (!originalRowPositions[category] && row) {
    const tbody = row.parentNode;
    originalRowPositions[category] = Array.from(tbody.children).indexOf(row);
  }

  if (currentView === 'heatmap') {
    if (heatmaps[category]) {
      const isVisible = heatmaps[category].getMap();
      heatmaps[category].setMap(isVisible ? null : map);

      if (isVisible) {
        activeCategories.delete(category);
        button.classList.remove('btn-success');
        if (row) {
          row.classList.remove('table-success');
          restoreRowPosition(row);  // Restaurar la posición solo de la fila desactivada
        }
      } else {
        activeCategories.add(category);
        button.classList.add('btn-success');
        if (row) {
          row.classList.add('table-success');
          moveRowToTop(row);  // Mover la fila activada al principio
        }
      }
    }
  }

  if (currentView === 'markers') {
    if (markers[category]) {
      const areVisible = markers[category][0].getMap();
      markers[category].forEach(marker => marker.setMap(areVisible ? null : map));

      if (areVisible) {
        activeCategories.delete(category);
        button.classList.remove('btn-success');
        if (row) {
          row.classList.remove('table-success');
          restoreRowPosition(row);  // Restaurar la posición solo de la fila desactivada
        }
      } else {
        activeCategories.add(category);
        button.classList.add('btn-success');
        if (row) {
          row.classList.add('table-success');
          moveRowToTop(row);  // Mover la fila activada al principio
        }
      }
    }
  }
  adjustMapBounds();
}

function saveOriginalRowPosition(category, row) {
  if (!originalRowPositions[category] && row) {
    const tbody = row.parentNode;
    originalRowPositions[category] = Array.from(tbody.children).indexOf(row);
  }
}

function toggleHeatmapVisibility(category, button, row) {
  const isVisible = heatmaps[category].getMap();
  heatmaps[category].setMap(isVisible ? null : map);
  updateUI(category, button, row, isVisible);
}

function toggleMarkersVisibility(category, button, row) {
  const areVisible = markers[category][0].getMap();
  markers[category].forEach(marker => marker.setMap(areVisible ? null : map));
  updateUI(category, button, row, areVisible);
}
async function fetchAndGroupData(startDate = null, endDate = null, niveles = [], unidades = []) {
  const url = '../../controller/evento.php?op=get_evento_lat_lon';

  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();

    const nivelesArr = Array.isArray(niveles) ? niveles.map(nivel => nivel.toLowerCase()) : [];
    const unidadesArr = Array.isArray(unidades) ? unidades.map(unidad => unidad.toLowerCase()) : [];

    const filteredData = data.filter(item => {
      const itemDate = new Date(item.fecha_inicio);
      const start = startDate ? new Date(startDate) : null;
      const end = endDate ? new Date(endDate) : null;
      const matchesDate = (!start || itemDate >= start) && (!end || itemDate <= end);
      const matchesNivel = !nivelesArr.length || nivelesArr.some(nivel => item.nivel.toLowerCase().includes(nivel));
      const matchesUnidad = !unidadesArr.length || unidadesArr.some(unidad => item.unidad.toLowerCase().includes(unidad));

      return matchesDate && matchesNivel && matchesUnidad;
    });

    const groupedData = filteredData.reduce((acc, item) => {
      const { categoria } = item;
      if (!acc[categoria]) {
        acc[categoria] = [];
      }
      acc[categoria].push(item);
      return acc;
    }, {});

    return groupedData;

  } catch (error) {
    console.error('Fetch error:', error);
    return {};
  }
}

function createCategoryButtons(categories) {
  const controlsDiv = document.getElementById('controls');
  controlsDiv.innerHTML = '';

  let row;
  Object.keys(categories).forEach((category, index) => {
    if (disabledCategories.includes(category)) {
      return;
    }

    if (index % 3 === 0) {
      row = document.createElement('div');
      row.className = 'btn-group mb-2';
      controlsDiv.appendChild(row);
    }

    const button = document.createElement('button');
    button.className = 'btn btn-outline-primary';
    button.textContent = category.charAt(0).toUpperCase() + category.slice(1).replace(/([A-Z])/g, ' $1');
    button.onclick = () => filterCategory(category, button);

    const icon = createCategoryIcon(categoryColors[category]);
    button.prepend(icon);
    row.appendChild(button);
  });
}

function toggleView() {
  currentView = currentView === 'heatmap' ? 'markers' : 'heatmap';

  Object.keys(heatmaps).forEach(category => {
    if (currentView === 'heatmap') {
      if (activeCategories.has(category)) {
        heatmaps[category].setMap(map);
      }
    } else {
      heatmaps[category].setMap(null);
    }
  });

  Object.keys(markers).forEach(category => {
    if (currentView === 'markers') {
      if (activeCategories.has(category)) {
        markers[category].forEach(marker => marker.setMap(map));
      }
    } else {
      markers[category].forEach(marker => marker.setMap(null));
    }
  });

  const toggleMapViewButton = document.getElementById('toggleMapView');
  if (currentView === 'heatmap') {
    toggleMapViewButton.innerHTML = '<i class="fas fa-map-marker-alt"></i> Mapa de Dispersión';
  } else {
    toggleMapViewButton.innerHTML = '<i class="fa fa-bullseye"></i> Mapa de Calor';
  }
  adjustMapBounds();
}

function togglePOIs() {
  showPOIs = !showPOIs;
  map.setOptions({
    styles: [
      {
        featureType: 'poi',
        elementType: 'labels',
        stylers: [{ visibility: showPOIs ? 'on' : 'off' }]
      },
      {
        featureType: 'poi.business',
        elementType: 'labels',
        stylers: [{ visibility: showPOIs ? 'on' : 'off' }]
      }
    ]
  });

  const poIsButton = document.getElementById('togglePOIs');

  if (poIsButton) {

    if (showPOIs) {
      poIsButton.textContent = '';
      poIsButton.appendChild(createIcon('fa-eye-slash'));
      poIsButton.appendChild(document.createTextNode(' Ocultar Puntos de Interés'));

      poIsButton.classList.add('btn-active');
      poIsButton.classList.remove('btn-inactive');
    } else {
      poIsButton.textContent = '';
      poIsButton.appendChild(createIcon('fa-eye'));
      poIsButton.appendChild(document.createTextNode(' Mostrar Puntos de Interés'));

      poIsButton.classList.add('btn-inactive');
      poIsButton.classList.remove('btn-active');
    }
  } else {
    console.error('Botón con ID togglePOIs no encontrado.');
  }
}

function createIcon(iconClass) {
  const icon = document.createElement('i');
  icon.className = `fas ${iconClass} btn-icon`;
  return icon;
}

function generateColorFromCategory(category) {
  let hash = 0;
  for (let i = 0; i < category.length; i++) {
    hash = category.charCodeAt(i) + ((hash << 5) - hash);
  }
  let color = '#';
  for (let i = 0; i < 3; i++) {
    let value = (hash >> (i * 8)) & 0xFF;
    color += ('00' + value.toString(16)).slice(-2);
  }
  return color;
}

function applyDateFilter(startDate, endDate) {
  fetchAndGroupData(startDate, endDate).then(groupedData => {
    clearMapData();
    addHeatmapLayers(groupedData);
    addMarkers(groupedData);
    createCategoryButtons(groupedData);
    restoreActiveCategories();
    adjustMapBounds();
  });
}

function clearMapData() {
  Object.keys(heatmaps).forEach(category => heatmaps[category].setMap(null));
  Object.keys(markers).forEach(category => markers[category].forEach(marker => marker.setMap(null)));
}

function restoreActiveCategories() {
  activeCategories.forEach(category => {
    if (currentView === 'heatmap' && heatmaps[category]) {
      heatmaps[category].setMap(map);
    } else if (currentView === 'markers' && markers[category]) {
      markers[category].forEach(marker => marker.setMap(map));
    }
  });
}

function adjustMapBounds() {
  const activeMarkers = [];
  activeCategories.forEach(category => {
    if (markers[category]) {
      activeMarkers.push(...markers[category]);
    }
  });

  if (activeMarkers.length > 0) {
    const newBounds = new google.maps.LatLngBounds();
    activeMarkers.forEach(marker => newBounds.extend(marker.getPosition()));
    map.fitBounds(newBounds);
  } else if (bounds && !bounds.isEmpty()) {
    map.fitBounds(bounds);
  }
}
function createCategoryIcon(color) {
  const icon = document.createElement('i');
  icon.className = 'fa fa-circle';
  icon.style.color = color;
  icon.style.marginRight = '5px';
  return icon;
}
window.onload = initMap;

function generateSummaryTable(groupedData) {
  // Crear el contenedor de la tabla
  const tableContainer = document.getElementById('summaryTableContainer');
  tableContainer.innerHTML = ''; // Limpiar cualquier contenido previo

  // Crear la tabla y sus encabezados
  const table = document.createElement('table');
  table.className = 'table table-bordered';

  const thead = document.createElement('thead');
  const headerRow = document.createElement('tr');

  const headers = [
    'N°',
    'Categoría', 
    'Cantidad de Eventos',
    'Cantidad de Eventos por Nivel',
    'Cantidad de Eventos Abiertos',
    'Cantidad de Eventos Cerrados',
    'Primer Día de Inicio', 
    'Último Día de Inicio',
    'Primer Día de Cierre', 
    'Último Día de Cierre'
  ];

  headers.forEach(headerText => {
    const th = document.createElement('th');
    th.textContent = headerText;
    headerRow.appendChild(th);
  });

  thead.appendChild(headerRow);
  table.appendChild(thead);

  // Crear el cuerpo de la tabla
  const tbody = document.createElement('tbody');

  let rowIndex = 1;

  Object.keys(groupedData).forEach(category => {
    const events = groupedData[category];
    const firstStartDate = new Date(Math.min(...events.map(e => new Date(e.fecha_inicio))));
    const lastStartDate = new Date(Math.max(...events.map(e => new Date(e.fecha_inicio))));

    // Filtrar los eventos que no están en proceso para calcular las fechas de cierre
    const closedEvents = events.filter(e => e.fecha_cierre !== 'En Proceso');
    const endDates = closedEvents.map(e => new Date(e.fecha_cierre));
    const firstEndDate = endDates.length ? new Date(Math.min(...endDates)) : null;
    const lastEndDate = endDates.length ? new Date(Math.max(...endDates)) : null;

    // Contar eventos cerrados y abiertos
    const closedEventsCount = closedEvents.length;
    const openEventsCount = events.length - closedEventsCount;

    // Contar eventos por nivel
    const eventCountByLevel = events.reduce((acc, event) => {
      const nivel = event.nivel;
      if (!acc[nivel]) {
        acc[nivel] = 0;
      }
      acc[nivel]++;
      return acc;
    }, {});

    // Crear etiquetas Bootstrap para los eventos por nivel
    const eventCountByLevelBadges = Object.entries(eventCountByLevel)
      .map(([nivel, count]) => {
        const badge = document.createElement('span');
        badge.className = 'badge bg-primary me-1'; // Estilo y margen derecho
        badge.textContent = `${nivel}: ${count}`;
        return badge.outerHTML;
      })
      .join(' ');

    const row = document.createElement('tr');
    row.id = `row-${category}`;

    const cells = [
      rowIndex,
      category,
      events.length,
      eventCountByLevelBadges, // Mover aquí la cantidad de eventos por nivel
      openEventsCount,
      closedEventsCount,
      formatDate(firstStartDate),
      formatDate(lastStartDate),
      formatDate(firstEndDate, 'Sin cierre registrado'),
      formatDate(lastEndDate, 'Sin cierre registrado')
    ];

    cells.forEach(cellText => {
      const td = document.createElement('td');
      td.innerHTML = cellText; // Usar innerHTML para las etiquetas Bootstrap
      row.appendChild(td);
    });

    tbody.appendChild(row);
    rowIndex++;
  });

  table.appendChild(tbody);
  tableContainer.appendChild(table);
}

function formatDate(date, fallbackText = 'Fecha no disponible') {
  if (!date || isNaN(date.getTime())) {
    return fallbackText;
  }
  return date.toISOString().split('T')[0];
}

function getRowNumber(row) {
  return parseInt(row.cells[0].innerText, 10);
}

function restoreRowPosition(row) {
  const tbody = row.parentNode;
  const rowNumber = getRowNumber(row);

  let targetRow = null;

  for (let i = 0; i < tbody.children.length; i++) {
    const currentRow = tbody.children[i];
    const currentRowNumber = getRowNumber(currentRow);

    if (currentRowNumber > rowNumber) {
      targetRow = currentRow;
      break;
    }
  }

  if (targetRow) {
    tbody.insertBefore(row, targetRow);
  } else {
    tbody.appendChild(row);
  }
}

function restoreOriginalOrder() {
  const tableContainer = document.getElementById('summaryTableContainer');
  const table = tableContainer.querySelector('table');
  const tbody = table.querySelector('tbody');

  const orderedRows = Object.keys(originalRowPositions)
    .sort((a, b) => originalRowPositions[a] - originalRowPositions[b])
    .map(category => document.getElementById(`row-${category}`));

  orderedRows.forEach(row => {
    if (row) {
      tbody.appendChild(row);
    }
  });
}

function moveRowToTop(row) {
  const tbody = row.parentNode;
  tbody.insertBefore(row, tbody.firstChild);
}
