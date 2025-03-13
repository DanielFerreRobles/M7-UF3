<?php
session_start();
include '../../config.php';

$result = $mysqli->query("SELECT * FROM users ORDER BY id DESC");
$arrayUsers = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $age = $_POST['age'];
    $photo = $_POST['photo'];
    $data_register = date('Y-m-d'); 

    $stmt = $mysqli->prepare(
        "INSERT INTO users (name, email, password, rol, age, photo, data_register) 
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('sssssis', $name, $email, $password, $rol, $age, $photo, $data_register);

    if ($stmt->execute()) {
        echo 'Usuario añadido correctamente!';
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
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" id="name" name="name" class="form-control" required>
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

            <div class="mb-3">
                <label for="age" class="form-label">Edad:</label>
                <input type="number" id="age" name="age" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Foto de perfil (URL):</label>
                <input type="text" id="photo" name="photo" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Añadir Usuario</button>
        </form>

        <h2 class="mt-4">Usuarios Añadidos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Edad</th>
                    <th>Rol</th>
                    <th>Imagen</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrayUsers as $user) { ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['age']; ?></td>
                    <td><?php echo $user['rol']; ?></td>
                    <td><img src="<?php echo $user['photo']; ?>" alt="img" width="100"></td>
                    <td><?php echo $user['data_register']; ?></td>
                    <td>
                        <a href="editUser.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="deleteUser.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>