var map, heatmaps = {}, markers = {}, infoWindow;
var currentView = 'heatmap'; // Puede ser 'heatmap' o 'markers'
var categoryColors = {}; // Almacenará los colores asignados a cada categoría
var showPOIs = false; // Estado de visibilidad de los puntos de interés
const disabledCategories = ['last_tiendas', 'otros']; // Lista de categorías a desactivar
const activeCategories = new Set(); // Almacena las categorías activas

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
  });

  document.getElementById('toggleMapView').addEventListener('click', toggleView);
  document.getElementById('togglePOIs').addEventListener('click', togglePOIs);
}

function addHeatmapLayers(categories) {
  Object.keys(categories).forEach(function(category) {
    if (!categoryColors[category]) {
      categoryColors[category] = getRandomColor();
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
  Object.keys(categories).forEach(function(category) {
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
          fillOpacity: 0.6,
          strokeColor: categoryColors[category],
          strokeWeight: 1,
          scale: 7
        },
        title: 'Detalles de ' + category
      });

      marker.addListener('click', function() {
        showInfoWindow(marker.getPosition(), category);
      });

      return marker;
    });
  });
}

function showInfoWindow(latLng, category) {
  var content = '<strong>Detalles de ' + category + ':</strong><br>Descripción del evento...';
  infoWindow.setContent(content);
  infoWindow.setPosition(latLng);
  infoWindow.open(map);
}

function filterCategory(category, button) {
  if (disabledCategories.includes(category)) {
    return;
  }

  if (currentView === 'heatmap') {
    if (heatmaps[category]) {
      const isVisible = heatmaps[category].getMap();
      heatmaps[category].setMap(isVisible ? null : map);

      if (isVisible) {
        activeCategories.delete(category);
        button.classList.remove('btn-success');
      } else {
        activeCategories.add(category);
        button.classList.add('btn-success');
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
      } else {
        activeCategories.add(category);
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
  controlsDiv.innerHTML = '';

  Object.keys(categories).forEach(category => {
    if (disabledCategories.includes(category)) {
      return;
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
  poIsButton.textContent = showPOIs ? 'Ocultar Puntos de Interés' : 'Mostrar Puntos de Interés';
  poIsButton.classList.toggle('btn-active', showPOIs);
  poIsButton.classList.toggle('btn-inactive', !showPOIs);
}

function getRandomColor() {
  const letters = '0123456789ABCDEF';
  let color = '#';
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

window.onload = initMap;
