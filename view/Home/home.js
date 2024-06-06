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
      type: 'bar',
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
  let url = '../../controller/evento.php?op=get_datos_categoria_eventos_ultimos_30_dias';
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

//   google.charts.load("current", { packages: ["corechart"] });

//   google.charts.setOnLoadCallback(drawChart);

//   function drawChart() {
//     var dataTable = new google.visualization.DataTable();
//     dataTable.addColumn("string", "Fecha");
//     dataTable.addColumn("number", "Values");
//     // dataTable.addColumn({ type: "string", role: "tooltip", p: { html: true } });

//     //Variables del ejeX
//     var ejeX = [];
//     var largoEjeX = 0;
//     var ejeXMax = [];
//     var largoEjeXMax = 0;
    
//     //Variables del ejeY
//     var ejeY = [];
//     var largoEjeY = 0;
//     var ejeYMax = [];
//     var largoEjeYMax = 0;
//     // Funcion para cargar datos del grafico segun cantidad de emergencias por dia
//     $.post("../../controller/evento.php?op=evento-grafico-dashboard", function (data) {
//       data = JSON.parse(data);

//       var hoy = new Date(); // Se crea una nueva fecha con la fecha y hora actual
//       var dd = String(hoy.getDate()).padStart(2, '0');
//       var mm = String(hoy.getMonth() + 1).padStart(2, '0');
//       hoy = dd + '-' + mm; // Formato: 'YYYY-MM-DD'
//       if (Array.isArray(data) && data.length === 0){
//         data = [[hoy,0]]
//       }
//       //ingresa la cantidad de datos necesarios en el ejeX
//       var compara1 = 0;
//       for (var i = 0; i < data.length; i++) {
//         compara1 = data[i][0];
//         ejeX.push(compara1);
        
//       }
//       largoEjeX = ejeX.length;
//       for (var i = 0; i< largoEjeX; i++){
//         ejeXMax.push(ejeX[i]);
//       }
//       largoEjeXMax = ejeXMax.length;
      
//       //ingresa la cantidad de datos necesarios en el ejeY
//       var compara2 = 0;
//       for (var i = 0; i < data.length; i++) {
//         compara2 = data[i][1];
//         ejeY.push(compara2);
//       }
//       largoEjeY = ejeY.length;
//       for (var i = 0; i< largoEjeY; i++){
//         ejeYMax.push(ejeY[i]);
//       }
//       largoEjeYMax = ejeYMax.length;
//       var maxEjeY = Math.max.apply(null, ejeYMax);
//       maxEjeY++;
//       ejeYMax.push(maxEjeY)

//       // Verificar si los datos están en el formato correcto
//       if (Array.isArray(data)) {
//         // Agregar los datos obtenidos a la tabla de datos de Google Charts
//         dataTable.addRows(data);

//         var chart = new google.visualization.AreaChart(document.getElementById("chart_div"));
//         chart.draw(dataTable, options);
//       } else {
//           console.error("Los datos recibidos no están en el formato esperado.");
//       }
//     });
//     var options = {
//       height: 314,
//       legend: "none",
//       areaOpacity: 0.18,
//       axisTitlesPosition: "in",
//       hAxis: {
//         minValue:0,
//         maxValue:largoEjeXMax +1,
//         // minValue: ejeX[0],
//         title: "",
//         textStyle: {
//           color: "#fff",
//           fontName: "Proxima Nova",
//           fontSize: 12,
//           bold: true,
//           italic: false,
//         },
//         textPosition: "in",
//         ticks: ejeXMax,
        
//         gridlines: {
//           color: "#1ba0fc",
//           count: largoEjeXMax,
//         },
//       },
//       vAxis: {
//         minValue: 0,
//         textPosition: "in",
//         textStyle: {
//           color: "#fff",
//           fontName: "Proxima Nova",
//           fontSize: 10,
//           bold: true,
//           italic: false,
//         },
//         baselineColor: "#16b4fc",
//         ticks: ejeYMax,
//         gridlines: {
//           color: "#1ba0fc",
//           count: largoEjeYMax,
//         },
//       },
//       lineWidth: 2,
//       colors: ["#fff"],
//       curveType: "function",
//       pointSize: 5,
//       pointShapeType: "circle",
//       pointFillColor: "#f00",
//       backgroundColor: {
//         fill: "#008ffb",
//         strokeWidth: 0,
//       },
//       chartArea: {
//         top: 5,
//         // left: 15,
//         // bottom: 16, 
//         width: "100%",
//         height: "100%",
//       },
//       fontSize: 10,
//       fontName: "Proxima Nova",
//       tooltip: {
//         trigger: "selection",
//         isHtml: true,
//       }
      
//     };
// }
//   $(window).resize(function () {
//       drawChart();
//       setTimeout(function () {}, 1000);
//     });

  //////////////////////////////////////////////////////////////////////////////////////////


  //Funcion para cargar numeros a los recuadros de porcentajes Emergencias
  
  

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
