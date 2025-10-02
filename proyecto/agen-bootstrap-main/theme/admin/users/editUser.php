<?php
// Inicia sesión
session_start();

// Incluye la configuración de la base de datos
include '../../config.php';

// ===============================
// 1. OBTENER DATOS DEL USUARIO
// ===============================
// Tomar el ID del usuario desde la URL
$id = $_GET['id'];

// Preparar consulta para traer los datos del usuario
$sql = "SELECT * FROM USUARIOS WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc(); // Guardar datos del usuario en un array asociativo
$stmt->close();

// ===============================
// 2. PROCESAR FORMULARIO DE EDICIÓN
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos enviados desde el formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    // Si se escribe una nueva contraseña, se encripta; si no, se usa la antigua
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $_POST['old_password'];
    $rol = $_POST['rol'];

    // Preparar la consulta SQL para actualizar usuario
    $updateSql = "UPDATE USUARIOS SET nombre_usuario = ?, email = ?, password = ?, rol = ? WHERE id = ?";
    $updateConsulta = $mysqli->prepare($updateSql);

    // Vincular parámetros
    $updateConsulta->bind_param("ssssi", $nombre_usuario, $email, $password, $rol, $id);

    // Ejecutar consulta y mostrar mensaje según resultado
    if ($updateConsulta->execute()) {
        echo '<div class="alert alert-success">¡Usuario actualizado correctamente!</div>';
    } else {
        echo '<div class="alert alert-danger">Error al actualizar el usuario.</div>';
    }

    $updateConsulta->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Editar Usuario</h1>

    <!-- FORMULARIO DE EDICIÓN -->
    <form method="POST">
        <!-- Campo oculto para guardar contraseña antigua -->
        <input type="hidden" name="old_password" value="<?php echo $usuario['password']; ?>">

        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo $usuario['nombre_usuario']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password">
            <small class="text-muted">Deja este campo vacío si no deseas cambiar la contraseña.</small>
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select id="rol" name="rol" class="form-select" required>
                <option value="user" <?php echo ($usuario['rol'] == 'user') ? 'selected' : ''; ?>>Usuario</option>
                <option value="admin" <?php echo ($usuario['rol'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>