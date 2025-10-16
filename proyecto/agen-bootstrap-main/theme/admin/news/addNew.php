<?php
session_start();
include '../../config.php'; // Conexión a mi base de datos

//Obtenemos el id y el nombre de las ligas
$result = $mysqli->query("SELECT id, nombre FROM LIGAS");

//Si obtenemos resultados del SELECT, lo convertimos en un array y lo guardamos en la variable "ligas", sino, el array estará vacío
$ligas = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Obtenemos las noticias existentes
$resultNoticias = $mysqli->query("SELECT * FROM NOTICIAS ORDER BY id DESC");

//Si obtenemos resultados del SELECT, lo convertimos en un array y lo guardamos en la variable "noticias", sino, el array estará vacío
$noticias = $resultNoticias ? $resultNoticias->fetch_all(MYSQLI_ASSOC) : [];

//Si los datos han sido correctamente recibidos por POST, los guardamos en variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $foto = $_POST['foto'];
    $subtitulo = $_POST['subtitulo'];
    $contenido = $_POST['contenido'];
    $liga_id = $_POST['liga_id'];
    $jornada = $_POST['jornada'];
    $competicion = $_POST['competicion'] ?? '';
    $fecha_publicacion = $_POST['fecha_publicacion'] ?: date('Y-m-d H:i:s');

    // Inserto los datos recibidos del formulario en mi tabla "NOTICIAS"
    $stmt = $mysqli->prepare("INSERT INTO NOTICIAS (titulo, foto, subtitulo, contenido, liga_id, competicion, fecha_publicacion, jornada) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Si la consulta se preparó bien
    if ($stmt) {
        // Inserto la info de mis variables en la consulta
        $stmt->bind_param("sssssssi", $titulo, $foto, $subtitulo, $contenido, $liga_id, $competicion, $fecha_publicacion, $jornada);
        
        // Ejecuto la consulta
        if ($stmt->execute()) {
            // Si todo va bien, recargo la página
            header("Location: addNew.php");
            exit;
        } else {
            // Si hay error al ejecutar, lo muestro
            echo "Error al insertar la noticia.";
        }
        $stmt->close(); // Cierro la consulta
    } else {
        // Si falla la preparación de la consulta, muestro error
        echo "Error en la consulta.";
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
<a href="../../index.php" class="btn btn-secondary mb-4">← Volver al inicio</a>

<!--Enviamos por POST la info del formulario. Estos datos se reciben arriba y hacemos el INSERT INTO !-->
<form method="POST" class="mb-5">
    <input type="text" name="titulo" class="form-control mb-2" placeholder="Título" required>
    <input type="text" name="foto" class="form-control mb-2" placeholder="URL de la imagen" required>
    <input type="text" name="subtitulo" class="form-control mb-2" placeholder="Subtítulo" required>
    <textarea name="contenido" class="form-control mb-2" placeholder="Contenido" rows="4" required></textarea>
    
    <select name="liga_id" class="form-select mb-2" required>
        <option value="">Selecciona una liga</option>
        <?php foreach ($ligas as $liga): ?>
            <option value="<?php echo $liga['id']; ?>"><?php echo $liga['nombre']; ?></option>
        <?php endforeach; ?>
    </select>

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

<!--Mostramos las noticias existentes por pantalla-->
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
            <td><?php echo $noticia['titulo']; ?></td>
            <td><img src="<?php echo $noticia['foto']; ?>" width="80"></td>
            <td><?php echo $noticia['subtitulo']; ?></td>
            <td><?php echo $noticia['contenido']; ?></td>
            <td><?php echo $noticia['competicion']; ?></td>
            <td><?php echo $noticia['liga_id']; ?></td>
            <td><?php echo $noticia['jornada']; ?></td>
            <td><?php echo $noticia['fecha_publicacion']; ?></td>
            <td>

                <!--Si clicamos aquí editamos la noticia-->
                <a href="editNews.php?id=<?php echo $noticia['id']; ?>" class="btn btn-sm btn-warning">Editar</a>

                <!--Si clicamos aquí eliminamos la noticia-->
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