<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container-fluid">
    <!-- Logo y título -->
    <a class="navbar-brand fw-bold logo-section" href="<?= site_url('usuarios/home') ?>">
      <img src="<?= base_url('dae-logo.png') ?>" style="height: 30px; margin-right: 10px;">
      Sistema DAE
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars" style="color: #fdd188;"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Opciones de la izquierda -->
      <ul class="navbar-nav me-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/home'); ?>" >
            <div>
              <i class="fas fa-home fa-lg mb-1" style="color: #fddeaa;"></i>
            </div>
            Home
          </a>
        </li>
        <?php if ($tipo === 'estudiante' or $tipo ==='noestudiante'): ?>
          <li class="nav-item text-center mx-2 mx-lg-1">
            <a class="nav-link" href="<?php echo site_url('usuarios/agendar'); ?>" >
              <div>
                <i class="far fa-calendar fa-lg mb-1" style="color: #fddeaa;"></i>
              </div>
              Agendar
            </a>
          </li>
        <?php endif; ?>
        <?php if ($tipo === 'administrador'): ?>
          <li class="nav-item text-center mx-2 mx-lg-1">
            <a class="nav-link" href="<?php echo site_url('usuarios/gestor_ts'); ?>" >
              <div>
                <i class="far fa-user fa-lg mb-1" style="color: #fddeaa;"></i>
              </div>
              Gestor TS
            </a>
          </li>
        <?php endif; ?>
        <?php if ($tipo === 'administrador'): ?>
          <li class="nav-item text-center mx-2 mx-lg-1">
            <a class="nav-link" href="<?php echo site_url('usuarios/Licencia'); ?>" >
              <div>
                <i class="fas fa-file-alt fa-lg mb-1" style="color: #fddeaa;"></i>
              </div>
              Ingresar Licencia
            </a>
          </li>
        <?php endif; ?>
        <?php ?>
          <li class="nav-item text-center mx-2 mx-lg-1">
            <a class="nav-link" href="<?php echo site_url('usuarios/visualizar-citas') ?>" >
              <div>
                <i class="far fa-user fa-lg mb-1" style="color: #fddeaa;"></i>
              </div>
              Mostrar Citas
            </a>
          </li>
        <?php  ?>
      </ul>
      <!-- Opciones de la derecha -->
      <ul class="navbar-nav ms-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/logout'); ?>">
            <div>
              <i class="fas fa-sign-out-alt fa-lg mb-1" style="color: #fddeaa;"></i>
            </div>
            Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
/* Fondo de la navbar y borde negro */
.custom-navbar {
  background-color: #2B309E;
  border: 1px solid black; /* Borde negro alrededor de la navbar */
}

/* Fondo blanco para la sección del logo */
.logo-section {
  background-color: white;
  padding: 5px 10px; /* Espaciado interno opcional */
  border-radius: 5px; /* Bordes redondeados para estética */
  color: black;
}

/* Para mantener los colores legibles en el texto */
.logo-section:hover {
  text-decoration: none;
  color: black;
}
.nav-link{
  color:white;
}
</style>
