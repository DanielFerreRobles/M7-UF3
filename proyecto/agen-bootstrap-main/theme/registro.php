<?php
session_start();
include 'config.php'; // Conexión a mi base de datos MySQL usando $mysqli

$error = ''; //Por si hay error

//Si los datos han sido correctamente enviados por POST, los guardamos en variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Hasheamos la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserto los datos recibidos del formulario en mi tabla "USUARIOS"
    $stmt = $mysqli->prepare("INSERT INTO USUARIOS (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $hashed_password, $rol);

    //Si la consulta de INSERT INTO se ejecuta correctamente, redirije a login.php (para iniciar sesión, sino, da error)
    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Error al registrarse. Inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Enganchados Por El Fútbol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <h2 class="mb-4 text-center">Registro</h2>

        <!--Si hay un error al registrarse, el mensaje se mostrará rojo-->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!--Enviamos por POST el nombre de usuario, email, contraseña y si es admin o user. Estos datos se reciben arriba y hacemos el INSERT INTO !-->
        <form method="POST">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label text-dark">Nombre de usuario</label>
                <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label text-dark">Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-dark">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label text-dark">Rol</label>
                <select class="form-select" name="rol" id="rol" required>
                    <option value="">Selecciona un rol</option>
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        </form>

        <!--Si clica aquí le llevará a login.php para que inicie sesión-->
        <p class="mt-3 text-center text-dark">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>