<?php
session_start();
include '../../config.php'; // Conexión a la base de datos

//Si los datos han sido correctamente recibidos por POST, los guardamos en variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    //Hashemos la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserto los datos recibidos del formulario en mi tabla "USUARIOS"
    $stmt = $mysqli->prepare("INSERT INTO USUARIOS (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $hashed_password, $rol);

    //Si la consulta de INSERT INTO se ejecuta correctamente, dará un mensaje de éxito, sino, dará error
    if ($stmt->execute()) {
        $success = "Usuario agregado correctamente.";
    } else {
        $error = "Error al agregar usuario.";
    }
}

// Obtenemos la info de nuestra tabla "USUARIOS" ordenada ascendentemente y convertimos la info en array para sacarla por pantalla
$result = $mysqli->query("SELECT * FROM USUARIOS ORDER BY id ASC");
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario - Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4">Agregar Usuario</h2>
    <a href="../../index.php" class="btn btn-secondary mb-4">← Volver al inicio</a>
    <!--Si hay error se mostrará en rojo-->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!--Si hay éxito al añadir el usuario, se mostrará en verde-->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

        <!--Enviamos por POST el nombre de usuario, email, contraseña y rol. Estos datos se reciben arriba y hacemos el INSERT INTO !-->
    <form method="POST" class="mb-5">
        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-select" name="rol" id="rol" required>
                <option value="">Selecciona un rol</option>
                <option value="usuario">Usuario</option>
                <option value="admin">Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Usuario</button>
    </form>

    <!-- Tabla de usuarios existentes -->
    <h3 class="mb-3">Usuarios existentes</h3>
    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['nombre_usuario']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['rol']; ?></td>
                    <td>
                        <!--Si queremos editar un usuario, clicando aquí nos llevará a poder editarlo, pasandole el id del usuario correspondiente !-->
                        <a href="editUser.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                        
                        <!--Si queremos eliminar un usuario, clicando aquí nos llevará a poder eliminarlo, pasandole el id del usuario correspondiente !-->
                        <a href="deleteUser.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>