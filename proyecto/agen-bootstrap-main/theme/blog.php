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
  <title>Agen | Bootstrap Agency Template</title>

  <!-- mobile responsive meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
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

<!-- page-title -->
<section class="bg-dark text-center py-4">
    <h3 class="text-white">TODAS LAS NOTICIAS</h3>
    <a href="index.php" class="btn btn-light mt-3">Volver a Inicio</a>
</section>
<!-- /page-title -->

<!-- blog -->
<section class="section">
  <div class="container">
    <div class="row">
    <?php
require_once 'config.php';
$result = $mysqli->query("SELECT * FROM NEWS ORDER BY id DESC");
$arrayNews = $result->fetch_all(MYSQLI_ASSOC);

foreach ($arrayNews as $new) {
    echo "<div class='col-lg-4 col-md-6 mb-4 d-flex'>";
    echo "<div class='card h-100 w-100'>";
    echo "<img class='card-img-top' src='{$new['photo']}' alt='img'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>{$new['title']}</h5>";
    echo "<h6 class='card-subtitle mb-2 text-muted'>{$new['subtitle']}</h6>";
    echo "<p class='card-text'>{$new['description']}</p>";
    echo "</div>";
    echo "<div class='card-footer'>";
    echo "<small class='text-muted'>{$new['new_date']}</small>";
    echo "<br><a href='noticia{$new['id']}.php?' class='btn btn-primary mt-2'>Leer más</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>
    </div>
  </div>
</section>
<!-- /blog -->

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