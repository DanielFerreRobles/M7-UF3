<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ligas de Fútbol</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

<section class="section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title">Ligas de fútbol</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php
            $result = $mysqli->query("SELECT * FROM LIGAS");

            if ($result && $result->num_rows > 0) {
                while ($liga = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 col-sm-6 mb-4'>";
                    echo "<div class='card h-100 shadow'>";
                    echo "<img class='card-img-top p-3' src='{$liga['foto']}' alt='{$liga['nombre']}' style='height: 150px; object-fit: contain;'>";
                    echo "<div class='card-body text-center'>";
                    echo "<h5 class='card-title'>{$liga['nombre']}</h5>";
                    echo "</div>";
                    echo "<div class='card-footer text-center'>";
                    echo "<small class='text-muted'>ID: {$liga['id']}</small>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-center'>No se encontraron ligas.</p>";
            }
            ?>
        </div>
    </div>
</section>

</body>
</html>