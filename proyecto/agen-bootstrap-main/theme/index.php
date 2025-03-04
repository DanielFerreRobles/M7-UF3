<?php
session_start();
?>

<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

<html lang="zxx">

<head>
  <meta charset="utf-8">
  <title>Dani Ferrer Robles</title>

  <!-- mobile responsive meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <!-- theme meta -->
  <meta name="theme-name" content="agen" />
  
  <!-- ** Plugins Needed for the Project ** -->
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <!-- slick slider -->
  <link rel="stylesheet" href="plugins/slick/slick.css">
  <!-- themefy-icon -->
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <!-- venobox css -->
  <link rel="stylesheet" href="plugins/venobox/venobox.css">
  <!-- card slider -->
  <link rel="stylesheet" href="plugins/card-slider/css/style.css">

  <!-- Main Stylesheet -->
  <link href="css/style.css" rel="stylesheet">
  
  <!--Favicon-->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">

</head>

<body>
  
<section class="d-flex justify-content-center align-items-center text-white text-center bg-dark min-vh-100" 
  style="background: url('images/agua.jpg') no-repeat center center; background-size: cover;">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center">
        <h6 class="display-4 text-white">Daniel Ferrer Robles</h6>
        <h3>Bienvenido <?php echo isset($_SESSION['user_name']) ? ($_SESSION['user_name'] . ' <img src="' . $_SESSION['user_photo'] . '" alt="Foto de perfil" width="50" height="50" />') : 'Crack'; ?></h3>
        
        <a href="adminPanel.php" class="btn btn-sm btn-dark shadow-sm">
            <i class="bi bi-tools"></i> Panel Admin
        </a>

        
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


<section class="py-5">
        <div class="mx-auto border-0" style="max-width: 600px; background: none;">
            <div>
            <img src="https://www.fedpc.org/wp-content/uploads/WhatsApp-Image-2023-08-03-at-20.29.26-1.jpeg" alt="Descripción de la imagen" class="img-fluid mx-auto d-block" style="max-width: 50%;">

                <h1 class="text-black text-center">Dani Ferrer Robles</h1>
                <p><strong>Fecha de nacimiento:</strong> 23 de agosto de 2002</p>
                <p><strong>Lugar de nacimiento:</strong> Badalona, España</p>
                <p><strong>Club actual:</strong> C.N. Mataró</p>
                <h2 class="mt-4">Logros destacados</h2>
                <p>En 2023, Dani Ferrer Robles logró un gran éxito en el Campeonato del Mundo de Manchester, donde obtuvo <strong class="text-warning">2 medallas de plata</strong> precisamente en sus 2 pruebas estrella, 50m libre y 50m espalda, a parte, fue finalista (top 8) en todas las pruebas que nadó, quedando cuarto en otra de ellas, el 150m estilos. Su destacada trayectoria en la natación paralímpica lo ha llevado a batir varios récords nacionales, consolidándose como uno de los mejores nadadores de su categoría.</p>
                <p>Gracias a sus impresionantes logros, en 2023 fue galardonado con el premio al <strong class="text-success">mejor deportista paralímpico</strong> de Mataró. Además, su talento y esfuerzo lo llevaron a ser finalista en el prestigioso premio al <strong class="text-danger">mejor deportista de Cataluña</strong>, un reconocimiento que resalta su impacto en el deporte.</p>
            </div>
        </div>
    </section>

<section class="section">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 mx-auto text-center">
      <h2 class="section-title">Noticias sobre el nadador</h2>
      <div class="d-flex justify-content-center">
      <?php
require_once 'config.php';
$result = $mysqli->query("SELECT * FROM NEWS ORDER BY id DESC LIMIT 3");
$arrayNews = $result->fetch_all(MYSQLI_ASSOC);

foreach ($arrayNews as $new) {
  echo "<div class='col-lg-4 col-md-6 mb-4'>";
  echo "<div class='card h-100'>";
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
<a href="blog.php">Ver todas</a>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto text-center">
                <h2 class="section-title">Comentarios</h2>
                <div class="d-flex justify-content-center">
                    <?php

                    $result = $mysqli->query("SELECT * FROM COMENTARIS");
  
                        $arrayCommentaris = $result->fetch_all(MYSQLI_ASSOC);

                        foreach ($arrayCommentaris as $commentari) {
                            echo "<div class='col-lg-4 col-md-6 mb-4'>";
                            echo "<div class='card h-100'>";
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

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto text-center">
                <h2 class="section-title">Testimonios</h2>
                <div class="d-flex justify-content-center">
                    <?php
                    $result = $mysqli->query("SELECT * FROM TESTIMONIALS");

                    $arrayTestimonials = $result->fetch_all(MYSQLI_ASSOC);

                    foreach ($arrayTestimonials as $testimoni) {
                        echo "<div class='col-lg-4 col-md-6 mb-4'>";
                        echo "<div class='card h-100'>";
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

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto text-center">
                <h2 class="section-title">Proyectos</h2>
                <div class="d-flex justify-content-center">
                    <?php
                    $result = $mysqli->query("SELECT * FROM PROJECTS ORDER BY id DESC");

                    $arrayProyectos = $result->fetch_all(MYSQLI_ASSOC);

                    foreach ($arrayProyectos as $proyecto) {
                        echo "<div class='col-lg-4 col-md-6 mb-4'>";
                        echo "<div class='card h-100'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>{$proyecto['title']}</h5>";
                        echo "<p class='card-text'>{$proyecto['description']}</p>";
                        echo "</div>";
                        echo "<div class='card-footer'>";
                        echo "<a href='{$proyecto['url']}' target='_blank' class='btn btn-primary'>Ver</a><br>";
                        echo "<small class='text-muted'>Categoría: {$proyecto['categoria']}</small>";
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

<!-- /footer -->

<!-- jQuery -->
<script src="plugins/jQuery/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<!-- slick slider -->
<script src="plugins/slick/slick.min.js"></script>
<!-- venobox -->
<script src="plugins/venobox/venobox.min.js"></script>
<!-- shuffle -->
<script src="plugins/shuffle/shuffle.min.js"></script>
<!-- apear js -->
<script src="plugins/counto/apear.js"></script>
<!-- counter -->
<script src="plugins/counto/counTo.js"></script>
<!-- card slider -->
<script src="plugins/card-slider/js/card-slider-min.js"></script>
<!-- google map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU&libraries=places"></script>
<script src="plugins/google-map/gmap.js"></script>

<!-- Main Script -->
<script src="js/script.js"></script>

</body>
</html>