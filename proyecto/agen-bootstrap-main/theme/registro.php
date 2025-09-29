<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare(
        "INSERT INTO USUARIOS (nombre_usuario, email, password, rol) 
         VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('ssss', $nombre_usuario, $email, $passwordHashed, $rol);

    if ($stmt->execute()) {
        header('Location: login.php');
        exit;        
    } else {
        echo 'Error de registro: ' . $stmt->error;
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
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Registro</h1>
        <form action="" method="POST" class="bg-white p-4 shadow rounded">

            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
    </div>
</body>
</html>