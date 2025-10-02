<?php
// ===============================
// 1. INICIO DE SESIÓN Y CONEXIÓN
// ===============================
session_start();
include '../../config.php';  // Incluye conexión a la base de datos

// ===============================
// 2. OBTENER TODAS LAS NOTICIAS
// ===============================
$result = $mysqli->query("SELECT * FROM NOTICIAS ORDER BY id DESC");
$arrayNoticias = $result->fetch_all(MYSQLI_ASSOC);

// ===============================
// 3. OBTENER TODAS LAS LIGAS PARA EL SELECT
// ===============================
$ligasResult = $mysqli->query("SELECT id, nombre FROM LIGAS");
$ligas = $ligasResult->fetch_all(MYSQLI_ASSOC);

// ===============================
// 4. PROCESAR FORMULARIO DE AÑADIR NOTICIA
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $titulo = $_POST['titulo'];
    $foto = $_POST['foto']; // URL de la foto
    $subtitulo = $_POST['subtitulo'];
    $contenido = $_POST['contenido'];
    $liga_id = $_POST['liga_id'];
    $user_id = $_SESSION['user_id'] ?? 1; // Si no hay sesión, usar 1 como default
    $competicion = $_POST['competicion'] ?? 'N/A';
    $fecha_publicacion = $_POST['fecha_publicacion'];

    // Preparar sentencia SQL para insertar noticia
    $stmt = $mysqli->prepare("INSERT INTO NOTICIAS (titulo, foto, subtitulo, contenido, liga_id, user_id, competicion, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    // Vincular parámetros
    $stmt->bind_param('ssssisss', $titulo, $foto, $subtitulo, $contenido, $liga_id, $user_id, $competicion, $fecha_publicacion);

    // Ejecutar y mostrar mensaje según resultado
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">¡Noticia añadida correctamente!</div>';
        header("Refresh:1"); // Refresca la página para ver la noticia añadida
    } else {
        echo '<div class="alert alert-danger">Error al agregar noticia: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}

// Cerrar conexión al final
$mysqli->close();
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
    <!-- FORMULARIO PARA AÑADIR NOTICIA -->
    <div class="card">
        <div class="card-body">
            <h2>Añadir Noticia</h2>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Título:</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto (URL):</label>
                    <input type="text" name="foto" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subtítulo:</label>
                    <input type="text" name="subtitulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contenido:</label>
                    <textarea name="contenido" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Liga:</label>
                    <select name="liga_id" class="form-select" required>
                        <option value="">Selecciona una liga</option>
                        <?php foreach ($ligas as $liga): ?>
                            <option value="<?php echo $liga['id']; ?>"><?php echo htmlspecialchars($liga['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Competición:</label>
                    <input type="text" name="competicion" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha de publicación:</label>
                    <input type="datetime-local" name="fecha_publicacion" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Añadir Noticia</button>
            </form>
        </div>
    </div>

    <!-- TABLA DE NOTICIAS EXISTENTES -->
    <h2 class="mt-4">Noticias añadidas</h2>
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
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($arrayNoticias as $noticia): ?>
            <tr>
                <td><?php echo $noticia['id']; ?></td>
                <td><?php echo $noticia['titulo']; ?></td>
                <td><img src="<?php echo $noticia['foto']; ?>" alt="foto" width="100"></td>
                <td><?php echo $noticia['subtitulo']; ?></td>
                <td><?php echo $noticia['contenido']; ?></td>
                <td><?php echo $noticia['competicion']; ?></td>
                <td><?php echo $noticia['liga_id']; ?></td>
                <td><?php echo $noticia['fecha_publicacion']; ?></td>
                <td>
                    <a href="editNews.php?id=<?php echo $noticia['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                    <a href="deleteNews.php?id=<?php echo $noticia['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>