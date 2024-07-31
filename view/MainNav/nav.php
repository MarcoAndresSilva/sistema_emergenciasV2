
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
        <!-- <li class="blue-dirty">
            <a class="Historial" href="../Historial/">
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Historial </span>
            </a>
        </li> -->
        <li class="blue-dirty">
            <a class="HistorialEventos" href="../HistorialEventos/">
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Historial Eventos </span>
            </a>
        </li>
        
        <?php 
        if ($_SESSION["usu_tipo"] == 2) {
        ?> 
        
        <!-- Evento -->
        <li class="blue-dirty">
            <a class="ControlEventos" href="../ControlEventosDos/">
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl"> Eventos Activos</span>
            </a>
        </li>


        <!-- Parametría -->
        <li class="blue-dirty">
            <a class="Parametria" data-toggle="collapse-personal" href="#collapseParametria" role="button"  aria-controls="collapseParametria" >
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Administración</span>
            </a>
            <div class="collapse-personal" id="collapseParametria"  >
                <div class="card card-body" style="padding-left: 5px; border: none" style="padding-left: 5px; border: none">

                    <a class="Unidad-Municipal" href="../UnidadMunicipal/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl">Unidades Municipales</span>
                    </a>

                    <a class="" href="../NivelCategoria/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl">Nivel Categoria </span>
                    </a>
                    <a class="" href="../GestionMotivo/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl">Gestion Motivos </span>
                    </a>
                   
                </div>
            </div>
        </li>
        <!-- Fin Parametría -->
        <!-- Gestión de Eventos de Emergencia -->
        <li class="blue-dirty">
            <a class="" data-toggle="collapse-personal" href="#collapseGestionEventos" role="button"  aria-controls="collapseGestionEventos" >
                <span class="glyphicon glyphicon-th"></span>
                <span class="lbl">Gestión de usuarios</span>
            </a>
            <div class="collapse-personal" id="collapseGestionEventos"  >
                <div class="card card-body" style="padding-left: 5px; border: none" style="padding-left: 5px; border: none">
                    
                    <a class="" href="../SeguridadPassword/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl"> Seguridad contrase&ntilde;a</span>
                    </a>
                    <a class="" href="../GestionUsuario/">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        <span class="lbl">Gestionar Usuarios</span>
                    </a>
                </div>
            </div>
        </li>
                
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
