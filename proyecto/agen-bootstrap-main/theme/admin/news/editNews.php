<?php
session_start();
include '../../config.php'; // Ajusta la ruta si hace falta

// Obtener el ID de la noticia
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de noticia no válido.");
}
$id = (int)$_GET['id'];

// Obtener noticia
$stmt = $mysqli->prepare("SELECT * FROM NOTICIAS WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();
$stmt->close();

if (!$noticia) {
    die("Noticia no encontrada.");
}

// Obtener ligas para el select
$ligasResult = $mysqli->query("SELECT id, nombre FROM LIGAS");
$ligas = $ligasResult->fetch_all(MYSQLI_ASSOC);

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    $contenido = $_POST['contenido'];
    $liga_id = $_POST['liga_id'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $foto = $_POST['foto']; // Puedes añadir upload si quieres

    $updateStmt = $mysqli->prepare("UPDATE NOTICIAS SET titulo = ?, subtitulo = ?, contenido = ?, liga_id = ?, fecha_publicacion = ?, foto = ? WHERE id = ?");
    $updateStmt->bind_param("sssissi", $titulo, $subtitulo, $contenido, $liga_id, $fecha_publicacion, $foto, $id);

    if ($updateStmt->execute()) {
        $mensaje = '<div class="alert alert-success">¡Noticia actualizada correctamente!</div>';
    } else {
        $mensaje = '<div class="alert alert-danger">Error al actualizar: ' . $updateStmt->error . '</div>';
    }

    $updateStmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Noticia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="mb-4">Editar Noticia</h2>

            <?php if (!empty($mensaje)) echo $mensaje; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subtítulo</label>
                    <input type="text" name="subtitulo" class="form-control" value="<?= htmlspecialchars($noticia['subtitulo']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contenido</label>
                    <textarea name="contenido" class="form-control" rows="5" required><?= htmlspecialchars($noticia['contenido']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto (URL)</label>
                    <input type="text" name="foto" class="form-control" value="<?= htmlspecialchars($noticia['foto']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Liga</label>
                    <select name="liga_id" class="form-select" required>
                        <?php foreach ($ligas as $liga): ?>
                            <option value="<?= $liga['id'] ?>" <?= $liga['id'] == $noticia['liga_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($liga['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha de publicación</label>
                    <input type="datetime-local" name="fecha_publicacion" class="form-control" 
                        value="<?= date('Y-m-d\TH:i', strtotime($noticia['fecha_publicacion'])) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Noticia</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>