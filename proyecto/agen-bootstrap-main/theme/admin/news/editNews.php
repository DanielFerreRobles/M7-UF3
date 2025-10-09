<?php
session_start();
include '../../config.php'; // Conexión a mi base de datos

// Si NO recibimos el id de la noticia que queremos editar, NO podremos editarla
if (!isset($_GET['id'])) {
    header("Location: addNew.php");
    exit;
}

// Si recibimos correctamente el id de la noticia que queremos editar, SI podremos editarla. Guardamos el id en la variable "noticia_id"
$noticia_id = $_GET['id'];

// Obtenemos datos actuales de la noticia
$stmt = $mysqli->prepare("SELECT * FROM NOTICIAS WHERE id=?");
$stmt->bind_param("i", $noticia_id);
$stmt->execute();
$noticia = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Si la noticia no existe, redirige a la página de añadir noticias
if (!$noticia) {
    header("Location: addNew.php");
    exit;
}

// Obtenemos el id y el nombre de las ligas
$ligas = $mysqli->query("SELECT id, nombre FROM LIGAS")->fetch_all(MYSQLI_ASSOC);

// Si los datos han sido correctamente recibidos por POST, los guardamos en variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $foto = $_POST['foto'];
    $subtitulo = $_POST['subtitulo'];
    $contenido = $_POST['contenido'];
    $liga_id = $_POST['liga_id'];
    $jornada = $_POST['jornada'];
    $competicion = $_POST['competicion'];
    $fecha_publicacion = $_POST['fecha_publicacion'] ?: date('Y-m-d H:i:s');

    // Actualizamos la info con el UPDATE
    $stmt = $mysqli->prepare("UPDATE NOTICIAS SET titulo=?, foto=?, subtitulo=?, contenido=?, liga_id=?, jornada=?, competicion=?, fecha_publicacion=? WHERE id=?");
    $stmt->bind_param("ssssisssi", $titulo, $foto, $subtitulo, $contenido, $liga_id, $jornada, $competicion, $fecha_publicacion, $noticia_id);
    // Ejecutamos UPDATE
    $stmt->execute();

    // Si se ejecuta correctamente la consulta, mostrará un mensaje de éxito
    if ($stmt->execute()) {
        $success = "Noticia actualizada correctamente.";

        // Volvemos a hacer SELECT para obtener la info actualizada de la noticia
        $stmt = $mysqli->prepare("SELECT * FROM NOTICIAS WHERE id=?");
        $stmt->bind_param("i", $noticia_id);
        $stmt->execute();
        $noticia = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        $error = "Error al actualizar la noticia."; // Mensaje por si da error
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Noticia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

<h1 class="mb-4">Editar Noticia</h1>

<!--Si hay mensaje de error, se mostrará en rojo-->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!--Si hay mensaje de éxito, se mostrará en verde-->
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<!-- Enviamos por POST los datos del formulario. Estos datos se reciben arriba y hacemos el UPDATE !-->
<form method="POST">
    <!-- Título -->
    <input type="text" name="titulo" class="form-control mb-2" value="<?php echo $noticia['titulo']; ?>" required>

    <!-- URL de la foto -->
    <input type="text" name="foto" class="form-control mb-2" value="<?php echo $noticia['foto']; ?>" required>

    <!-- Subtítulo -->
    <input type="text" name="subtitulo" class="form-control mb-2" value="<?php echo $noticia['subtitulo']; ?>" required>

    <!-- Contenido -->
    <textarea name="contenido" class="form-control mb-2" rows="4" required><?php echo $noticia['contenido']; ?></textarea>

    <!-- Liga -->
    <!-- Menú desplegable para elegir la liga -->
    <select name="liga_id" class="form-select mb-2" required>
        <!-- Primera opción: mensaje inicial, sin valor -->
        <option value="">Selecciona una liga</option>

        <!-- Creamos una opción por cada liga que tenemos en la base de datos -->
        <?php foreach ($ligas as $liga): ?>
            <!-- Guardamos el ID de la liga como valor y seleccionamos la liga que ya tiene la noticia -->
            <option 
                value="<?php echo $liga['id']; ?>" 
                <?php if ($liga['id'] == $noticia['liga_id']) echo 'selected'; ?>
            >
                <!-- Mostramos el nombre de la liga al usuario -->
                <?php echo $liga['nombre']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <!-- Jornada -->
    <select name="jornada" class="form-select mb-2" required>
        <option value="">Selecciona la jornada</option>
        <?php for ($i = 1; $i <= 38; $i++): ?>
            <option value="<?php echo $i; ?>" <?php if ($i == $noticia['jornada']) echo 'selected'; ?>>Jornada <?php echo $i; ?></option>
        <?php endfor; ?>
    </select>

    <!-- Competición -->
    <input type="text" name="competicion" class="form-control mb-2" value="<?php echo $noticia['competicion']; ?>" placeholder="Competición">

    <!-- Fecha de publicación -->
    <input type="datetime-local" name="fecha_publicacion" class="form-control mb-2" 
           value="<?php echo date('Y-m-d\TH:i', strtotime($noticia['fecha_publicacion'])); ?>">

    <button type="submit" class="btn btn-primary">Guardar Cambios</button>

    <!-- Por si nos equivocamos y queremos volver al listado o añadir nuevas noticias -->
    <a href="addNew.php" class="btn btn-secondary">Cancelar</a>
</form>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>