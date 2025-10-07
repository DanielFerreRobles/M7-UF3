<?php
session_start();
include '../../config.php'; // Ajusta según tu estructura de carpetas

// Traer ligas
$result = $mysqli->query("SELECT id, nombre FROM LIGAS");
$ligas = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Traer noticias existentes
$resultNoticias = $mysqli->query("SELECT * FROM NOTICIAS ORDER BY id DESC");
$noticias = $resultNoticias ? $resultNoticias->fetch_all(MYSQLI_ASSOC) : [];

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $foto = $_POST['foto'];
    $subtitulo = $_POST['subtitulo'];
    $contenido = $_POST['contenido'];
    $liga_id = $_POST['liga_id'];
    $jornada = $_POST['jornada']; // nueva
    $competicion = $_POST['competicion'] ?? '';
    $fecha_publicacion = $_POST['fecha_publicacion'] ?: date('Y-m-d H:i:s');
    $user_id = $_SESSION['user_id']; // corregido

    $stmt = $mysqli->prepare("INSERT INTO NOTICIAS (titulo, foto, subtitulo, contenido, liga_id, user_id, competicion, fecha_publicacion, jornada) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssisssi", $titulo, $foto, $subtitulo, $contenido, $liga_id, $user_id, $competicion, $fecha_publicacion, $jornada);
        $ok = $stmt->execute();
        if ($ok) {
            header("Location: addNew.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error al insertar la noticia: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error en la preparación de la consulta: " . $mysqli->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Añadir Noticia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

<h1 class="mb-4">Añadir Noticia</h1>

<form method="POST" class="mb-5">
    <input type="text" name="titulo" class="form-control mb-2" placeholder="Título" required>
    <input type="text" name="foto" class="form-control mb-2" placeholder="URL de la imagen" required>
    <input type="text" name="subtitulo" class="form-control mb-2" placeholder="Subtítulo" required>
    <textarea name="contenido" class="form-control mb-2" placeholder="Contenido" rows="4" required></textarea>
    
    <select name="liga_id" class="form-select mb-2" required>
        <option value="">Selecciona una liga</option>
        <?php foreach ($ligas as $liga): ?>
            <option value="<?php echo $liga['id']; ?>"><?php echo htmlspecialchars($liga['nombre']); ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Selector de jornada -->
    <select name="jornada" class="form-select mb-2" required>
        <option value="">Selecciona la jornada</option>
        <?php for ($i = 1; $i <= 38; $i++): ?>
            <option value="<?php echo $i; ?>">Jornada <?php echo $i; ?></option>
        <?php endfor; ?>
    </select>

    <input type="text" name="competicion" class="form-control mb-2" placeholder="Competición">
    <input type="datetime-local" name="fecha_publicacion" class="form-control mb-2">
    <button type="submit" class="btn btn-primary">Añadir Noticia</button>
</form>

<h2>Noticias existentes</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Foto</th>
            <th>Subtítulo</th>
            <th>Contenido</th>
            <th>Competición</th>
            <th>Liga</th>
            <th>Jornada</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($noticias as $noticia): ?>
        <tr>
            <td><?php echo $noticia['id']; ?></td>
            <td><?php echo htmlspecialchars($noticia['titulo']); ?></td>
            <td><img src="<?php echo $noticia['foto']; ?>" width="80"></td>
            <td><?php echo htmlspecialchars($noticia['subtitulo']); ?></td>
            <td><?php echo htmlspecialchars($noticia['contenido']); ?></td>
            <td><?php echo htmlspecialchars($noticia['competicion']); ?></td>
            <td><?php echo $noticia['liga_id']; ?></td>
            <td><?php echo $noticia['jornada']; ?></td>
            <td><?php echo $noticia['fecha_publicacion']; ?></td>
            <td>
                <a href="editNews.php?id=<?php echo $noticia['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="deleteNews.php?id=<?php echo $noticia['id']; ?>" class="btn btn-sm btn-danger">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>