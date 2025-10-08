<?php
session_start();
include 'config.php'; // Conexión a mi base de datos

$error = ''; //Por si da error al iniciar sesión

// Si recibimos correctamente por POST los datos del formulario, guardamos esos datos (email y contraseña) en variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Obtenemos toda la info de la tabla "USUARIOS" donde el email sea el que hemos recibido del formulario
    $stmt = $mysqli->prepare("SELECT * FROM USUARIOS WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    //Guardamos en la variable "usuario" la info de la consulta ejecutada, y con fetch_assoc convertimos la info en array (así podemos luego mostrarla en pantalla)
    $usuario = $result->fetch_assoc();
    $stmt->close();

    // Si "usuario" y la contraseña recibida del formulario existe en mi base de datos, guardamos en sesiones el id, nombre y rol del usuario (info que tenemos en la base de datos)
    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nombre_usuario'];
        $_SESSION['user_rol'] = $usuario['rol'];

        header("Location: index.php"); //Redirigir al index principal
        exit;
    } else {
        $error = "Email o contraseña incorrectos"; //Mensaje de error
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Enganchados Por El Fútbol</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Iniciar Sesión</h2>
        <!--Si hay error, se mostrará en rojo-->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!--Enviamos por POST el email y contraseña que se ha escrito del formulario-->
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
            <!--Si no está registrado, puede registrarse clicando aquí-->
        <p class="text-center mt-3">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>