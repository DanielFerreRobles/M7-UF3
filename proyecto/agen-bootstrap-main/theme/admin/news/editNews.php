<?php
session_start();
include '../../config.php';

$id = $_GET['id'];

$stmt = $mysqli->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

$noticia = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    $contenido = $_POST['contenido'];
    $fecha_publicacion = $_POST['fecha_publicacion'];

    $updateStmt = $mysqli->prepare("UPDATE noticias SET titulo = ?, subtitulo = ?, contenido = ?, fecha_publicacion = ? WHERE id = ?");
    $updateStmt->bind_param('ssssi', $titulo, $subtitulo, $contenido, $fecha_publicacion, $id);

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
                        <label class="form-label">Subtítulo:</label>
                        <input type="text" name="subtitulo" class="form-control" value="<?php echo htmlspecialchars($noticia['subtitulo']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contenido:</label>