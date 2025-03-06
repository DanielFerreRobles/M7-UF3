<?php
session_start();
include '../../config.php';  

$id = $_GET['id'];

$deleteSql = "DELETE FROM PROJECTS WHERE id = ?";

$stmt = $mysqli->prepare($deleteSql);

if (!$stmt) {
    die('Error en la preparación: ' . $mysqli->error);
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
echo 'Proyecto eliminado correctamente!';
exit();
} else {
    echo 'Error al eliminar el proyecto: ' . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>