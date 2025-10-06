<?php
session_start();
include '../../config.php';

// Obtener ID de la liga
$liga_id = isset($_GET['liga_id']) ? (int)$_GET['liga_id'] : 0;

// Información de la liga
$ligaNombre = "Todas las noticias";
$ligaLogo = ""; 
if ($liga_id > 0) {
    $ligaStmt = $mysqli->prepare("SELECT nombre, foto FROM LIGAS WHERE id = ?");
    $ligaStmt->bind_param("i", $liga_id);
    $ligaStmt->execute();
    $ligaResult = $ligaStmt->get_result();
    if ($ligaResult->num_rows > 0) {
        $ligaData = $ligaResult->fetch_assoc();
        $ligaNombre = $ligaData['nombre'];
        $ligaLogo = $ligaData['foto'];
    }
    $ligaStmt->close();
}

// Obtener noticias de la liga
if ($liga_id > 0) {
    $stmt = $mysqli->prepare("SELECT * FROM NOTICIAS WHERE liga_id = ? ORDER BY fecha_publicacion DESC");
    $stmt->bind_param("i", $liga_id);
} else {
    $stmt = $mysqli->prepare("SELECT * FROM NOTICIAS ORDER BY fecha_publicacion DESC");
}

$stmt->execute();
$result = $stmt->get_result();
$noticias = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Procesar nuevo comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenido']) && isset($_POST['noticia_id']) && isset($_SESSION['user_id'])) {
    $contenido = $_POST['contenido'];
    $noticia_id = $_POST['noticia_id'];
    $usuario_id = $_SESSION['user_id'];

    $stmtComInsert = $mysqli->prepare("INSERT INTO COMENTARIOS (noticia_id, usuario_id, contenido, fecha_comentario) VALUES (?, ?, ?, NOW())");
    if ($stmtComInsert) {
        $stmtComInsert->bind_param("iis", $noticia_id, $usuario_id, $contenido);
        $stmtComInsert->execute();
        $stmtComInsert->close();
        header("Location: noticias.php?liga_id=".$liga_id);
        exit();
    } else {
        echo "<p>Error insertando comentario: ".$mysqli->error."</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Noticias - <?php echo htmlspecialchars($ligaNombre); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-white">

<?php if ($ligaLogo): ?>
<div class="position-fixed w-100 h-100 top-0 start-0">
    <img src="<?php echo htmlspecialchars($ligaLogo); ?>" class="w-100 h-100" style="object-fit: cover;" alt="Fondo liga">
    <div class="w-100 h-100 position-absolute top-0 start-0 bg-dark bg-opacity-50"></div>
</div>
<?php endif; ?>

<div class="container py-5 position-relative">
    <h1 class="mb-4 text-center"><?php echo htmlspecialchars($ligaNombre); ?></h1>

    <?php if (!empty($noticias)): ?>
        <?php foreach ($noticias as $noticia): ?>
        <div class="col-12 mb-4">
            <div class="card bg-light text-dark shadow-sm">
                <?php if (!empty($noticia['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($noticia['foto']); ?>" 
                         class="card-img-top" 
                         alt="Foto noticia" 
                         style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                    <h5 class="card-subtitle mb-3 text-muted"><?php echo htmlspecialchars($noticia['subtitulo']); ?></h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
                    <p class="text-muted">Publicado el <?php echo date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])); ?></p>

                    <!-- Mostrar comentarios -->
                    <div class="mt-3">
                        <h6>Comentarios:</h6>
                        <?php
                        $stmtCom = $mysqli->prepare("SELECT C.contenido, C.fecha_comentario, U.nombre_usuario 
                                                     FROM COMENTARIOS C 
                                                     JOIN USUARIOS U ON C.usuario_id = U.id 
                                                     WHERE C.noticia_id = ? 
                                                     ORDER BY C.fecha_comentario ASC");
                        $comentarios = [];
                        if ($stmtCom) {
                            $stmtCom->bind_param("i", $noticia['id']);
                            $stmtCom->execute();
                            $resultCom = $stmtCom->get_result();
                            $comentarios = $resultCom->fetch_all(MYSQLI_ASSOC);
                            $stmtCom->close();
                        }
                        if (!empty($comentarios)):
                            foreach ($comentarios as $com):
                        ?>
                        <p><strong><?php echo htmlspecialchars($com['nombre_usuario']); ?>:</strong> <?php echo nl2br(htmlspecialchars($com['contenido'])); ?> <small class="text-muted">(<?php echo date('d/m/Y H:i', strtotime($com['fecha_comentario'])); ?>)</small></p>
                        <?php
                            endforeach;
                        else:
                            echo "<p class='text-muted'>No hay comentarios aún.</p>";
                        endif;
                        ?>

                        <!-- Formulario para nuevo comentario -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="" method="POST" class="mt-2">
                            <input type="hidden" name="noticia_id" value="<?php echo $noticia['id']; ?>">
                            <div class="mb-2">
                                <textarea name="contenido" class="form-control" rows="2" placeholder="Escribe un comentario..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Comentar</button>
                        </form>
                        <?php else: ?>
                            <p class="text-muted">Inicia sesión para comentar.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center">No hay noticias disponibles para esta liga.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>