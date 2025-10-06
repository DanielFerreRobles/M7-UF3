<?php
session_start();
include '../../config.php'; // Configuración de la base de datos

// Verificar que se haya pasado el id de la noticia
if (!isset($_GET['id'])) {
    header("Location: addNew.php");
    exit;
}

$noticia_id = $_GET['id'];

// Traer la noticia a editar
$stmt = $mysqli->prepare("SELECT * FROM NOTICIAS WHERE id=?");
$stmt->bind_param("i", $noticia_id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();
$stmt->close();

// Traer ligas para el select
$ligas = $mysqli->query("SELECT id, nombre FROM LIGAS")->fetch_all(MYSQLI_ASSOC);

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $foto = $_POST['foto'];
    $subtitulo = $_POST['subtitulo'];
    $contenido = $_POST['contenido'];
    $liga_id = $_POST['liga_id'];
    $competicion = $_POST['competicion'] ?? '';
    $fecha_publicacion = $_POST['fecha_publicacion'] ?: date('Y-m-d H:i:s');

    $stmt = $mysqli->prepare("UPDATE NOTICIAS SET titulo=?, foto=?, subtitulo=?, contenido=?, liga_id=?, competicion=?, fecha_publicacion=? WHERE id=?");
    $stmt->bind_param("ssssissi", $titulo, $foto, $subtitulo, $contenido, $liga_id, $competicion, $fecha_publicacion, $noticia_id);
    $stmt->execute();
    $stmt->close();

    header("Location: addNew.php");
    exit;
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

<form method="POST">
    <input type="text" name="titulo" class="form-control mb-2" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
    <input type="text" name="foto" class="form-control mb-2" value="<?php echo htmlspecialchars($noticia['foto']); ?>" required>
    <input type="text" name="subtitulo" class="form-control mb-2" value="<?php echo htmlspecialchars($noticia['subtitulo']); ?>" required>
    <textarea name="contenido" class="form-control mb-2" rows="4" required><?php echo htmlspecialchars($noticia['contenido']); ?></textarea>
    <select name="liga_id" class="form-select mb-2" required>
        <?php foreach ($ligas as $liga): ?>
            <option value="<?php echo $liga['id']; ?>" <?php if ($liga['id'] == $noticia['liga_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($liga['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="competicion" class="form-control mb-2" value="<?php echo htmlspecialchars($noticia['competicion']); ?>" placeholder="Competición">
    <input type="datetime-local" name="fecha_publicacion" class="form-control mb-2" 
           value="<?php echo date('Y-m-d\TH:i', strtotime($noticia['fecha_publicacion'])); ?>">
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="addNew.php" class="btn btn-secondary">Cancelar</a>
</form>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>