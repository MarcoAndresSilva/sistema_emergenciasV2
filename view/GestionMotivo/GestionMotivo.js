function fn_agregar_motivo_cierre(){
Swal.fire({
  title: 'Ingrese el motivo',
  input: 'text',
  inputPlaceholder: 'Escriba su motivo aquí',
  showCancelButton: true,
  confirmButtonText: 'Aceptar',
  cancelButtonText: 'Cancelar',
}).then((result) => {
  if (result.isConfirmed) {
    Swal.fire('Motivo ingresado', `Su motivo fue: ${result.value}`, 'success');
  }
});
}

