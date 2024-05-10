const input = document.getElementById('search-pass');
const rows = document.querySelectorAll('#datos-pass tr');
const selectStatus = document.getElementById('selectStatus');

function filtrarDatos(value) {
  rows.forEach(row => {
    const cellsToFilter = obtenerCeldasFiltrables(row);
    const mostrarFila = cellsToFilter.some(cell => cell.textContent.toLowerCase().includes(value));
    row.style.display = mostrarFila ? 'table-row' : 'none';
  });
}


function obtenerCeldasFiltrables(row) {
  return [
    row.querySelector('td:nth-child(1)'),// nombre
    row.querySelector('td:nth-child(2)'),// apellido
    row.querySelector('td:nth-child(4)')// correo
  ];
}

input.addEventListener('keyup', function() {
  const value = this.value.toLowerCase();
  filtrarDatos(value);
});

function filtrarDatosPorEstado() {
  const selectedValue = selectStatus.value;
  rows.forEach(row => {
    const cell = row.querySelector('td:nth-child(3)');// estado
    const cellValue = cell.textContent.trim();
    const mostrarFila = selectedValue === '0' || selectedValue === cellValue;
    row.style.display = mostrarFila ? 'table-row' : 'none';
  });
}

selectStatus.addEventListener('change', filtrarDatosPorEstado);
