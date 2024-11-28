<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color: #060eae;">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#" style="color: #fdd188;">
      <img src="<?php echo base_url('assets/img/dae-logo.png'); ?>" style="height: 30px; margin-right: 10px;">
      Sistema DAE
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars" style="color: #fdd188;"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Opciones de la izquierda -->
      <ul class="navbar-nav me-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/home'); ?>" style="color: #fbf1d0;">
            <div>
              <i class="fas fa-home fa-lg mb-1" style="color: #fddeaa;"></i>
            </div>
            Home
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/agendar'); ?>" style="color: #fbf1d0;">
            <div>
              <i class="far fa-calendar fa-lg mb-1" style="color: #fddeaa;"></i>
            </div>
            Agendar
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/gestor_ts'); ?>" style="color: #fbf1d0;">
            <div>
              <i class="far fa-user fa-lg mb-1" style="color: #fddeaa;"></i>
            </div>
            Gestor TS
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/Licencia'); ?>" style="color: #fbf1d0;">
            <div>
              <i class="fas fa-file-alt fa-lg mb-1" style="color: #fddeaa;"></i>
            </div>
            Ingresar Licencia
          </a>
        </li>
      </ul>
      <!-- Opciones de la derecha -->
      <ul class="navbar-nav ms-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/logout'); ?>" style="color: #fbf1d0;">
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
<!-- Navbar -->
