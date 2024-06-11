$(document).ready(function () {

  
  // Obtener el elemento <a> por su ID
  var enlace = document.querySelector('.home');

  enlace.classList.add('selected');

  $(".panel").lobiPanel({
    sortable: true,
  });

  $(".panel").on("dragged.lobiPanel", function (ev, lobiPanel) {
    $(".dahsboard-column").matchHeight();
  });

  // Grafico nuevo con ChartJS
  var ctx = document.getElementById('myChart');
  var myChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
          labels: [], // Etiquetas de los eventos
          datasets: [{
              label: 'Cantidad de Eventos por mes',
              backgroundColor: ['#D37B7E', '#8DC327', '#42A5F5', '#F66909', '#995DEA', '#FDC006', '#90CAF9', '#64B5F6', '#42A5F5', '#2196F3', '#0D47A1'],
              borderColor: 'black',
              borderWidth: 1,
              data: [] // Cantidad de eventos por categoría
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  });
  
  //en el host en el host emergencias.melipilla.cl/eventos.php;
  let url = 'http://localhost/sistema_emergenciasV2/eventos.php';
  fetch(url)
      .then(response => response.json())
      .then(datos => mostrar(datos))
      .catch(error => console.log(error));
  
      const mostrar = (eventos) => {
        // Limpiar los datos existentes en el gráfico
        myChart.data.labels = [];
        myChart.data.datasets[0].data = [];
    
        let otrosData = 0; // Variable para almacenar la cantidad de eventos de la categoría "Otros"
    
        // Procesar los datos recibidos y asignarlos al gráfico
        eventos.forEach(element => {
            if (element.cat_nom === 'Otros') {
                otrosData = parseInt(element.cantidad_eventos); // Almacenar la cantidad de eventos de la categoría "Otros"
            } else {
                myChart.data.labels.push(element.cat_nom); // Agregar el nombre de la categoría como etiqueta
                myChart.data.datasets[0].data.push(parseInt(element.cantidad_eventos)); // Agregar la cantidad de eventos como dato
            }
        });
    
        // Agregar la categoría "Otros" al final del gráfico
        if (otrosData > 0) {
            myChart.data.labels.push('Otros');
            myChart.data.datasets[0].data.push(otrosData);
        }
    
        // Actualizar el gráfico
        myChart.update();
    }

  /////////////RECUADROS INFO/////////////////7
  $.post("../../controller/evento.php?op=cantidad-eventos",function(respuesta,status){

    // Parsear la respuesta JSON
    var data = JSON.parse(respuesta);
    // Asignar los valores a los recuadros
    $('#number-open').html(data.eventos_abiertos);
    $('#number-close').html(data.eventos_cerrados);
    $('#porcentaje-emer-open').html(data.porcentaje_abiertas + "%");
    $('#porcentaje-emer-close').html(data.porcentaje_cerradas + "%");
  
  });

// Función para cargar números y porcentajes a los recuadros de emergencias
$.post("../../controller/evento.php?op=cantidad-segun-nivel-peligro", function(respuesta, status) {
  // Verificar el estado de la respuesta
  if (status === "success") {
      // Parsear la respuesta JSON
      var data = JSON.parse(respuesta);

      // Asignar los valores a los recuadros
      $('#number-eventos-criticos-medios').html(data.totalCriticosMedios);
      $('#number-eventos-bajas-comunes').html(data.totalBajasComunes);

      // Calcular el porcentaje de emergencias críticas y medias
      var porcentajeCriticosMedios = (data.totalCriticosMedios / (data.totalCriticosMedios + data.totalBajasComunes)) * 100;
      $('#porcentaje-criticos-medios').html(porcentajeCriticosMedios.toFixed(0) + "%");

      // Calcular el porcentaje de emergencias bajas y comunes
      var porcentajeBajasComunes = (data.totalBajasComunes / (data.totalCriticosMedios + data.totalBajasComunes)) * 100;
      $('#porcentaje-bajas-comunes').html(porcentajeBajasComunes.toFixed(0) + "%");
  } else {
      console.error("Error al obtener los datos del servidor.");
  }
}).fail(function() {
  console.error("Error al enviar la solicitud al servidor.");
});

  //////////////////////////////////////////TABLAS INFO////////////////////////////////////////////////
  // Funcion para cargar el grafico principal
  $.post("../../controller/evento.php?op=tablas-dashboard",function(respuesta,status){
    
    // Parsear la respuesta JSON
    var data = JSON.parse(respuesta);
    
    // Asignar los valores a los recuadros
    $('#critico-medio').html(data.criticoYmedio);
    $('#bajo-comun').html(data.bajoYcomun);
 
  });

  //////////////////////////////////////////////////////////////////////////////////////////
  // Funcion para cargar el lateral del grafico principal
  $.post("../../controller/evento.php?op=cantidad-segun-nivel-peligro",function(respuesta,status){
    // Parsear la respuesta JSON
    var data = JSON.parse(respuesta);

    // Asignar los valores a los recuadros
    $('#contador-critico').html(data.contadorCritico);
    $('#contador-medio').html(data.contadorMedio);
    $('#contador-bajo').html(data.contadorBajo);
    $('#contador-comun').html(data.contadorComun);
 
  });
});
