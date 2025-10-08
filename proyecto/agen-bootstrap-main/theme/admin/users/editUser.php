<?php
session_start();
include '../../config.php'; // Conexión a mi base de datos

// Si NO recibimos el id del usuario que queremos editar, NO podremos editarlo
if (!isset($_GET['id'])) {
    header("Location: addUser.php");
    exit;
}

//Si recibimos correctamente el id del usuario que queremos editar, SI podremos editarlo. Guardamos el id en la variable "usuario_id"
$usuario_id = $_GET['id'];

// Obtenemos datos actuales del usuario
$stmt = $mysqli->prepare("SELECT nombre_usuario, email, rol FROM USUARIOS WHERE id=?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {  // Si el número de filas NO es igual a 1
    header("Location: addUser.php"); //redirige al usuario a la página addUser.php
    exit;                            //y detiene el script para que no siga ejecutándose
}

//Guardamos en la variable "usuario" la info del SELECT convertido en array
$usuario = $resultado->fetch_assoc();

//Si los datos han sido correctamente recibidos por POST, los guardamos en variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = $_POST['password'];

    //Si le pone una contraseña nueva al usuario, actualiza la tabla "USUARIOS" con los datos recibidos del formulario
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("UPDATE USUARIOS SET nombre_usuario=?, email=?, password=?, rol=? WHERE id=?");
        $stmt->bind_param("ssssi", $nombre, $email, $hashed_password, $rol, $usuario_id);
    } else {
        //Si NO le pone una contraseña nueva al usuario, actualiza la tabla "USUARIOS" con los datos recibidos del formulario sin la contraseña
        $stmt = $mysqli->prepare("UPDATE USUARIOS SET nombre_usuario=?, email=?, rol=? WHERE id=?");
        $stmt->bind_param("sssi", $nombre, $email, $rol, $usuario_id);
    }

    //Si se ejecuta correctamente la consulta, mostrará en verde un mensaje de éxito y guardamos en el array "usuario" la info actualizada de nuestras variables
    if ($stmt->execute()) {
        $success = "Usuario actualizado correctamente.";
        $usuario['nombre_usuario'] = $nombre;
        $usuario['email'] = $email;
        $usuario['rol'] = $rol;
    } else {
        $error = "Error al actualizar usuario."; //Mensaje por si da error
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

<!--Si hay mensaje de error, se mostrará en rojo-->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!--Si hay mensaje de éxito, se mostrará en verde-->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!--Enviamos por POST el nombre de usuario, email, contraseña y rol. Estos datos se reciben arriba y hacemos el UPDATE !-->
    <form method="POST">
        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" value="<?php echo $usuario['nombre_usuario']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="<?php echo $usuario['email']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-select" name="rol" id="rol" required>
                <!--Que salga Usuario si es el rol actual !-->
                <option value="usuario" <?php if($usuario['rol'] === 'usuario') echo 'selected'; ?>>Usuario</option>

                <!--Que salga Administradpr si es el rol actual !-->
                <option value="admin" <?php if($usuario['rol'] === 'admin') echo 'selected'; ?>>Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>

        <!--Si clicamos aquí, iremos a la página para añadir usuarios !-->
        <a href="addUser.php" class="btn btn-secondary">Volver a Usuarios</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>