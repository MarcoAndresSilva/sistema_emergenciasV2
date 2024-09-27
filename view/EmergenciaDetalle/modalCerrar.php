
<!-- Modal Derivar -->
<div class="modal fade" id="modalCerrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="miTitle">Panel de Cierre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="event-box" action="nuevoevento.js" method="post" id="event_form" enctype="multipart/form-data">
					<div id="distribucion-box">
						<div id="distribucion-espacios">
							<fieldset class="form-group" id="grupito">
								<label class="form-label bold" id="asignaciones-title">Id de Evento</label>
								<div id="ev_id_cierre"></div>
							</fieldset>
							<fieldset class="form-group" id="grupito">
								<label class="form-label bold" id="asignaciones-title">Categoria</label>
								<div id="cat_nom_cierre"></div>
							</fieldset>
							<fieldset class="form-group" id="grupito">
								<label class="form-label bold" id="asignaciones-title">Nombre Apellido</label>
								<input type="text" class="form-control" id="nombre_apellido" placeholder="Nombre y Apellido" value="<?php echo $_SESSION['usu_nom'] . ' ' . $_SESSION['usu_ape']; ?>" readonly>
							</fieldset>
							<fieldset class="form-group" id="grupito">
								<label class="form-label bold" id="asignaciones-title">Ingrese un detalle</label>
								<input type="text" class="form-control" id="detalle_cierre" name="detalle_cierre" placeholder="Detalle del cierre">
							</fieldset>
							<fieldset class="form-group">
								<label class="form-label bold" id="asignaciones-title">Seleccione el motivo</label>
								<select id="motivo_cierre" class="form-control">
									<!-- Datos de consulta -->
								</select>
							</fieldset>
							<fieldset class="form-group">
								<label class="form-label semibold" for="exampleInput">Desea adjuntar una imagen de la emergencia?</label>
								<input class="form-control" type="file" id="imagen" name="imagen" accept="image/*">
								<!-- <label id="archivoAdjuntado">No hay archivo adjunto (.JPG/.JPEG/.PNG)</label> -->
							</fieldset>
							<fieldset class="form-group box-selection" id="buttons-group">
								<!-- <label class="form-label semibold" id="asignaciones-title">Seleccionar</label> -->
								<div class="btn-box">
									<button id="btnCerrarEvento" type="button" class="btn btn-inline btn-warning btn-sm ladda-button btnCerrarEvento close-btn">
										Cerrar Evento <span><i class="fa-regular fa-circle-xmark"></i></span>
									</button>
								</div>
							</fieldset>
						</div>
					</div>
				</form>
      </div>
    </div>
  </div>
</div>