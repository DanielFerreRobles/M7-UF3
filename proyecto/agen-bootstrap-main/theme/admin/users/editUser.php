<?php
session_start();
include '../../config.php'; // Conexión a la base de datos

// ================================
// 1️⃣ OBTENER ID DEL USUARIO A EDITAR
// ================================
if (!isset($_GET['id'])) {
    die("Error: no se ha especificado un ID de usuario.");
}
$id = intval($_GET['id']); // Convertimos a número por seguridad

// ================================
// 2️⃣ CONSULTAR DATOS ACTUALES DEL USUARIO
// ================================
$stmt = $mysqli->prepare("SELECT * FROM USUARIOS WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    die("Error: usuario no encontrado.");
}
$stmt->close();

// ================================
// 3️⃣ PROCESAR FORMULARIO DE ACTUALIZACIÓN
// ================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $email = trim($_POST['email']);
    $rol = $_POST['rol'];
    $password = $_POST['password']; // Puede venir vacío

    // ==========================
    // 3.1️⃣ Si se cambia la contraseña → encriptar
    // ==========================
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $hashedPassword = $usuario['password']; // mantener la actual
    }

    // ==========================
    // 3.2️⃣ Actualizar los datos
    // ==========================
    $updateStmt = $mysqli->prepare("UPDATE USUARIOS SET nombre_usuario = ?, email = ?, password = ?, rol = ? WHERE id = ?");
    $updateStmt->bind_param("ssssi", $nombre_usuario, $email, $hashedPassword, $rol, $id);

    if ($updateStmt->execute()) {
        echo '<div class="alert alert-success text-center mt-3">✅ ¡Usuario actualizado correctamente!</div>';
        // Actualizar variable $usuario para mostrar los nuevos datos sin recargar manualmente
        $usuario['nombre_usuario'] = $nombre_usuario;
        $usuario['email'] = $email;
        $usuario['rol'] = $rol;
        $usuario['password'] = $hashedPassword;
    } else {
        echo '<div class="alert alert-danger text-center mt-3">❌ Error al actualizar el usuario: ' . $updateStmt->error . '</div>';
    }

    $updateStmt->close();
}

$mysqli->close();
?>

<!-- ================================
 4️⃣ HTML — FORMULARIO DE EDICIÓN
================================ -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="mb-4">✏️ Editar Usuario</h2>

            <form method="POST">
                <!-- Campo nombre -->
                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre de Usuario:</label>
                    <input 
                        type="text" 
                        id="nombre_usuario" 
                        name="nombre_usuario" 
                        class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" 
                        required>
                </div>

                <!-- Campo email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico:</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['email']); ?>" 
                        required>
                </div>

                <!-- Campo contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (opcional):</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Deja vacío para mantener la actual">
                </div>

                <!-- Campo rol -->
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol:</label>
                    <select id="rol" name="rol" class="form-select" required>
                        <option value="user" <?php echo ($usuario['rol'] === 'user') ? 'selected' : ''; ?>>Usuario</option>
                        <option value="admin" <?php echo ($usuario['rol'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
