<?php
session_start();
include '../../config.php'; // Configuración de la base de datos

// Verificar que se haya pasado el id de la noticia
if (!isset($_GET['id'])) {
    header("Location: addNew.php");
    exit;
}

$noticia_id = $_GET['id'];

// Ejecutar eliminación
$stmt = $mysqli->prepare("DELETE FROM NOTICIAS WHERE id=?");
$stmt->bind_param("i", $noticia_id);
$stmt->execute();
$stmt->close();

// Redirigir de vuelta al listado de noticias
header("Location: addNew.php");
exit;
?>