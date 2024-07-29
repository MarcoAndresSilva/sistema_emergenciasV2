var map, heatmaps = {}, infoWindow;

function initMap() {
  // Configuración inicial del mapa
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: { lat: -33.6866, lng: -71.2166 }, // Coordenadas del centro de Melipilla
    mapTypeId: 'roadmap' // Tipo de mapa (roadmap, satellite, hybrid, terrain)
  });

  // Inicializa el InfoWindow
  infoWindow = new google.maps.InfoWindow();

  // Añade capas de mapa de calor por categorías
  addHeatmapLayers();
}

function addHeatmapLayers() {
  // Datos de ejemplo divididos por categorías
  var categories = {
    incendios: [
      new google.maps.LatLng(-33.6844, -71.2167),
      new google.maps.LatLng(-33.68620473248334, -71.21801221635748)
      // Agrega más puntos de incendios según sea necesario
    ],
    caidaArbol: [
      new google.maps.LatLng(-33.68593488576285, -71.21709827911673),
      new google.maps.LatLng(-33.68468376717178, -71.21650864218722)
      // Agrega más puntos de caída de árboles según sea necesario
    ],
    corteLuz: [
      new google.maps.LatLng(-33.6869295008119, -71.22583412257963),
      new google.maps.LatLng(-33.68995340481311, -71.21016191729446)
      // Agrega más puntos de cortes de luz según sea necesario
    ]
  };

  // Recorre cada categoría y añade capas de mapa de calor correspondientes
  Object.keys(categories).forEach(function(category) {
    // Crea una nueva capa de mapa de calor para la categoría
    heatmaps[category] = new google.maps.visualization.HeatmapLayer({
      data: categories[category],
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

    // Alternar la clase active del botón
    button.classList.toggle('active', !isVisible);
  }
}
