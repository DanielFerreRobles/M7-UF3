<?php
// Inicia la sesión para poder guardar datos del usuario si entra bien
session_start();

// Incluye el archivo de configuración (conexión a la BD)
require_once 'config.php';

// Comprueba si el formulario fue enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoge lo que el usuario escribió en el formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepara una consulta SQL segura para buscar al usuario por email
    $stmt = $mysqli->prepare("SELECT * FROM USUARIOS WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email); // "s" significa que es un string
    $stmt->execute();
    $result = $stmt->get_result();

    // Si encontró un usuario con ese email
    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc(); // Trae los datos del usuario como array

        // Verifica que la contraseña escrita coincide con la guardada (encriptada)
        if (password_verify($password, $usuario['password'])) {
            
            // Si está bien, guarda datos en la sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_name'] = $usuario['nombre_usuario'];
            $_SESSION['user_rol'] = $usuario['rol'];

            // Redirige al inicio de la página
            header('Location: index.php');
            exit;
        } else {
            // Si la contraseña está mal
            $error = "Contraseña incorrecta";
        }
    } else {
        // Si no existe el usuario
        $error = "Usuario no encontrado";
    }

    // Cierra la consulta y la conexión con la base de datos
    $stmt->close();
    $mysqli->close();
}
?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar sesión</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <h1 class="mb-4">Iniciar sesión</h1>

            <!-- Si hubo error, lo muestra en un alert rojo -->
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>

            <!-- Formulario de login -->
            <form action="" method="POST" class="bg-white p-4 shadow rounded">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </form>
        </div>
    </body>
    </html>