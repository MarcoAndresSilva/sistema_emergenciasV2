<?php
// Definir el menú como una variable PHP
$menu_items = [
    [
        "nombre" => "Inicio",
        "url" => "../Home/",
        "className" => "home",
        "permiso_requerido" => null
    ],
    [
        "nombre" => "Nuevo Evento",
        "url" => "../NuevoEvento/",
        "className" => "NuevoEvento",
        "permiso_requerido" => null
    ],
    [
        "nombre" => "Historial Eventos",
        "url" => "../HistorialEventos/",
        "className" => "HistorialEventos",
        "permiso_requerido" => null
    ],
    [
        "nombre" => "Control De Evento",
        "url" => "../ControlEventos/",
        "permiso_requerido" => null,
        "className" => "ControlEventos"
    ],
    [
        "nombre" => "Administración",
        "url" => "#collapseParametria",
        "className" => "Home",
        "permiso_requerido" => null,
        "submenu" => [
            [
                "nombre" => "Unidades Municipales",
                "url" => "../UnidadMunicipal/",
                "className" => "Home",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Gestion Reglas Noticia",
                "url" => "../GestionReglasNoticia/",
                "className" => "Home",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Nivel Categoria",
                "url" => "../NivelCategoria/",
                "className" => "Home",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Gestion Motivos",
                "url" => "../GestionMotivo/",
                "className" => "Home",
                "permiso_requerido" => null
            ]
        ]
    ],
    [
        "nombre" => "Gestión de usuarios",
        "url" => "#collapseGestionEventos",
        "permiso_requerido" => null,
        "className" => "Home",
        "submenu" => [
            [
                "nombre" => "Seguridad contraseña",
                "url" => "../SeguridadPassword/",
                "className" => "Home",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Gestionar Usuarios",
                "url" => "../GestionUsuario/",
                "className" => "Home",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Seguridad Unidad",
                "url" => "../SeguridadUnidad/",
                "className" => "Home",
                "permiso_requerido" => null
            ],
        ]
    ]
];

function render_menu($items) {
    foreach ($items as $item) {
        // Verificar permisos si es necesario
        if (is_null($item['permiso_requerido']) || Permisos::isPermited($item['permiso_requerido'])) {
            // Si el elemento tiene un submenú, agregar el toggle y el contenedor colapsable
            if (isset($item['submenu']) && !empty($item['submenu'])) {
                echo '<a class="'.$item["className"].'"  href="' . $item['url'] . '" data-toggle="collapse-personal" role="button" aria-controls="' . substr($item['url'], 1) . '">';
                echo '<span class="glyphicon glyphicon-th"></span>';
                echo '<span class="lbl">' . $item['nombre'] . '</span>';
                echo '</a>';
                echo '<div class="collapse-personal" id="' . substr($item['url'], 1) . '">';
                echo '<div class="card card-body" style="padding-left: 5px; border: none">';
                // Renderizar el submenú
                render_menu($item['submenu']);
                echo '</div></div>';
            } else {
                // Si no tiene submenú, renderizar un solo enlace
                echo '<li class="blue-dirty" ">';
                echo '<a class ="'.$item['className'].'" href="'.$item['url'].'">';
                echo '<span class="glyphicon glyphicon-th"></span>';
                echo '<span class="lbl">' . $item['nombre'] . '</span>';
                echo '</a></li>';
            }
        }
    }
}
?>

<nav class="side-menu">
    <ul class="side-menu-list">
        <?php render_menu($menu_items); ?>
    </ul>
</nav><!--.side-menu-->    <script >

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
