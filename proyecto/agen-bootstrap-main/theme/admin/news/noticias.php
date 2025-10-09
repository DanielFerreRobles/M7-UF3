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

//Juntando las tablas "NOTICIAS" y "USUARIOS",para obtener todas las noticias de la liga y jornada seleccionadas junto con el nombre del usuario que escribió cada noticia
$stmtNoticias = $mysqli->prepare("SELECT NOTICIAS.id, NOTICIAS.titulo, NOTICIAS.subtitulo, NOTICIAS.contenido, NOTICIAS.foto, NOTICIAS.fecha_publicacion, USUARIOS.nombre_usuario 
    FROM NOTICIAS JOIN USUARIOS
    ON NOTICIAS.user_id = USUARIOS.id 
    WHERE NOTICIAS.liga_id = ? AND NOTICIAS.jornada = ? 
    ORDER BY NOTICIAS.id DESC");
$stmtNoticias->bind_param("ii", $liga_id, $jornada);
$stmtNoticias->execute();
$resultNoticias = $stmtNoticias->get_result();
$noticias = $resultNoticias->fetch_all(MYSQLI_ASSOC);
$stmtNoticias->close();

 // Si no hay noticias, ponemos array vacío
if (!$noticias) {
    $noticias = [];
}
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

<!-- Fondo de la liga -->
<div class="position-relative vh-100" 
     style="background-image: url('<?php echo $liga['foto']; ?>'); background-size: cover; background-position: center;">
    
    <!-- Capa oscura encima del fondo -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>

    <div class="container position-relative py-5 text-white" 
         style="z-index: 1; overflow-y: auto; max-height: 100vh;">

        <!-- Nombre de la liga -->
        <h1 class="text-center mb-4"><?php echo $liga['nombre']; ?></h1>

        <!-- Seleccionamos jornada -->
        <form method="POST" class="mb-4 d-flex align-items-center gap-2">
            <input type="hidden" name="liga_id" value="<?php echo $liga_id; ?>">
            <label for="jornada" class="form-label mb-0">Jornada:</label>
            <select name="jornada" id="jornada" class="form-select w-auto">
                <?php for ($i = 1; $i <= 38; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if ($i == $jornada) echo 'selected'; ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        </form>

        <!--Mostramos las noticias -->
        <?php foreach ($noticias as $noticia): ?>
            <div class="card mb-4">

                <!-- Imagen de la noticia -->
                <?php if ($noticia['foto']): ?>
                    <img src="<?php echo $noticia['foto']; ?>" class="img-fluid rounded" alt="Imagen noticia">
                <?php endif; ?>

                <div class="card-body text-dark">
                    <h3><?php echo $noticia['titulo']; ?></h3>
                    <h5 class="text-muted"><?php echo $noticia['subtitulo']; ?></h5>
                    <p><?php echo $noticia['contenido']; ?></p>
                    <p class="text-muted small">
                        Publicado por <?php echo $noticia['nombre_usuario']; ?>
                        el <?php echo $noticia['fecha_publicacion']; ?>
                    </p>

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

                    <!-- Mostramos los comentarios, quien los ha escrito y cuando -->
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="border p-2 mb-2 rounded bg-light text-dark">
                            <strong><?php echo $comentario['nombre_usuario']; ?></strong>
                            <span class="text-muted small"> - <?php echo $comentario['fecha_comentario']; ?></span>
                            <p class="mb-0"><?php echo $comentario['contenido']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Si no hay noticias mostramos esta frase -->
        <?php if (empty($noticias)): ?>
            <div class="alert alert-info text-dark">No hay noticias disponibles.</div>
        <?php endif; ?>

        <!-- Por si nos equivocamos, clicando aqií volveremos a la página prinicipal para seleccionar una liga -->
        <a href="../../index.php" class="btn btn-secondary">Volver</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>