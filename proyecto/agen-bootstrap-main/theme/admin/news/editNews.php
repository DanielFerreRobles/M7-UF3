<?php
session_start();
include '../../config.php';

$id = $_GET['id'];

$stmt = $mysqli->prepare("SELECT * FROM NEWS WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Noticia no encontrada.");
}

$new = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $description = $_POST['description'];
    $new_date = $_POST['new_date'];
    $photo = $_POST['photo'];

    $updateStmt = $mysqli->prepare("UPDATE NEWS SET title = ?, subtitle = ?, description = ?, new_date = ?, photo = ? WHERE id = ?");
    $updateStmt->bind_param('sssssi', $title, $subtitle, $description, $new_date, $photo, $id);

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
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($new['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtítulo:</label>
                        <input type="text" name="subtitle" class="form-control" value="<?php echo htmlspecialchars($new['subtitle']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción:</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($new['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha:</label>
                        <input type="date" name="new_date" class="form-control" value="<?php echo $new['new_date']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL de la Imagen:</label>
                        <input type="text" name="photo" class="form-control" value="<?php echo htmlspecialchars($new['photo']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Noticia</button>
                </form>
            </div>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Volver a la lista de noticias</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>