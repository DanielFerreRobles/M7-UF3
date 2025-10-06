<?php
session_start();
include 'config.php'; // Conexión usando $mysqli

// Solo admins pueden acceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Obtener id del usuario a editar
if (!isset($_GET['id'])) {
    header("Location: addUser.php");
    exit;
}

$usuario_id = $_GET['id'];

// Traer datos actuales del usuario
$stmt = $mysqli->prepare("SELECT nombre_usuario, email, rol FROM USUARIOS WHERE id=?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    header("Location: addUser.php");
    exit;
}

$usuario = $resultado->fetch_assoc();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = $_POST['password'];

    if (!empty($password)) {
        // Actualizar con nueva contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("UPDATE USUARIOS SET nombre_usuario=?, email=?, password=?, rol=? WHERE id=?");
        $stmt->bind_param("ssssi", $nombre, $email, $hashed_password, $rol, $usuario_id);
    } else {
        // Actualizar sin cambiar contraseña
        $stmt = $mysqli->prepare("UPDATE USUARIOS SET nombre_usuario=?, email=?, rol=? WHERE id=?");
        $stmt->bind_param("sssi", $nombre, $email, $rol, $usuario_id);
    }

    if ($stmt->execute()) {
        $success = "Usuario actualizado correctamente.";
        $usuario['nombre_usuario'] = $nombre;
        $usuario['email'] = $email;
        $usuario['rol'] = $rol;
    } else {
        $error = "Error al actualizar usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4">Editar Usuario</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-select" name="rol" id="rol" required>
                <option value="usuario" <?php if($usuario['rol'] === 'usuario') echo 'selected'; ?>>Usuario</option>
                <option value="admin" <?php if($usuario['rol'] === 'admin') echo 'selected'; ?>>Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        <a href="addUser.php" class="btn btn-secondary">Volver a Usuarios</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>