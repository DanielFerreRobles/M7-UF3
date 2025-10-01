<?php
// Inicia la sesión (aunque aquí no es tan necesario, se podría usar luego si quieres guardar datos al registrarse)
session_start();

// Incluye el archivo de configuración donde seguramente está la conexión a la base de datos
include 'config.php';

// Comprueba si el formulario fue enviado mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos que el usuario escribió en el formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Encripta la contraseña antes de guardarla en la base de datos
    // PASSWORD_DEFAULT usa el algoritmo recomendado por PHP (actualmente bcrypt)
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    // Prepara la consulta SQL para insertar un nuevo usuario
    $stmt = $mysqli->prepare(
        "INSERT INTO USUARIOS (nombre_usuario, email, password, rol) 
         VALUES (?, ?, ?, ?)"
    );

    // Si hubo un error al preparar la consulta, se detiene y muestra el error
    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    // Enlaza los valores a los marcadores (?) de la consulta
    // 'ssss' significa que los 4 parámetros son strings (texto)
    $stmt->bind_param('ssss', $nombre_usuario, $email, $passwordHashed, $rol);

    // Ejecuta la consulta y revisa si fue bien
    if ($stmt->execute()) {
        // Si todo fue correcto, redirige al login para que el usuario pueda iniciar sesión
        header('Location: login.php');
        exit;        
    } else {
        // Si algo salió mal, muestra el error
        echo 'Error de registro: ' . $stmt->error;
    }

    // Cierra la consulta
    $stmt->close();
    // Cierra la conexión con la base de datos
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

        <!-- Formulario de registro -->
        <form action="" method="POST" class="bg-white p-4 shadow rounded">

            <!-- Campo para el nombre de usuario -->
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" required>
            </div>

            <!-- Campo para el correo -->
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <!-- Campo para la contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <!-- Selector de rol -->
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <!-- Botón para enviar el formulario -->
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
    </div>
</body>
</html>