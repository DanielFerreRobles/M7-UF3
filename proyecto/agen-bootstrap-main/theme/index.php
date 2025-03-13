<?php
session_start();
?>

<!DOCTYPE html>

<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Selección Española de Fútbol</title>

  <!-- mobile responsive meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- theme meta -->
  <meta name="theme-name" content="agen" />

  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

  <!-- Main Stylesheet -->
  <link href="css/style.css" rel="stylesheet">

  <!--Favicon-->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">

</head>

<body class="bg-light">

<!-- Hero Section with Spain Colors -->
<section class="d-flex justify-content-center align-items-center text-white text-center bg-danger min-vh-100" 
  style="background: url('images/seleccion_espana.jpg') no-repeat center center; background-size: cover;">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center">
        <h6 class="display-4">Selección Española de Fútbol</h6>
        <h3>Bienvenido <?php echo isset($_SESSION['user_name']) ? ($_SESSION['user_name'] . ' <img src="' . $_SESSION['user_photo'] . '" alt="Foto" width="50" height="50" />') : 'Fan'; ?></h3>
        
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

<!-- About Section with Yellow Accents -->
<section class="py-5 bg-warning">
    <div class="mx-auto border-0" style="max-width: 600px; background: none;">
        <div class="text-center">
            <img src="https://www.teleadhesivo.com/es/img/mrs37-png/folder/products-detalle-png/vinilos-decorativos-escudo-seleccion-espanola.png" alt="Logo de la Selección Española" class="img-fluid mx-auto d-block" style="max-width: 50%;">

            <h1 class="text-dark mt-3">Selección Española de Fútbol</h1>
            <p><strong>Fecha de formación:</strong> 1920</p>
            <p><strong>Entrenador actual:</strong> Luis de la Fuente</p>
            <p><strong>Ranking FIFA:</strong> 6º</p>
            <h2 class="mt-4">Logros destacados</h2>
            <p>La Selección Española ha alcanzado grandes éxitos en la historia del fútbol internacional, destacando su victoria en la <strong class="text-danger">Eurocopa 2008</strong>, la <strong class="text-danger">Copa del Mundo 2010</strong> y la <strong class="text-danger">Eurocopa 2012</strong>, convirtiéndose en uno de los equipos más exitosos de la historia reciente.</p>
            <p>Además, la selección ha producido jugadores icónicos como <strong class="text-success">Iker Casillas</strong>, <strong class="text-success">Xavi Hernández</strong>, y <strong class="text-success">Sergio Ramos</strong>, quienes han dejado una huella imborrable en la historia del deporte.</p>
        </div>
    </div>
</section>

<!-- News Section with Cards -->
<section class="section py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 mx-auto text-center">
        <h2 class="section-title mb-4">Noticias sobre la Selección Española</h2>
        <div class="row g-4">
          <?php
          require_once 'config.php';
          $result = $mysqli->query("SELECT * FROM NEWS ORDER BY id DESC LIMIT 3");
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
        <a href="blog.php" class="btn btn-danger mt-3">Ver todas las noticias</a>
      </div>
    </div>
  </div>
</section>

<!-- Comments Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto text-center">
                <h2 class="section-title">Comentarios de los Fans</h2>
                <div class="row g-4">
                    <?php
                    $result = $mysqli->query("SELECT * FROM COMENTARIS");
                    $arrayCommentaris = $result->fetch_all(MYSQLI_ASSOC);

                    foreach ($arrayCommentaris as $commentari) {
                        echo "<div class='col-lg-4 col-md-6 mb-4'>";
                        echo "<div class='card h-100 shadow border-danger'>";
                        echo "<div class='card-body'>";
                        echo "<p class='card-text'>{$commentari['coment']}</p>";
                        echo "</div>";
                        echo "<div class='card-footer'>";
                        echo "<small class='text-muted'>{$commentari['date']}</small>";
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

<!-- Testimonials Section -->
<section class="section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto text-center">
                <h2 class="section-title">Testimonios de Jugadores</h2>
                <div class="row g-4">
                    <?php
                    $result = $mysqli->query("SELECT * FROM TESTIMONIALS");

                    $arrayTestimonials = $result->fetch_all(MYSQLI_ASSOC);

                    foreach ($arrayTestimonials as $testimoni) {
                        echo "<div class='col-lg-4 col-md-6 mb-4'>";
                        echo "<div class='card h-100 shadow border-warning'>";
                        echo "<img class='card-img-top' src='{$testimoni['imatge']}' alt='Img'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>{$testimoni['name']} {$testimoni['surname']}</h5>";
                        echo "<p class='card-text'>{$testimoni['description']}</p>";
                        echo "</div>";
                        echo "<div class='card-footer'>";
                        echo "<small class='text-muted'>{$testimoni['data']}</small>";
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
    <p>&copy; 2025 Selección Española de Fútbol. Todos los derechos reservados.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>