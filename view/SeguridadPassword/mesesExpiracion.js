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
    } else {
        row.classList.add("inseguro"); 
    }
    console.log(row);
});
