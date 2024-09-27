
//  Desarrollado por: Ilustre Municipalidad de Melipilla.
//  Departamento:Informática.
//  Directora de departamento: Limbi Odeth Ortiz Neira.
//  Jefe de proyecto: Cristian Esteban Suazo Olguin 
//  Desarrolladores: Marco Silva, Nelson Navarro


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

