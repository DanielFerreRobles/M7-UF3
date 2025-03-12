<?php
session_start();
include '../../config.php';

$id = $_GET['id']; 
$stmt = $mysqli->prepare("SELECT * FROM PROJECTS WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Proyecto no encontrado.");
}

$project = $result->fetch_assoc(); 
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $categoria = $_POST['categoria'];

    $updateStmt = $mysqli->prepare("UPDATE PROJECTS SET title = ?, description = ?, url = ?, categoria = ? WHERE id = ?");
    $updateStmt->bind_param('ssssi', $title, $description, $url, $categoria, $id);

    if ($updateStmt->execute()) {
        echo '<div class="alert alert-success">¡Proyecto actualizado correctamente!</div>';
    } else {
        echo '<div class="alert alert-danger">Error al actualizar el proyecto: ' . $updateStmt->error . '</div>';
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
    <title>Editar Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Editar Proyecto</h2>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título:</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción:</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL del Proyecto:</label>
                        <input type="text" name="url" class="form-control" value="<?php echo htmlspecialchars($project['url']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoría:</label>
                        <input type="text" name="categoria" class="form-control" value="<?php echo htmlspecialchars($project['categoria']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Proyecto</button>
                </form>
            </div>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Volver a la lista de proyectos</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>