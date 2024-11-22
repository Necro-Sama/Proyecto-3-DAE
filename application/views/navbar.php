<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Sistema DAE</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars text-light"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/home'); ?>">
            <div>
              <i class="fas fa-home fa-lg mb-1"></i>
            </div>
            Home
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/agendar'); ?>">
            <div>
              <i class="far fa-calendar fa-lg mb-1"></i>
            </div>
            Agendar
          </a>
        </li>
        
          <li class="nav-item text-center mx-2 mx-lg-1">
              <a class="nav-link" href="<?php echo site_url('gestor_ts'); ?>">
                  <div>
                      <i class="far fa-user fa-lg mb-1"></i>
                  </div>
                  Gestor TS
              </a>
          </li>
          <li class="nav-item text-center mx-2 mx-lg-1">
                    <a class="nav-link" href="<?php echo site_url('usuarios/Licencia'); ?>">
                      <div>
                        <i class="fas fa-sign-out-alt fa-lg mb-1"></i>
                      </div>
                      Ingresar Licencia
                    </a>
            </li>
      </ul>
      <ul class="navbar-nav ms-auto d-flex flex-row mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="<?php echo site_url('usuarios/logout'); ?>">
            <div>
              <i class="fas fa-sign-out-alt fa-lg mb-1"></i>
            </div>
            Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Navbar -->
