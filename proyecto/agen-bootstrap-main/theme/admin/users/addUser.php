<?php
session_start();
include '../../config.php';

$result = $mysqli->query("SELECT * FROM USUARIOS ORDER BY id DESC");
$arrayUsuarios = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $mysqli->prepare(
        "INSERT INTO USUARIOS (nombre_usuario, email, password, rol) 
         VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('ssss', $nombre_usuario, $email, $password, $rol);

    if ($stmt->execute()) {
        echo '¡Usuario añadido correctamente!';
        exit();
    } else {
        echo 'Error al añadir usuario: ' . $stmt->error;
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
    <title>Añadir Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Añadir Usuario</h1>

        <form method="POST">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol:</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Añadir Usuario</button>
        </form>

        <h2 class="mt-4">Usuarios Añadidos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Correo Electrónico</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrayUsuarios as $usuario) { ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['nombre_usuario']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo $usuario['rol']; ?></td>
                    <td>
                        <a href="editUser.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="deleteUser.php?id=<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>