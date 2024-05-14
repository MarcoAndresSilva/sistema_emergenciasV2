// Función para aplicar el filtro por "robusta"
function aplicarFiltroRobusta() {
    const rows2 = document.querySelectorAll("#datos-pass tr");
    rows2.forEach(row => {
        const cellsToFilter2 = row.querySelectorAll('td');
        let mostrarFila = false;
        cellsToFilter2.forEach(cell => {
            if (cell.textContent.toLowerCase().includes('robusta')) {
                mostrarFila = true;
            }
        });

        if (mostrarFila) {
            row.classList.add('seguro');
            row.classList.remove('inseguro');
        } else {
            row.classList.remove("seguro");
            row.classList.add("inseguro");
        }
    });
}

// Función para aplicar el filtro según el valor del input mesesexpiracion
function aplicarFiltroSegunMesesExpiracion() {
    const mesesExpiracionInput = document.getElementById('mesesexpiracion');
    const valorInput = mesesExpiracionInput.value.trim();

    if (valorInput === '') {
        aplicarFiltroRobusta(); // Aplicar filtro por "robusta" si el input está vacío
        return;
    }

    const numeroMeses = parseInt(valorInput);
    const rows2 = document.querySelectorAll("#datos-pass tr");
    rows2.forEach(row => {
        const cells = row.querySelectorAll('td');
        let esSegura = true;
        cells.forEach((cell, index) => {
            // Columna 6 (índice 5) contiene el número de meses
            if (index === 5 && parseInt(cell.textContent) >= numeroMeses) {
                esSegura = false;
            }
        });
        // Aplicar clases según el valor del input
        if (esSegura) {
            row.classList.remove('inseguro');
            row.classList.add('seguro');
        } else {
            row.classList.remove('seguro');
            row.classList.add('inseguro');
        }
    });
}

// Escuchar cambios en el input mesesexpiracion
const mesesExpiracionInput = document.getElementById('mesesexpiracion');
mesesExpiracionInput.addEventListener('input', aplicarFiltroSegunMesesExpiracion);

// Aplicar filtro inicial al cargar la página
aplicarFiltroRobusta();
