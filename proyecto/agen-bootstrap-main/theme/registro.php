<?php

session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age']; 
    $rol = 'user';
    $img = $_POST['photo'];

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare(
        "INSERT INTO users (name, email, password, rol, age, photo, data_register) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())"

    );

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('ssssis', $name, $email, $passwordHashed, $rol, $age, $img);

    if ($stmt->execute()) {
        header('Location: login.php');
        exit;        
    } else {
        echo 'Error de registro: ' . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>
    <form action="" method="POST">
        <label for="name">Nombre:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="age">Edad:</label><br>
        <input type="number" id="age" name="age" required><br><br>

        <label for="photo">Foto de perfil (URL):</label><br>
        <input type="text" id="photo" name="photo" required><br><br>

        <input type="submit" value="Registrarse">
    </form>
</body>
</html>