<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>La Liga Española 2024-2025</title>

  <!-- mobile responsive meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- theme meta -->
  <meta name="theme-name" content="agen" />

  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

  <!-- Main Stylesheet -->
  <link href="css/style.css" rel="stylesheet">

  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">

</head>

<body class="bg-light">

<section class="d-flex justify-content-center align-items-center text-center bg-dark min-vh-50" style="color: white;">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center text-white">        
        <?php if (isset($_SESSION['user_name'])): ?>
          <h3 class="text-white">Bienvenido <?php echo $_SESSION['user_name']; ?> 
            <img src="<?php echo $_SESSION['user_photo']; ?>" alt="Foto" width="50" height="50" />
          </h3>
        <?php else: ?>
          <h3 class="text-white">Bienvenido Futbolero</h3>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 'admin') : ?>
          <a href="adminPanel.php" class="btn btn-warning btn-sm shadow-sm mt-3">
              <i class="bi bi-tools"></i> Panel Admin
          </a>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_name'])): ?>
          <a href="login.php" class="btn btn-primary mt-3">Iniciar sesión</a>
          <a href="registro.php" class="btn btn-secondary mt-3">Registrarse</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_name'])): ?>
          <a href="logout.php" class="btn btn-secondary mt-3">Cerrar sesión</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Clasificación La Liga -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title mb-4">Clasificación de La Liga 2024-2025</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Posición</th>
                            <th>Equipo</th>
                            <th>Puntos</th>
                            <th>Partidos Jugados</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'config.php';
                        $result = $mysqli->query("SELECT * FROM clasificacion ORDER BY PTS DESC, PJ ASC");
                        $clasificacion = $result->fetch_all(MYSQLI_ASSOC);

                        foreach ($clasificacion as $equipo) {
                            echo "<tr>";
                            echo "<td>{$equipo['posicion']}</td>";
                            echo "<td>{$equipo['equipo']}</td>";
                            echo "<td>{$equipo['pts']}</td>";
                            echo "<td>{$equipo['pj']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section class="section py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 mx-auto text-center">
        <h2 class="section-title mb-4">Noticias sobre La Liga 2024-2025</h2>
        <div class="row g-4">
          <?php
          $result = $mysqli->query("SELECT * FROM news ORDER BY new_date DESC LIMIT 3");
          $arrayNews = $result->fetch_all(MYSQLI_ASSOC); 

          foreach ($arrayNews as $new) {
            echo "<div class='col-lg-4 col-md-6 mb-4'>";
            echo "<div class='card h-100 shadow-lg border-warning'>";
            echo "<img class='card-img-top' src='{$new['photo']}' alt='img'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>{$new['title']}</h5>";
            echo "<h6 class='card-subtitle mb-2 text-muted'>{$new['subtitle']}</h6>";
            echo "<p class='card-text'>{$new['description']}</p>";
            echo "</div>";
            echo "<div class='card-footer'>";
            echo "<small class='text-muted'>{$new['new_date']}</small>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer Section -->
<footer class="text-center py-4 bg-dark text-white">
    <p>&copy; 2025 La Liga Española. Todos los derechos reservados.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>