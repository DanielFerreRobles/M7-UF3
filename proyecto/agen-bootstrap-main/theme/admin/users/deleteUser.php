<?php
session_start();
include '../../config.php';  

$id = $_GET['id'];

$deleteSql = "DELETE FROM users WHERE id = ?";

$stmt = $mysqli->prepare($deleteSql);

if (!$stmt) {
    die('Error en la preparación: ' . $mysqli->error);
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo 'Usuario eliminado correctamente';
    exit();
} else {
    echo 'Error al eliminar el usuario: ' . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>