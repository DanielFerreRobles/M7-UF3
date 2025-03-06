<?php
include '../../config.php';

$titulo = $_POST['titulo'];
$subtitulo = $_POST['subtitulo'];
$contenido = $_POST['contenido'];
$fecha = $_POST['fecha'];
$imagen = $_POST['imagen'];

$sql = "UPDATE noticias SET titulo='$titulo', subtitulo='$subtitulo', contenido='$contenido', fecha='$fecha', imagen='$imagen' WHERE id=1"; // Reemplaza id=1 con el id correcto

if ($conexion->query($sql) === TRUE) {
    echo "Noticia actualizada exitosamente.";
} else {
    echo "Error al actualizar la noticia: " . $conexion->error;
}

$conexion->close();
?>