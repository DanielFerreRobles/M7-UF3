<?php
session_start();
include '../../config.php';

$id = $_GET['id'];

$deleteSql = "DELETE FROM NEWS WHERE id = ?";

$stmt = $mysqli->prepare($deleteSql);

if (!$stmt) {
    die('Error en la preparación: ' . $mysqli->error);
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo 'Noticia eliminada correctamente!';
} else {
    echo 'Error al eliminar la noticia: ' . $stmt->error;
}

$stmt->close();
$mysqli->close();

?>