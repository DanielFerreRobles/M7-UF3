<?php
session_start();
include 'config.php';

$liga_id = isset($_GET['liga_id']) ? (int)$_GET['liga_id'] : 0;

$ligaNombre = "Todas las noticias";
if ($liga_id > 0) {
    $ligaStmt = $mysqli->prepare("SELECT nombre FROM LIGAS WHERE id = ?");
    $ligaStmt->bind_param("i", $liga_id);
    $ligaStmt->execute();
    $ligaResult = $ligaStmt->get_result();
    if ($ligaResult->num_rows > 0) {
        $ligaNombre = $ligaResult->fetch_assoc()['nombre'];
    }
    $ligaStmt->close();
}

if ($liga_id > 0) {
    $stmt = $mysqli->prepare("SELECT * FROM NOTICIAS WHERE liga_id = ? ORDER BY fecha_publicacion DESC");
    $stmt->bind_param("i", $liga_id);
} else {
    $stmt = $mysqli->prepare("SELECT * FROM NOTICIAS ORDER BY fecha_publicacion DESC");
}

$stmt->execute();
$result = $stmt->get_result();
$noticias = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias - <?php echo htmlspecialchars($ligaNombre); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4">Noticias - <?php echo htmlspecialchars($ligaNombre); ?></h1>

    <div class="row">
        <?php if (!empty($noticias)): ?>
            <?php foreach ($noticias as $noticia): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($noticia['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($noticia['foto']); ?>" class="card-img-top" alt="Foto noticia" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($noticia['subtitulo']); ?></p>
                        <p class="card-text"><?php echo htmlspecialchars($noticia['contenido']); ?></p>
                        <p class="text-muted mt-auto"><?php echo date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No hay noticias disponibles para esta liga.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>