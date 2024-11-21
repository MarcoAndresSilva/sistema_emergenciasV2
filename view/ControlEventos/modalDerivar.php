
<div class="modal fade" id="modalDerivar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miTitle">Panel de Derivaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dependencias CSS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css"> 
                <!-- Agrega las librerías de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
                <link rel="stylesheet" href="./estilopersonaleventos.css">
               <!-- Agrega el script de DataTables -->
                <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

                <form>
                    <div id="distribucion-box">

                        <div id="distribucion-espacios-datos">      
                            <div class="card">
                                <div class="card-body"> 
                                    <h6 class="card-subtitle mb-2 text-muted">ID</h6>
                                    <div id="derivar_ev_id"></div>
                                </div>
                            </div>
                             <div class="card">
                                <div class="card-body"> 
                                    <h6 class="card-subtitle mb-2 text-muted">CATEGORIA</h6>
                                    <div id="derivar_cat_nombre"></div>
                                </div>
                            </div> 
                        </div>        
                        <div id="distribucion-espacios">
                            <div class="card">
                                <div class="card-body"> 
                                    <h6 class="card-subtitle mb-2 text-muted">SECCIONES ASIGNADAS</h6>
                                    <div class="datos-participantes">
                                        <div class="col-lg-2 box-item mt-2 secciones-box">
                                            <fieldset class="form-group">
                                                <ul id="listaParticipantesderivar" class="list-group">
                                                    <li class="list-group-item"></li>
                                                </ul>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="distribucion-espacios">
                            <div class="card">
                                <div class="card-body"> 
                                    <h6 class="card-subtitle mb-2 text-muted">SECCIONES DISPONIBLES PARA ASIGNAR</h6>
                                    <div class="datos-participantes">
                                        <div class="col-lg-2 box-item mt-4">
                                            <fieldset class="form-group">
                                               <table id="tablaSecciones" class="table table-bordered">
                                                  <thead>
                                                      <tr>
                                                          <th>Unidad</th>
                                                          <th>Sección</th>
                                                          <th>Detalle</th>
                                                          <th>Estado</th>
                                                          <th>Acción</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                  <!-- Aquí se insertarán las filas de datos -->
                                                  </tbody>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Puedes agregar botones adicionales aquí si es necesario -->
            </div>
        </div>
    </div>
</div>

<!-- Dependencias JavaScript -->
<script src="../../public/js/sweetaler2v11-11-0.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./derivar.js"></script>
<script type="text/javascript" src="./evento.js"></script>


