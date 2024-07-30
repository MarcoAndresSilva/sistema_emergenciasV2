var map, heatmaps = {}, infoWindow;

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
    createCategoryButtons(groupedData);
  });
}

function addHeatmapLayers(categories) {
  // Recorre cada categoría y añade capas de mapa de calor correspondientes
  Object.keys(categories).forEach(function(category) {
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
  if (heatmaps[category]) {
    var isVisible = heatmaps[category].getMap();
    heatmaps[category].setMap(isVisible ? null : map); // Alternar entre mostrar y ocultar

    // Alternar la clase active del botón y añadir/remover btn-success
    if (isVisible) {
      button.classList.remove('btn-success');
    } else {
      button.classList.add('btn-success');
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
    const button = document.createElement('button');
    button.className = 'btn';
    button.textContent = category.charAt(0).toUpperCase() + category.slice(1).replace(/([A-Z])/g, ' $1');
    button.onclick = () => filterCategory(category, button);
    controlsDiv.appendChild(button);
  });
}

// Inicializa el mapa cuando el documento está listo
document.addEventListener("DOMContentLoaded", initMap);
