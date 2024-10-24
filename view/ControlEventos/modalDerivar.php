
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
                <link rel="stylesheet" href="./estilopersonaleventos.css">

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
                                    <h6 class="card-subtitle mb-2 text-muted">SECCIONES</h6>
                                    <select id="multipleSelect" multiple name="native-select" placeholder="Native Select" data-search="false" data-silent-initial-value-set="true">
                                    </select> 
                                </div>
                             </div>
                            <fieldset class="form-group box-selection" id="buttons-group">
                                <div class="btn-box">
                                    <button id="btn" type='button' class='btn btn-inline btn-success btn-sm ladda-button btnActualizarTodos'>
                                        Derivar
                                        <span><i class="fa-solid fa-arrow-up-from-bracket"></i></span>
                                    </button>		
                                </div>
                            </fieldset>	
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


