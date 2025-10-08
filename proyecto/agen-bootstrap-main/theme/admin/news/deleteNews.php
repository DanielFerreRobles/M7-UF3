<?php
session_start();
include '../../config.php'; // Configuración de la base de datos

// Si NO recibimos el id de la noticia que queremos eliminar, NO podremos eliminarla
if (!isset($_GET['id'])) {
    header("Location: addNew.php");
    exit;
}

//Si recibimos correctamente el id de la noticia que queremos eliminar, SI podremos eliminarla. Guardamos el id en la variable "noticia_id"
$noticia_id = $_GET['id'];

// Ejecutar eliminación
$stmt = $mysqli->prepare("DELETE FROM NOTICIAS WHERE id=?");
$stmt->bind_param("i", $noticia_id);
$stmt->execute();

// Redirigir de vuelta al listado de noticias o para añadir nuevas
header("Location: addNew.php");
exit;
?>