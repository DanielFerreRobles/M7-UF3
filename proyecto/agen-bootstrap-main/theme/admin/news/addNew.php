<?php
session_start();
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $description = $_POST['description'];
    $new_date = $_POST['new_date'];
    $photo = $_POST['photo'];

    $stmt = $mysqli->prepare("INSERT INTO NEWS (title, subtitle, description, new_date, photo) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('sssss', $title, $subtitle, $description, $new_date, $photo);

    if ($stmt->execute()) {
        echo 'Notícia añadida correctamente!' ;
        exit;
    } else {
        echo 'Error al agregar noticia: ' . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Noticia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Añadir Noticia</h2>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtítulo:</label>
                        <input type="text" name="subtitle" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción:</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha:</label>
                        <input type="date" name="new_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL de la Imagen:</label>
                        <input type="text" name="photo" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Añadir Noticia</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>