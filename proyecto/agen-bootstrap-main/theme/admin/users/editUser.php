<?php
include '../../config.php';

$id = $_GET['id']; 

$sql = "SELECT * FROM USERS WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $_POST['old_password'];
    $rol = $_POST['rol'];
    $age = $_POST['age'];
    $photo = $_POST['photo'];

    $updateSql = "UPDATE USERS SET name = ?, email = ?, password = ?, rol = ?, age = ?, photo = ? WHERE id = ?";
    $updateConsulta = $mysqli->prepare($updateSql);

    $updateConsulta->bind_param("ssssisi", $name, $email, $password, $rol, $age, $photo, $id);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1>Editar Usuario</h1>
        <form method="POST">
            <input type="hidden" name="old_password" value="<?php echo $user['password']; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="text-muted">Deja este campo vacío si no deseas cambiar la contraseña.</small>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="user" <?php echo ($user['rol'] == 'user') ? 'selected' : ''; ?>>Usuario</option>
                    <option value="admin" <?php echo ($user['rol'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="age" class="form-label">Edad</label>
                <input type="number" class="form-control" id="age" name="age" value="<?php echo $user['age']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Foto de Perfil (URL)</label>
                <input type="url" class="form-control" id="photo" name="photo" value="<?php echo $user['photo']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>