<?php
session_start();
include '../../config.php';

// Obtener ID de la liga
$liga_id = isset($_GET['liga_id']) ? (int)$_GET['liga_id'] : 0;

// Información de la liga
$ligaNombre = "Todas las noticias";
$ligaLogo = ""; 
if ($liga_id > 0) {
    $ligaStmt = $mysqli->prepare("SELECT nombre, foto FROM LIGAS WHERE id = ?");
    $ligaStmt->bind_param("i", $liga_id);
    $ligaStmt->execute();
    $ligaResult = $ligaStmt->get_result();
    if ($ligaResult->num_rows > 0) {
        $ligaData = $ligaResult->fetch_assoc();
        $ligaNombre = $ligaData['nombre'];
        $ligaLogo = $ligaData['foto'];
    }
    $ligaStmt->close();
}

// Obtener noticias de la liga
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
<body class="text-white">

<?php if ($ligaLogo): ?>
<div class="position-fixed w-100 h-100 top-0 start-0">
    <img src="<?php echo htmlspecialchars($ligaLogo); ?>" class="w-100 h-100" style="object-fit: cover;" alt="Fondo liga">
    <div class="w-100 h-100 position-absolute top-0 start-0 bg-dark bg-opacity-50"></div>
</div>
<?php endif; ?>

<div class="container py-5 position-relative">
    <h1 class="mb-4 text-center"><?php echo htmlspecialchars($ligaNombre); ?></h1>

    <?php if (!empty($noticias)): ?>
        <?php foreach ($noticias as $noticia): ?>
        <div class="col-12 mb-4">
            <div class="card bg-light text-dark shadow-sm">
                <?php if (!empty($noticia['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($noticia['foto']); ?>" 
                         class="card-img-top" 
                         alt="Foto noticia" 
                         style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                    <h5 class="card-subtitle mb-3 text-muted"><?php echo htmlspecialchars($noticia['subtitulo']); ?></h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
                    <p class="text-muted">Publicado el <?php echo date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center">No hay noticias disponibles para esta liga.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>