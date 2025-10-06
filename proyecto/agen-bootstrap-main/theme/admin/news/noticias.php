<?php
session_start();
include '../config.php'; // Configuración DB

// Verificar si liga_id está presente
if (!isset($_GET['liga_id'])) {
    header("Location: ../index.php");
    exit;
}

$liga_id = $_GET['liga_id'];

// Traer información de la liga (para la foto)
$stmtLiga = $mysqli->prepare("SELECT nombre, foto FROM LIGAS WHERE id=?");
$stmtLiga->bind_param("i", $liga_id);
$stmtLiga->execute();
$resultLiga = $stmtLiga->get_result();
$liga = $resultLiga->fetch_assoc();
$stmtLiga->close();

// Procesar comentarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'], $_POST['noticia_id'])) {
    $contenido = $_POST['comentario'];
    $noticia_id = $_POST['noticia_id'];
    $usuario_id = $_SESSION['user_id'];

    $fecha_comentario = date('Y-m-d H:i:s');

    $stmt = $mysqli->prepare("INSERT INTO COMENTARIOS (noticia_id, usuario_id, contenido, fecha_comentario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $noticia_id, $usuario_id, $contenido, $fecha_comentario);
    $stmt->execute();
    $stmt->close();

    header("Location: noticias.php?liga_id=$liga_id"); // recargar la página
    exit;
}

// Traer noticias de la liga
$stmtNoticias = $mysqli->prepare("SELECT n.*, u.nombre_usuario AS autor FROM NOTICIAS n JOIN USUARIOS u ON n.user_id=u.id WHERE n.liga_id=? ORDER BY n.id DESC");
$stmtNoticias->bind_param("i", $liga_id);
$stmtNoticias->execute();
$resultNoticias = $stmtNoticias->get_result();
$noticias = $resultNoticias->fetch_all(MYSQLI_ASSOC);
$stmtNoticias->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Noticias - <?php echo htmlspecialchars($liga['nombre']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">

<h1 class="mb-4"><?php echo htmlspecialchars($liga['nombre']); ?></h1>
<img src="<?php echo $liga['foto']; ?>" alt="<?php echo htmlspecialchars($liga['nombre']); ?>" class="img-fluid mb-4">

<?php foreach ($noticias as $noticia): ?>
<div class="card mb-4">
    <div class="card-body">
        <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
        <h5 class="text-muted"><?php echo htmlspecialchars($noticia['subtitulo']); ?></h5>
        <p><strong>Competición:</strong> <?php echo htmlspecialchars($noticia['competicion']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
        <p class="text-muted">Publicado por <?php echo htmlspecialchars($noticia['autor']); ?> el <?php echo $noticia['fecha_publicacion']; ?></p>

        <!-- Formulario de comentario -->
        <form method="POST" class="mt-3">
            <input type="hidden" name="noticia_id" value="<?php echo $noticia['id']; ?>">
            <div class="mb-2">
                <textarea name="comentario" class="form-control" placeholder="Escribe tu comentario..." rows="2" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Comentar</button>
        </form>

        <!-- Mostrar comentarios existentes -->
        <?php
        $stmtComentarios = $mysqli->prepare("SELECT c.*, u.nombre_usuario FROM COMENTARIOS c JOIN USUARIOS u ON c.usuario_id=u.id WHERE c.noticia_id=? ORDER BY c.id ASC");
        $stmtComentarios->bind_param("i", $noticia['id']);
        $stmtComentarios->execute();
        $resultComentarios = $stmtComentarios->get_result();
        $comentarios = $resultComentarios->fetch_all(MYSQLI_ASSOC);
        $stmtComentarios->close();
        ?>
        <?php if ($comentarios): ?>
            <div class="mt-3">
                <h6>Comentarios:</h6>
                <?php foreach ($comentarios as $c): ?>
                    <div class="border p-2 mb-2 rounded bg-light">
                        <strong><?php echo htmlspecialchars($c['nombre_usuario']); ?></strong> 
                        <span class="text-muted">- <?php echo $c['fecha_comentario']; ?></span>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($c['contenido'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<a href="../index.php" class="btn btn-secondary">Volver</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
