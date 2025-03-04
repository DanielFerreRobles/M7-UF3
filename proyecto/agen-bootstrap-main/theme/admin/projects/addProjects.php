<?php
session_start();
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $categoria = $_POST['categoria'];

    $stmt = $mysqli->prepare(
        "INSERT INTO PROJECTS (title, description, url, categoria) 
        VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('ssss', $title, $description, $url, $categoria);

    if ($stmt->execute()) {
        echo 'Proyecto añadido correctamente!';
    } else {
        echo 'Error al añadir proyecto: ' . $stmt->error;
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
    <title>Añadir Proyecto</title>
</head>
<body>
    <h1>Añadir Proyecto</h1>
    <form action="" method="POST">
        <label for="title">Título:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Descripción:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="url">URL del Proyecto:</label><br>
        <input type="text" id="url" name="url" required><br><br>

        <label for="categoria">Categoría:</label><br>
        <input type="text" id="categoria" name="categoria" required><br><br>

        <input type="submit" value="Añadir Proyecto">
    </form>
</body>
</html>