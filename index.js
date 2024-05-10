
//  Desarrollado por: Ilustre Municipalidad de Melipilla.
//  Departamento:Informática.
//  Directora de departamento: Limbi Odeth Ortiz Neira.
//  Jefe de proyecto: Cristian Esteban Suazo Olguin 
//  Desarrolladores: Marco Silva, Bastian Cerda 

$(function () {
    $('.page-center').matchHeight({
        target: $('html')
    });

    $(window).resize(function () {
        setTimeout(function () {
            $('.page-center').matchHeight({
                remove: true
            });
            $('.page-center').matchHeight({
                target: $('html')
            });
        }, 100);
    });
});

// Buscar todos los scripts en la página
var scripts = document.querySelectorAll('script');
// Iterar sobre cada script para encontrar jQuery
scripts.forEach(function(script) {
// Verificar si el script fuente contiene "jquery" en su URL
if (script.src && script.src.toLowerCase().includes('jquery')) {
    console.log('jQuery se está cargando desde:', script.src);
}
});