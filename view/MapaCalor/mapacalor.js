var map, heatmaps = {}, markers = {}, infoWindow;
var currentView = 'heatmap'; // Puede ser 'heatmap' o 'markers'
var categoryColors = {}; // Almacenará los colores asignados a cada categoría
var showPOIs = false; // Estado de visibilidad de los puntos de interés
const disabledCategories = ['last_tiendas', 'otros']; // Lista de categorías a desactivar
const activeCategories = new Set(); // Almacena las categorías activas

function initMap() {
  // Configuración inicial del mapa
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: { lat: -33.6866, lng: -71.2166 }, // Coordenadas del centro de Melipilla
    mapTypeId: 'roadmap', // Tipo de mapa (roadmap, satellite, hybrid, terrain)
    styles: [ // Personaliza el estilo del mapa para ocultar ciertos elementos
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

  // Inicializa el InfoWindow
  infoWindow = new google.maps.InfoWindow();

  // Obtén los datos y añade capas de mapa de calor por categorías
  fetchAndGroupData().then(groupedData => {
    addHeatmapLayers(groupedData);
    addMarkers(groupedData);
    createCategoryButtons(groupedData);
  });

  // Configurar el botón de cambio de vista
  document.getElementById('toggleMapView').addEventListener('click', toggleView);

  // Configurar el botón para mostrar/ocultar puntos de interés
  document.getElementById('togglePOIs').addEventListener('click', togglePOIs);
}

function addHeatmapLayers(categories) {
  // Recorre cada categoría y añade capas de mapa de calor correspondientes
  Object.keys(categories).forEach(function(category) {
    // Asigna un color automáticamente si no está ya asignado
    if (!categoryColors[category]) {
      categoryColors[category] = getRandomColor();
    }

    // Convierte los datos de cada categoría a LatLng
    const points = categories[category].map(item => new google.maps.LatLng(item.latitud, item.longitud));

    // Crea una nueva capa de mapa de calor para la categoría
    heatmaps[category] = new google.maps.visualization.HeatmapLayer({
      data: points,
      map: null, // Empieza oculta, se mostrará según el filtro
      radius: 20
    });

    // Añade eventos para mostrar detalles al hacer clic o acercar el cursor
    google.maps.event.addListener(heatmaps[category], 'click', function(event) {
      showInfoWindow(event.latLng, category);
    });

    google.maps.event.addListener(heatmaps[category], 'mousemove', function(event) {
      showInfoWindow(event.latLng, category);
    });
  });
}

function addMarkers(categories) {
  // Recorre cada categoría y añade marcadores correspondientes
  Object.keys(categories).forEach(function(category) {
    // Si la categoría está desactivada, no añadimos los marcadores
    if (disabledCategories.includes(category)) {
      return; // No crear marcador para categorías desactivadas
    }

    markers[category] = categories[category].map(item => {
      const marker = new google.maps.Marker({
        position: { lat: item.latitud, lng: item.longitud },
        map: null, // Empieza oculto, se mostrará según el filtro
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          fillColor: categoryColors[category],
          fillOpacity: 0.6,
          strokeColor: categoryColors[category],
          strokeWeight: 1,
          scale: 7
        },
        title: 'Detalles de ' + category // Agrega un título o más detalles aquí
      });

      marker.addListener('click', function() {
        showInfoWindow(marker.getPosition(), category);
      });

      return marker;
    });
  });
}

function showInfoWindow(latLng, category) {
  // Crea un infowindow con los detalles específicos de la categoría
  var content = '<strong>Detalles de ' + category + ':</strong><br>Descripción del evento...'; // Agrega detalles específicos aquí
  infoWindow.setContent(content);

  // Muestra el infowindow en la posición del clic o del cursor
  infoWindow.setPosition(latLng);
  infoWindow.open(map);
}

function filterCategory(category, button) {
  // Muestra u oculta la capa de mapa de calor según la categoría seleccionada
  if (heatmaps[category] && currentView === 'heatmap') {
    var isVisible = heatmaps[category].getMap();
    heatmaps[category].setMap(isVisible ? null : map); // Alternar entre mostrar y ocultar

    // Alternar la clase active del botón y añadir/remover btn-success
    if (isVisible) {
      button.classList.remove('btn-success');
    } else {
      button.classList.add('btn-success');
    }
  if (disabledCategories.includes(category)) {
    return; // No aplicar filtros a categorías desactivadas
  }

  // Muestra u oculta los marcadores según la categoría seleccionada
  if (currentView === 'heatmap') {
    if (heatmaps[category]) {
      const isVisible = heatmaps[category].getMap();
      heatmaps[category].setMap(isVisible ? null : map); // Alternar entre mostrar y ocultar

      if (isVisible) {
        activeCategories.delete(category); // Eliminar de categorías activas
        button.classList.remove('btn-success');
      } else {
        activeCategories.add(category); // Añadir a categorías activas
        button.classList.add('btn-success');
      }
    }
  }
  if (currentView === 'markers') {
    if (markers[category]) {
      const areVisible = markers[category][0].getMap();
      markers[category].forEach(marker => marker.setMap(areVisible ? null : map)); // Alternar entre mostrar y ocultar

      if (areVisible) {
        activeCategories.delete(category); // Eliminar de categorías activas
        button.classList.remove('btn-success');
      } else {
        activeCategories.add(category); // Añadir a categorías activas
        button.classList.add('btn-success');
      }
    }
  }
}

async function fetchAndGroupData() {
  const url = '../../controller/evento.php?op=get_evento_lat_lon';

  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();

    // Agrupar datos por categoría
    const groupedData = data.reduce((acc, item) => {
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

  // Limpiar cualquier botón existente
  controlsDiv.innerHTML = '';

  // Crear un botón por cada categoría
  Object.keys(categories).forEach(category => {
    if (disabledCategories.includes(category)) {
      return; // No crear botón para categorías desactivadas
    }
    
    const button = document.createElement('button');
    button.className = 'btn btn-outline-primary';
    button.textContent = category.charAt(0).toUpperCase() + category.slice(1).replace(/([A-Z])/g, ' $1');
    button.onclick = () => filterCategory(category, button);
    controlsDiv.appendChild(button);
  });
}

function toggleView() {
  currentView = currentView === 'heatmap' ? 'markers' : 'heatmap';

  // Mostrar u ocultar capas según la vista actual
  Object.keys(heatmaps).forEach(category => {
    if (currentView === 'heatmap') {
      heatmaps[category].setMap(map);
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

  // Actualizar el texto y la clase del botón
  const poIsButton = document.getElementById('togglePOIs');
  poIsButton.textContent = showPOIs ? 'Ocultar Puntos de Interés' : 'Mostrar Puntos de Interés';
  poIsButton.classList.toggle('btn-active', showPOIs);
  poIsButton.classList.toggle('btn-inactive', !showPOIs);
}

// Generar colores aleatorios para categorías
function getRandomColor() {
  const letters = '0123456789ABCDEF';
  let color = '#';
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

// Inicializa el mapa al cargar la página
window.onload = initMap;
