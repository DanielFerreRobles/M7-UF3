<?php
// Inicia o continúa la sesión del usuario
session_start();

// Incluye la configuración de la base de datos
include '../../config.php';

// Obtener el ID de la liga desde la URL (por ejemplo noticias.php?liga_id=2)
// Si no existe, se asigna 0 para mostrar todas las noticias
$liga_id = isset($_GET['liga_id']) ? (int)$_GET['liga_id'] : 0;

// Inicializar variables de la liga por defecto
$ligaNombre = "Todas las noticias";
$ligaLogo = "";

// Si hay una liga específica seleccionada
if ($liga_id > 0) {
    // Preparar consulta para obtener el nombre y logo de la liga
    $ligaStmt = $mysqli->prepare("SELECT nombre, foto FROM LIGAS WHERE id = ?");
    $ligaStmt->bind_param("i", $liga_id);
    $ligaStmt->execute();
    $ligaResult = $ligaStmt->get_result();

    // Si se encontró la liga, guardar sus datos
    if ($ligaResult->num_rows > 0) {
        $ligaData = $ligaResult->fetch_assoc();
        $ligaNombre = $ligaData['nombre']; // Nombre de la liga
        $ligaLogo = $ligaData['foto'];     // URL del logo o imagen de fondo
    }
    $ligaStmt->close();
}

// Obtener las noticias
if ($liga_id > 0) {
    // Si hay una liga seleccionada, filtrar por esa liga
    $stmt = $mysqli->prepare("SELECT * FROM NOTICIAS WHERE liga_id = ? ORDER BY fecha_publicacion DESC");
    $stmt->bind_param("i", $liga_id);
} else {
    // Si no, obtener todas las noticias
    $stmt = $mysqli->prepare("SELECT * FROM NOTICIAS ORDER BY fecha_publicacion DESC");
}

$stmt->execute();
$result = $stmt->get_result();
$noticias = $result->fetch_all(MYSQLI_ASSOC); // Guardar todas las noticias en un array
$stmt->close();

// Procesar nuevo comentario si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST['contenido']) 
    && isset($_POST['noticia_id']) 
    && isset($_SESSION['user_id'])) {

    $contenido = $_POST['contenido'];       // Texto del comentario
    $noticia_id = $_POST['noticia_id'];     // ID de la noticia comentada
    $usuario_id = $_SESSION['user_id'];     // ID del usuario logueado

    // Preparar consulta para insertar el comentario en la base de datos
    $stmtComInsert = $mysqli->prepare(
        "INSERT INTO COMENTARIOS (noticia_id, usuario_id, contenido, fecha_comentario) 
         VALUES (?, ?, ?, NOW())"
    );

    if ($stmtComInsert) {
        $stmtComInsert->bind_param("iis", $noticia_id, $usuario_id, $contenido);
        $stmtComInsert->execute();
        $stmtComInsert->close();

        // Redirige a la misma página para evitar reenvío de formulario
        header("Location: noticias.php?liga_id=".$liga_id);
        exit();
    } else {
        // Mostrar error si falla la inserción
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

<!-- Bootstrap CSS para estilos modernos -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-white">

<?php if ($ligaLogo): ?>
<!-- Mostrar logo o fondo de la liga si existe -->
<div class="position-fixed w-100 h-100 top-0 start-0">
    <img src="<?php echo htmlspecialchars($ligaLogo); ?>" class="w-100 h-100" style="object-fit: cover;" alt="Fondo liga">
    <!-- Capa oscura encima para que el texto sea legible -->
    <div class="w-100 h-100 position-absolute top-0 start-0 bg-dark bg-opacity-50"></div>
</div>
<?php endif; ?>

<!-- Contenedor principal de noticias -->
<div class="container py-5 position-relative">
    <!-- Nombre de la liga o "Todas las noticias" -->
    <h1 class="mb-4 text-center"><?php echo htmlspecialchars($ligaNombre); ?></h1>

    <?php if (!empty($noticias)): ?>
        <!-- Recorrer todas las noticias -->
        <?php foreach ($noticias as $noticia): ?>
        <div class="col-12 mb-4">
            <div class="card bg-light text-dark shadow-sm">
                <?php if (!empty($noticia['foto'])): ?>
                    <!-- Imagen de la noticia -->
                    <img src="<?php echo htmlspecialchars($noticia['foto']); ?>" 
                         class="card-img-top" 
                         alt="Foto noticia" 
                         style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <!-- Título y subtítulo de la noticia -->
                    <h3 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                    <h5 class="card-subtitle mb-3 text-muted"><?php echo htmlspecialchars($noticia['subtitulo']); ?></h5>
                    <!-- Contenido de la noticia -->
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
                    <p class="text-muted">Publicado el <?php echo date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])); ?></p>

                    <!-- Mostrar comentarios -->
                    <div class="mt-3">
                        <h6>Comentarios:</h6>
                        <?php
                        // Preparar consulta para obtener comentarios de esta noticia junto con el nombre del usuario
                        $stmtCom = $mysqli->prepare("SELECT C.contenido, C.fecha_comentario, U.nombre_usuario 
                                                     FROM COMENTARIOS C 
                                                     JOIN USUARIOS U ON C.usuario_id = U.id 
                                                     WHERE C.noticia_id = ? 
                                                     ORDER BY C.fecha_comentario ASC");
                        $comentarios = []; // Inicializar array de comentarios
                        if ($stmtCom) {
                            $stmtCom->bind_param("i", $noticia['id']); // Asignar ID de la noticia
                            $stmtCom->execute();
                            $resultCom = $stmtCom->get_result();
                            $comentarios = $resultCom->fetch_all(MYSQLI_ASSOC); // Guardar todos los comentarios en un array
                            $stmtCom->close();
                        }

                        // Mostrar comentarios si hay
                        if (!empty($comentarios)):
                            foreach ($comentarios as $com):
                        ?>
                        <p>
                            <strong><?php echo htmlspecialchars($com['nombre_usuario']); ?>:</strong> 
                            <?php echo nl2br(htmlspecialchars($com['contenido'])); ?> 
                            <small class="text-muted">(<?php echo date('d/m/Y H:i', strtotime($com['fecha_comentario'])); ?>)</small>
                        </p>
                        <?php
                            endforeach;
                        else:
                            echo "<p class='text-muted'>No hay comentarios aún.</p>";
                        endif;
                        ?>

                        <!-- Formulario para agregar nuevo comentario si el usuario está logueado -->
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
        <!-- Mensaje si no hay noticias -->
        <p class="text-center">No hay noticias disponibles para esta liga.</p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS para componentes interactivos -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>