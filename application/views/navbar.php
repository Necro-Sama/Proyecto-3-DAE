<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container-fluid">
        <!-- Logo y título -->
        <a class="navbar-brand fw-bold logo-section" href="<?= site_url('usuarios/home') ?>">
            <img src="<?= base_url('dae-logo.png') ?>" alt="DAE Logo" class="nav-logo">
            Sistema DAE
        </a>
        
        <!-- Botón hamburguesa tradicional -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Menú colapsable -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('usuarios/home') ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('usuarios/agendar') ?>">
                        <i class="far fa-calendar"></i>
                        <?php if ($tipo === 'estudiante' || $tipo === 'noestudiante'): ?>
                            Agendar
                        <?php else: ?>
                            Gestión de calendario
                        <?php endif; ?>
                    </a>
                </li>
                <?php if ($tipo === 'administrador'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('usuarios/gestor_ts') ?>">
                            <i class="far fa-user"></i> Gestor TS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('usuarios/asignar-carrera') ?>">
                            <i class="fas fa-clipboard-list"></i> Asignar Carrera
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('usuarios/Licencia') ?>">
                            <i class="fas fa-file-alt"></i> Ingresar Licencia
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('usuarios/estadisticas') ?>">
                            <i class="fas fa-chart-bar"></i> Estadísticas
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('usuarios/visualizar-citas') ?>">
                        <i class="far fa-user"></i> Mostrar Citas
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('usuarios/logout') ?>">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.custom-navbar {
    background-color: #2B309E;
    border: 1px solid black;
    padding: 0.5rem 1rem;
}

.nav-logo {
    height: 30px;
    margin-right: 10px;
}

.logo-section {
    background-color: white;
    padding: 5px 10px;
    border-radius: 5px;
    color: black;
    transition: all 0.3s ease;
}

.logo-section:hover {
    color: black;
    transform: scale(1.05);
}

.nav-link {
    color: white !important;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
    border-radius: 5px;
    margin: 0.2rem;
    white-space: nowrap;
}

.nav-link:hover {
    background-color: white;
    color: black !important;
    transform: scale(1.05);
}

.navbar-toggler {
    color: white;
    border-color: white;
}

.navbar-toggler i {
    color: white;
}

@media (max-width: 991.98px) {
    .navbar-nav {
        padding: 1rem 0;
    }
    
    .nav-link {
        padding: 0.75rem 1rem;
        margin: 0.2rem 0;
    }
    
    .navbar-collapse {
        background-color: #2B309E;
        padding: 1rem;
        border-radius: 0 0 5px 5px;
    }
}
</style>
