<?php
session_start();
include '../../config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $description = $_POST['description'];
    $img = $_POST['photo'];
    $date = date('Y-m-d');

    $stmt = $mysqli->prepare(
        "INSERT INTO TESTIMONIALS (name, surname, description, imatge, data) 
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('sssss', $name, $surname, $description, $img, $date);

    if ($stmt->execute()) {
        echo 'Testimonio añadido correctamente! ';
    } else {
        echo 'Error al añadir testimonio: ' . $stmt->error;
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
    <title>Añadir Testimonio</title>
</head>
<body>
    <h1>Añadir Testimonio</h1>
    <form action="" method="POST">
        <label for="name">Nombre:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="surname">Apellido:</label><br>
        <input type="text" id="surname" name="surname" required><br><br>

        <label for="description">Descripción:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="photo">Foto de perfil (URL):</label><br>
        <input type="text" id="photo" name="photo" required><br><br>

        <input type="submit" value="Añadir Testimonio">
    </form>
</body>
</html>