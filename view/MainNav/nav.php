
<nav class="side-menu">
    <ul class="side-menu-list">

        <li class="blue-dirty">
            <a class="home" href="../Home/">
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Inicio</span>
            </a>
        </li>
        <li class="blue-dirty">
            <a class="NuevoEvento" href="../NuevoEvento/">
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Nuevo Evento</span>
            </a>
        </li>
        <li class="blue-dirty">
            <a class="Historial" href="../Historial/">
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Historial </span>
            </a>
        </li>
        
        <?php 
        if ($_SESSION["usu_tipo"] == 1){
        ?> 
        
        <!-- Evento -->
        <li class="blue-dirty">
            <a class="ControlEventos" href="../ControlEventos/">
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl"> Eventos</span>
            </a>
        </li>
        <!-- Mapa -->
        <!--<li class="blue-dirty">-->
        <!--    <a class="Map" href="../Map/">-->
        <!--        <span class="glyphicon glyphicon-asterisk"></span>-->
        <!--        <span class="lbl"> Mapa</span>-->
        <!--    </a>-->
        <!--</li>-->
        <!-- Fin Evento -->

        <!-- Parametría -->
        <li class="blue-dirty">
            <a class="Parametria" data-toggle="collapse-personal" href="#collapseParametria" role="button"  aria-controls="collapseParametria" >
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Parametria</span>
            </a>
            <div class="collapse-personal" id="collapseParametria"  >
                <div class="card card-body" style="padding-left: 5px; border: none" style="padding-left: 5px; border: none">

                    <!-- Contenido de tu despliegue -->
                    <!-- <a class="Administracion_datos" data-toggle="collapse-personal" href="#collapseAdministracionDatosParametria"   role="button"  aria-controls="collapseAdministracionDatosParametria" >
                        <span class="glyphicon glyphicon-list"></span>
                        <span class="lbl">Administraci&oacute;n de datos</span>
                    </a>
                    <div class="collapse-personal" id="collapseAdministracionDatosParametria"  >
                        <div class="card card-body" style="padding-left: 5px; border: none">
                            Contenido de tu despliegue

                            <a class="" data-toggle="collapse-personal" href="#collapseDatosGeograficoParametria"   role="button"  aria-controls="collapseDatosGeograficoParametria" >
                                <span class="glyphicon glyphicon-option-vertical"></span>
                                <span class="lbl">Datos Geogr&aacute;ficos</span>
                            </a>
                            <div class="collapse-personal" id="collapseDatosGeograficoParametria"  >
                                <div class="card card-body" style="padding-left: 5px; border: none">
                                    Contenido de tu despliegue

                                    <a class="" href="../Países/">
                                        <span class="glyphicon glyphicon-asterisk"></span>
                                        <span class="lbl"> Pa&iacute;ses</span>
                                    </a>
                                    <a class="" href="../Regiones/">
                                        <span class="glyphicon glyphicon-asterisk"></span>
                                        <span class="lbl"> Regiones</span>
                                    </a>
                                    <a class="" href="../Comunas/">
                                        <span class="glyphicon glyphicon-asterisk"></span>
                                        <span class="lbl"> Comunas</span>
                                    </a>
                                    <a class="" href="../Ciudades/">
                                        <span class="glyphicon glyphicon-asterisk"></span>
                                        <span class="lbl"> Ciudades</span>
                                    </a>
                                    <a class="" href="../Sectores/">
                                        <span class="glyphicon glyphicon-asterisk"></span>
                                        <span class="lbl"> Sectores</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- Contenido de tu despliegue -->
                    <!-- <a class="Unidad-Municipal" href="../UnidadMunicipal/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl">Unidades Municipales</span>
                    </a>
                    <a class="" href="../FuncionariosTerritoriales/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Funcionarios Territoriales</span>
                    </a>
                    <a class="" href="../FuncionariosMunicipales/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Funcionarios Municipales</span>
                    </a>
                    <a class="" href="../CategoriasEmergencias/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Categor&iacute;as de Eventos de Emergencia</span>
                    </a>
                    <a class="" href="../InstitucionesEmergencias/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Intituciones de Emergencia</span>
                    </a> -->
                    <a class="" href="../SeguridadPassword/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Seguridad contrase&ntilde;a</span>
                    </a>
                </div>
            </div>
        </li>
        <!-- Fin Parametría -->
        <!-- Gestión de Eventos de Emergencia -->
        <li class="blue-dirty">
            <a class="" data-toggle="collapse-personal" href="#collapseGestionEventos" role="button"  aria-controls="collapseGestionEventos" >
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Gestión de Emergencias</span>
            </a>
            <div class="collapse-personal" id="collapseGestionEventos"  >
                <div class="card card-body" style="padding-left: 5px; border: none" style="padding-left: 5px; border: none">
                    <!-- <a class="" href="../RecibirDerivacionEmergencia/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Recibir Derivación de Emergencia</span>
                    </a>
                    <a class="" href="../AtenderEmergencia/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Atender Emergencia</span>
                    </a> -->
                    <a class="" href="../NivelCategoria/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl">Nivel Categoria </span>
                    </a>
                </div>
            </div>
        </li>
        <!-- FIN Gestión de Eventos de Emergencia -->
        <!-- Reportes -->
        <!-- <li class="blue-dirty">
            <a class="" data-toggle="collapse-personal" href="#collapseReportes" role="button"  aria-controls="collapseReportes" >
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Reportes</span>
            </a>
            <div class="collapse-personal" id="collapseReportes"  >
                <div class="card card-body" style="padding-left: 5px; border: none" style="padding-left: 5px; border: none">
                    <a class="" href="../ReporteGeneralEmergencias/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Reporte General de Emergencias</span>
                    </a>
                    <a class="" href="../EmergenciasPorInstitucion/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Emergenias por institución</span>
                    </a>
                    <a class="" href="../registrosLog/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl">Registros Logs</span>
                    </a>
                </div>
            </div>
        </li> -->
        <!-- Fin Reportes -->
                
        <?php
        }
        ?>

</ul>
</nav><!--.side-menu-->

    <script >

document.addEventListener("DOMContentLoaded", function() {
  // Encuentra todos los elementos <a> con el atributo data-toggle="collapse-personal"
  var collapseLinks = document.querySelectorAll('a[data-toggle="collapse-personal"]');

  // Itera sobre los enlaces y agrega un event listener
  collapseLinks.forEach(function(link) {
    link.addEventListener('click', function(event) {
      // Evita la acción predeterminada del enlace para que no navegue a otra página
      event.preventDefault();

      // Obtiene el valor del atributo href para encontrar el elemento que se va a modificar
      var targetId = link.getAttribute('href').substring(1); // Elimina el carácter '#' al principio
      var targetElement = document.getElementById(targetId);

      // Verifica si el elemento tiene la clase 'collapse-personal'
      var isCollapsed = targetElement.classList.contains('collapse-personal');

        // Cambia la clase del elemento de 'collapse-personal' a 'collapse-in' si está cerrado, o viceversa
        if (isCollapsed) {
          targetElement.classList.remove('collapse-personal');
          targetElement.classList.add('collapse-in');
        } else {
          targetElement.classList.remove('collapse-in');
          targetElement.classList.add('collapse-personal');
        }
    });
  });
});


  

    </script>
    <style>
        .collapse-in {
            display: block !important; /* o el estilo que desees */
        }

        div.collapse-personal{
            display: none;
        }
    </style>
