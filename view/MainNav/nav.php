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
        "className" => "Administracion",
        "permiso_requerido" => null,
        "submenu" => [
            [
                "nombre" => "Unidades Municipales",
                "url" => "../UnidadMunicipal/",
                "className" => "UnidadMunicipal",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Gestion Reglas Noticia",
                "url" => "../GestionReglasNoticia/",
                "className" => "ReglasNoticia",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Nivel Categoria",
                "url" => "../NivelCategoria/",
                "className" => "NivelCategoria",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Gestion Motivos",
                "url" => "../GestionMotivo/",
                "className" => "GestionMotivo",
                "permiso_requerido" => null
            ]
        ]
    ],
    [
        "nombre" => "Gestión de usuarios",
        "url" => "#collapseGestionEventos",
        "permiso_requerido" => null,
        "className" => "ControlUsuario",
        "submenu" => [
            [
                "nombre" => "Seguridad contraseña",
                "url" => "../SeguridadPassword/",
                "className" => "SeguridadPassword",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Gestionar Usuarios",
                "url" => "../GestionUsuario/",
                "className" => "GestionarUsuario",
                "permiso_requerido" => null
            ],
            [
                "nombre" => "Seguridad Unidad",
                "url" => "../SeguridadUnidad/",
                "className" => "SeguridadUnidades",
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
// Obtener la ruta actual de la página, eliminando el prefijo del dominio
var currentPage = location.pathname;

// Normalizar currentPage para asegurarnos de que empiece por '../'
if (!currentPage.startsWith("../")) {
    currentPage = "../" + currentPage;
}

// Limpiar el currentPage para eliminar cualquier parte de 'view' y barras dobles
currentPage = currentPage.replace(/\/view\//g, '/').replace(/\/{2,}/g, '/'); // Reemplaza '/view/' y '//' con '/'

// Obtener todos los elementos <a> del menú
var menuLinks = document.querySelectorAll('.side-menu-list a');

// Recorrer todos los enlaces del menú
menuLinks.forEach(function(link) {
    // Obtener el href de cada enlace
    var linkHref = link.getAttribute('href');

    // Limpiar linkHref para eliminar barras dobles y '/view/'
    linkHref = linkHref.replace(/\/view\//g, '/').replace(/\/{2,}/g, '/'); // Reemplaza '/view/' y '//' con '/'

    // Depuración: ver las rutas actuales
    console.log([linkHref, currentPage]);

    // Verificar si el href coincide con la ruta actual
    if (linkHref === currentPage) {
        // Agregar la clase 'selected' al enlace correspondiente
        link.classList.add('selected');

        var parentCollapse = link.closest('.collapse-personal');

        if (parentCollapse) {
            // Desplegar el submenú padre
            parentCollapse.classList.remove('collapse-personal');
            parentCollapse.classList.add('collapse-in');

            // También cambiar la clase del enlace que activa el colapso del submenú (el toggle)
            var toggleLink = document.querySelector('a[href="#' + parentCollapse.id + '"]');
            if (toggleLink) {
                toggleLink.classList.add('selected');
            }
        }
    }
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
