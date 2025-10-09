<?php
session_start();
include '../../config.php'; // Conexión a la base de datos

// Si NO recibimos id de liga, SI podremos ver las noticias correspondientes
if (!isset($_GET['liga_id'])) {
    header("Location: /../../index.php");
    exit;
}

// Si recibimos id de liga, SI podremos ver las noticias correspondientes
$liga_id = $_GET['liga_id'];

//Si recibimos correctamente el número de la jornada que quiere ver el usuario la guardamos en una variable llamada "jornada"
if (isset($_POST['jornada'])) {
    $jornada = $_POST['jornada'];
} else {
    $jornada = 1; //Por defecto (al entrar a ver las noticias) el usuario verá las de la jornada 1
}

//Si recibimos correctamente el comentario y el id de la noticia, guardamos esta info en variables
if (isset($_POST['comentario'], $_POST['noticia_id'])) {
    $contenido = $_POST['comentario'];
    $noticia_id = $_POST['noticia_id'];
    $usuario_id = $_SESSION['user_id'];
    $fecha_comentario = date('Y-m-d H:i:s');

    //Introducimos en la tabla "COMENTARIOS" la info que hemos recibido del formulario
    $stmt = $mysqli->prepare("INSERT INTO COMENTARIOS (noticia_id, usuario_id, contenido, fecha_comentario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $noticia_id, $usuario_id, $contenido, $fecha_comentario);
    $stmt->execute();
    $stmt->close();

    // Recargar para no duplicar comentario
    header("Location: noticias.php"); 
    exit;
}

// Obtenemos el nombre y la foto de la liga con el id correspondiente y convertimos en array para mostrar la info por pantalla
$stmtLiga = $mysqli->prepare("SELECT nombre, foto FROM LIGAS WHERE id=?");
$stmtLiga->bind_param("i", $liga_id);
$stmtLiga->execute();
$resultLiga = $stmtLiga->get_result();
$liga = $resultLiga->fetch_assoc();
$stmtLiga->close();


$stmtNoticias = $mysqli->prepare("SELECT n.*, u.nombre_usuario AS autor FROM NOTICIAS n JOIN USUARIOS u ON n.user_id=u.id WHERE n.liga_id=? AND n.jornada=? ORDER BY n.id DESC");
$stmtNoticias->bind_param("ii", $liga_id, $jornada);
$stmtNoticias->execute();
$resultNoticias = $stmtNoticias->get_result();
$noticias = $resultNoticias->fetch_all(MYSQLI_ASSOC);
$stmtNoticias->close();

if (!$noticias) $noticias = [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Noticias - <?php echo $liga['nombre']; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Fondo con la imagen de la liga -->
<div class="position-relative vh-100" style="background-image: url('<?php echo $liga['foto']; ?>'); background-size: cover; background-position: center;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>

    <div class="container position-relative py-5 text-white" style="z-index: 1; overflow-y: auto; max-height: 100vh;">
        <h1 class="text-center mb-4"><?php echo $liga['nombre']; ?></h1>

        <!-- Selector de jornada (POST) -->
        <form method="POST" class="mb-4 d-flex align-items-center gap-2">
            <input type="hidden" name="liga_id" value="<?php echo $liga_id; ?>">
            <label for="jornada" class="form-label mb-0">Jornada:</label>
            <select name="jornada" id="jornada" class="form-select w-auto">
                <?php for ($i=1; $i<=38; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if($i==$jornada) echo 'selected'; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        </form>

        <?php if (count($noticias) > 0): ?>
            <?php foreach ($noticias as $noticia): ?>
            <div class="card mb-4">
                <?php if (!empty($noticia['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($noticia['foto']); ?>" class="img-fluid rounded" alt="Imagen noticia">
                <?php endif; ?>
                <div class="card-body text-dark">
                    <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                    <h5 class="text-muted"><?php echo htmlspecialchars($noticia['subtitulo']); ?></h5>
                    <p><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
                    <p class="text-muted small">Publicado por <?php echo htmlspecialchars($noticia['autor']); ?> el <?php echo $noticia['fecha_publicacion']; ?></p>

                    <!-- Formulario de comentario -->
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="liga_id" value="<?php echo $liga_id; ?>">
                        <input type="hidden" name="jornada" value="<?php echo $jornada; ?>">
                        <input type="hidden" name="noticia_id" value="<?php echo $noticia['id']; ?>">
                        <div class="mb-2">
                            <textarea name="comentario" class="form-control" placeholder="Escribe tu comentario..." rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Comentar</button>
                    </form>

                    <!-- Comentarios existentes -->
                    <?php
                    $stmtComentarios = $mysqli->prepare("
                        SELECT c.*, u.nombre_usuario 
                        FROM COMENTARIOS c 
                        JOIN USUARIOS u ON c.usuario_id=u.id 
                        WHERE c.noticia_id=? 
                        ORDER BY c.id ASC
                    ");
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
                        <div class="border p-2 mb-2 rounded bg-light text-dark">
                            <strong><?php echo htmlspecialchars($c['nombre_usuario']); ?></strong> 
                            <span class="text-muted small"> - <?php echo $c['fecha_comentario']; ?></span>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($c['contenido'])); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-dark">No hay noticias disponibles para esta liga y jornada.</div>
        <?php endif; ?>

        <a href="../../index.php" class="btn btn-secondary">Volver</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>