$(document).ready(function() {

    // Obtener el elemento <a> por su ID
    var enlace = document.querySelector('.ControlEventos');
    // Añadir una clase al enlace
    enlace.classList.add('selected');

    cargarTablaGeneral();
});

function cargarTablaGeneral() {
      //Funcion para cargar los datos de las tablas
      $.post("../../controller/eventoDos.php?op=tabla-general",function(respuesta,status){

        // Parsear la respuesta JSON
        var data = JSON.parse(respuesta);
        
        $('#datos-criticos').html(data.critico);
        $('#datos-medios').html(data.medio);
        $('#datos-bajos').html(data.bajo);
        $('#datos-generales').html(data.comun);
    });
}

 //Variable id_evento
 $id_evento = 0;

//////////btn derivar//////////// 
$(document).on("click", "#btnPanelDerivar", function(e) {
    console.log('Button Derivar clicked');
    mostrarModal('#modalDerivar');
   
    // Obtener el valor del ID del evento desde la celda
    $id_evento = $(this).closest('tr').find('#id_evento_celda').attr('value');
    // Llama la funcion para mostrar el id
    mostrarIdEvento($id_evento);
    // Llama la funcion para consultar la categoria
    consultarCategoria($id_evento);
    //Llamar a la función para consultar el nivel de peligro
    consultarNivelPeligro($id_evento);
     //Llamar a la función para consultar las unidades disponibles
    consultarUnidadDisponible($id_evento);
});

$(document).on("click", "#btnPanelCerrar", function(e) {
    console.log('Button Cerrar clicked');
    mostrarModal('#modalCerrar');
});

function mostrarModal(modalId) {
    var modal = $(modalId);
    if (modal.length) {
        modal.removeClass('fade');
        modal.modal('show');
    } else {
        console.error('Modal not found: ' + modalId);
    }
}

// Función para obtener el id del evento y mostrarlo en el label
function mostrarIdEvento(ev_id) {
    $('#ev_id').text(ev_id);
}

// Función para mostrar el cat_nom en el label
function consultarCategoria(ev_id) {
    $.post("../../controller/categoria.php?op=get_cat_nom_by_ev_id", { ev_id: ev_id }, function(data, status) {
        try {
            var jsonData = JSON.parse(data);
            if (jsonData && jsonData.cat_nom) {
                $('#cat_nom').text(jsonData.cat_nom); // Usar .text() para establecer el contenido del div
            } else {
                console.log("No se encontró el cat_nom correspondiente para el evento con ID: " + ev_id);
            }
        } catch (error) {
            console.log("Error al analizar la respuesta JSON:", error);
        }
    });
}

function consultarNivelPeligro($id_evento) {
    var id_evento = $id_evento; // Obtén el ID del evento
    
    //Realiza la recopilación y añade las unidades al select
    $.post("../../controller/nivelPeligro.php?op=get_nivel_peligro",function(data,status){
        // Limpiar el contenido actual del select
        $('#niv_id').empty();
        
        $('#niv_id').html(data);
    });
    
    // Obtener unidades asignadas al evento
    $.post("../../controller/eventoDos.php?op=get_evento_id", { id_evento: id_evento }, function(asignadas, status) {
        
        // Marcar como seleccionado el nivel de peligro del evento
        if (typeof asignadas[0]['ev_niv'] !== 'undefined') {
            
            //Obtener el id del nivel de peligro que esta en el evento
            var nivelPeligroEvento = asignadas[0]['ev_niv'];
            
            // Seleccionar el nivel de peligro en el select
            $('#niv_id').val(nivelPeligroEvento);

        }        
    }, 'json');
}

//Funcion para consultar las unidades disponibles y mostrarlas en los check
function consultarUnidadDisponible($id_evento) {
    var unid_est = 1;
    var id_evento = $id_evento; // Obtén el ID del evento
    var est_id = 1;
    // Limpiar el contenido actual del div
    $('#unidadOptions').empty();
    $.post("../../controller/unidad.php?unidad=listar", {est_id: est_id },function(data,status){

        // Agregar opciones al div
        for (var i = 0; i < data.length; i++) {

            var option = '<div class="form-check">' +
                '<input class="form-check-input" type="checkbox" name="unidad" id="unidad_' + data[i].unid_id + '" value="' + data[i].unid_id + '">' +
                '<label class="form-check-label" for="unidad_' + data[i].unid_id + '">' + data[i].unid_nom + '</label>' +
                '</div>';

            $('#unidadOptions').append(option);
        }
        //Obtener unidades asignadas al evento
        $.post("../../controller/eventoUnidad.php?op=get_datos_eventoUnidad", {ev_id: id_evento }, function(asignadas,status){
            // Verificar si asignadas es un array antes de usar forEach
            if (Array.isArray(asignadas)) {
                asignadas.forEach(unidad => {
                    var unidadID = unidad['unid_id'];
                    $('#unidad_' + unidadID).prop('checked', true);
                });
            } else {
                console.log("La respuesta recibida no es un array: ", asignadas);
            }
        }, 'json');
        
    }, 'json');
}


//Funcion para Modificar las unidades y nivel de peligro de un evento
//Activar el boton para modificar todo
// $('.btnActualizarTodos').off('click').on('click',function(){
//     //Llama a la funcion para modificar las unidades y el nivel de peligro de un evento
//     console.log("click")
//     ActualizarTodo($id_evento);
// });

$(document).on("click", ".btnActualizarTodos", function() {
    console.log('Button actualizar clicked');
    ActualizarTodo($id_evento);
});



function ActualizarTodo($id_evento){
    console.log("actualizar todod clicked");
    mostrarIdEvento($id_evento);
    //Variables
    var errorActualizar = 0;
    var unid_ids = []; 
    var unid_antiguas = [];
    var str_antiguo = "";
    var str_nuevo = "";
    var error = 0;
    
    // Actualiza la unidad asignadas primero
    var ev_id = $id_evento;
    
    // Esto devuelve un array con las IDs seleccionadas
    var unid_ids = []; 
    $('#unidadOptions input:checked').each(function ( ) {
        unid_ids.push($(this).val());
        });
        //Generación de Array de unidades ya asignadas al evento (antiguo)
        $.post("../../controller/eventoUnidad.php?op=get_datos_eventoUnidad", {ev_id :ev_id}, function(data,status){
            if (data.error){
                console.log("No hay unidades antiguas");
                str_antiguo = "No hay unidades asignadas";
            }else {
                // datos = JSON.parse(data); 
                unid_antiguas = data.map(function(item) {
                return item.unid_id;
                });
                
                str_antiguo = unid_antiguas.join(',');
                
                //recorrer array antiguo y asignando cada valor a     antigua_unid_id
                unid_antiguas.forEach(unid_id => {
                    
                    //Borrar cada valor en la tabla relacionada al ev_id
                    $.post("../../controller/eventoUnidad.php?op=delete_unidad", {ev_id :ev_id, unid_id :unid_id}, function(data,status){
                        console.log("Array delete unidades asignadas");
                        console.log(data);
                        if (data == 0) {
                            error ++;
                        }
                    });
                    
                });
            }
            
            str_nuevo = unid_ids.join(',');
            if(str_nuevo == ""){
                str_nuevo = "No hay unidades";
            }
            //Fecha y Hora
            var ev_final = new Date();
            var año = ev_final.getFullYear();
            var mes = ev_final.getMonth() + 1; // Mes en JavaScript es 0-indexado, así que suma 1
            var dia = ev_final.getDate();
            var horas = ev_final.getHours();
            var minutos = ev_final.getMinutes();
            var segundos = ev_final.getSeconds();
            // Formatear la fecha y hora como desees
            var fechaFormateada = año + '-' + mes + '-' + dia + ' ' + horas + ':' + minutos + ':' + segundos;
            var fec_cambio = fechaFormateada;
            
            $.post("../../controller/eventoUnidad.php?op=reporte_actualizacion", {ev_id :ev_id, str_antiguo :str_antiguo, str_nuevo :str_nuevo,fec_cambio :fec_cambio}, function(data,status){
                if(data == 0){
                    console.log("Error en insert reporte_actualizacion");
                    error ++;
                }
            });
            
            //recorrer array nuevo y asignando cada valor a     unid_id
            unid_ids.forEach(unid_id => {
                $.post("../../controller/eventoUnidad.php?op=insert_asignacion_unidades", { ev_id: ev_id, unid_id: unid_id }, function(data,status){
                    if (data == 0) {
                        error ++;
                    }
                });
                
            });

        },'json' );
        if(error > 0){
            console.log("Error: Unidades no actualizadas");
            errorActualizar += 1;
        }
        
        // Esto devuelve Id del Nivel de Peligro seleccionado
        var ev_niv = $('#niv_id').val();
        
        //Actualizar NivelPeligro
        //Respuesta de la Consulta del nivel de peligro del evento
        var RespuestaNivelPeligro = $.post("../../controller/eventoDos.php?op=update_nivelpeligro_evento", { ev_id: ev_id, ev_niv: ev_niv });
        
        //Validacion de respuestas y alertas
        $.when(RespuestaNivelPeligro).done(function ( data2) {
            
            if (data2[0] == 1) {
                console.log("Actualizacion del nivel de peligro exitosa");
            } else {
                console.log("Nivel de peligro no realizada");
                errorActualizar += 1;
            }

            if(errorActualizar === 0){
                $('#id_evento').val('');
                $('#niv_id').val(0);
                $('#unidadOptions input:checked').prop('checked',false);
                swal("Actualizado ","Evento actualizado correctamente","success");
            }else{
                swal("Error: Al actualizar el Evento","Error en la funcion Unidades o Nivel de Peligro","error");
            }
            window.location.reload();
        });

        // window.location.reload();
        
    }
        
    
    