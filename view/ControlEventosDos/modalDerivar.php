
<!-- Modal Derivar -->
<div class="modal fade" id="modalDerivar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="miTitle">Panel de Derivaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="distribucion-box">
            <div id="distribucion-espacios">
              <fieldset class="form-group" id="grupito">
                <label class="form-label bold" for="id_nivel_peligro" id="asignaciones-title">Id de Evento</label>
                <div id="ev_id"></div>
              </fieldset>
              <fieldset class="form-group" id="grupito">
                <label class="form-label bold" for="id_nivel_peligro" id="asignaciones-title">Categoria</label>
                <div id="cat_nom"></div>
              </fieldset>
              <fieldset class="form-group">
                <label class="form-label bold" for="id_nivel_peligro" id="asignaciones-title">Nivel de Peligro </label>
                <select id="niv_id" class="form-control" >
                  <!-- Datos de consulta -->
                </select>
              </fieldset>
            </div>
            <div id="distribucion-espacios">
              <fieldset class="form-group">
                <label class="form-label bold" id="asignaciones-title">Asignar Unidad</label>
                <div id="unidadOptions" class="form-check">
                  <!-- Las opciones se agregarán aquí dinámicamente -->
                </div>
              </fieldset>

              <fieldset class="form-group box-selection" id="buttons-group">
                <div class="btn-box">
                  <button id="btn"type='button' class='btn btn-inline btn-success btn-sm ladda-button btnActualizarTodos'> Derivar
                    <span><i class="fa-solid fa-arrow-up-from-bracket"></i> </span>
                  </button>		
                </div>
              </fieldset>	
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
      <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> -->
      <!-- <button type="button" class="btn btn-primary">Derivar</button> -->
      </div>
    </div>
  </div>
</div>



