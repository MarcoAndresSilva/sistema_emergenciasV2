<header class="site-header">
	<div class="container-fluid container-fluid-header">
		
		<a href="../Home/" class="site-logo">
			<img class="Logo" src="../../public/img/logo-meli.png" alt="">
		</a>

		<!-- Se a�0�9adio el id sandwich para disminuir el ancho -->
		<a href="sandwich" id="show-hide-sidebar-toggle" class="show-hide-sidebar sandwich">
			<span>Toggle Menu</span>
		</a>
		
		<div class="site-header-content">
			<div class="site-header-content-in">
				<div class="site-header-shown">
					<div class="dropdown user-menu">
						<button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown"
						aria-haspopup="true" aria-expanded="false">
							<img src="../../public/img/avatar-2-64.png" alt="">
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
							<!-- <a class="dropdown-item" href="#"><span
							class="font-icon glyphicon glyphicon-user"></span>Perfil</a> -->
							<!-- <a class="dropdown-item" href="#"><span
							class="font-icon glyphicon glyphicon-cog"></span>Configuraci��n</a> -->
							<!-- <a class="dropdown-item" href="#"><span
							class="font-icon glyphicon glyphicon-question-sign"></span>Ayuda</a> -->
							<div class="dropdown-divider"></div>
							
							<a class="dropdown-item" href="../Perfile"><span
										  class="font-icon glyphicon glyphicon-cog"></span>Editar Perfil</a>
							<?php 
								if ($_SESSION["usu_tipo"] == 2){
							?> 
              <a class="dropdown-item" href="../Perfil"><span
							class="font-icon glyphicon glyphicon-cog"></span>Editar Perfil</a>

							<a class="dropdown-item" href="../CrearUsuario/index.php"><span
							class="font-icon glyphicon glyphicon-plus"></span>Crear Usuario</a>
							<?php
								}
							?>

							<a class="dropdown-item" href="../Logout/logout.php"><span
							class="font-icon glyphicon glyphicon-log-out"></span>Cerrar Sesi&oacute;n</a>
						</div>
					</div>
				
				</div><!--.site-header-shown-->
			
				<div class="mobile-menu-right-overlay"></div>
				
				<input type="hidden" id="user_idx" value="<?php echo $_SESSION["usu_id"] ?>"><!-- ID del Usuario -->
				<input type="hidden" id="rol_idx" value="<?php echo $_SESSION["usu_tipo"] ?>"><!-- Rol del Usuario -->

				<div class="site-header-collapsed">
					<div class="dropdown dropdown-typical">
						<a href="#" class="dropdown-toggle no-arr">
							<span class="font-icon font-icon-user"></span>
							<span class="lblcontactonomx">
								<?php echo $_SESSION["usu_nom"] ?>
								<?php echo $_SESSION["usu_ape"] ?>
								
							</span>
						</a>
					</div>
					
					<div class="site-header-collapsed-in">
						<div class="site-header-search-container">
							<!-- <form class="site-header-search closed">
								<input type="text" placeholder="Search" />
								<button type="submit" id="icon-search">
									<span class="font-icon-search"></span>
								</button>
								<div class="overlay"></div>
							</form> -->
						</div>
					</div><!--.site-header-collapsed-in-->
					
				</div><!--.site-header-collapsed-->
			
			</div><!--site-header-content-in-->
		</div><!--.site-header-content-->
	</div><!--.container-fluid-->
</header><!--.site-header-->
