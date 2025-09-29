<?php
session_start();
include '../../config.php';

$id = $_GET['id'];

// Obtener noticia
$stmt = $mysqli->prepare("SELECT * FROM NOTICIAS WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();
$stmt->close();

// Obtener ligas para el select
$ligasResult = $mysqli->query("SELECT id, nombre FROM LIGAS");
$ligas = $ligasResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $foto = $_POST['foto']; // nuevo campo
    $subtitulo = $_POST['subtitulo'];
    $contenido = $_POST['contenido'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $liga_id = $_POST['liga_id'];

    $updateStmt = $mysqli->prepare("UPDATE NOTICIAS SET titulo = ?, foto = ?, subtitulo = ?, contenido = ?, fecha_publicacion = ?, liga_id = ? WHERE id = ?");
    $updateStmt->bind_param('ssssssi', $titulo, $foto, $subtitulo, $contenido, $fecha_publicacion, $liga_id, $id);

    if ($updateStmt->execute()) {
        echo '<div class="alert alert-success">¡Noticia actualizada correctamente!</div>';
    } else {
        echo '<div class="alert alert-danger">Error al actualizar la noticia: ' . $updateStmt->error . '</div>';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Editar Noticia</h2>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Título:</label>
                    <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto (URL):</label>
                    <input type="text" name="foto" class="form-control" value="<?php echo htmlspecialchars($noticia['foto']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subtítulo:</label>
                    <input type="text" name="subtitulo" class="form-control" value="<?php echo htmlspecialchars($noticia['subtitulo']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contenido:</label>
                    <textarea name="contenido" class="form-control" rows="4" required><?php echo htmlspecialchars($noticia['contenido']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Liga:</label>
                    <select name="liga_id" class="form-select" required>
                        <?php foreach ($ligas as $liga): ?>
                            <option value="<?php echo $liga['id']; ?>" <?php echo ($liga['id'] == $noticia['liga_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($liga['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha de publicación:</label>
                    <input type="datetime-local" name="fecha_publicacion" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($noticia['fecha_publicacion'])); ?>">
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Noticia</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>