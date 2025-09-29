<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FútbolApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar Bootstrap 5 -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">FútbolApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link active" href="#">Inicio</a>
                </li>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="registro.php">Registro</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="login.php">Iniciar Sesión</a>
                    </li>
                <?php else: ?>
                    <?php if ($_SESSION['user_rol'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="btn btn-warning ms-2" href="admin_panel.php">Panel Admin</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="logout.php">Cerrar Sesión</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<!-- Sección de bienvenida -->
<section class="py-5 bg-light text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Bienvenido a Enganchados Por El Fútbol</h1>
        <p class="lead mb-4">
            <?php if (!isset($_SESSION['user_id'])): ?>
                Regístrate o inicia sesión para acceder a todas las funcionalidades.
            <?php else: ?>
                Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            <?php endif; ?>
        </p>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>