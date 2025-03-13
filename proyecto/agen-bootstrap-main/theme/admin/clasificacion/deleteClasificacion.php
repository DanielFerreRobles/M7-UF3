<?php
session_start();
include '../../config.php';  

// Obtener el id del equipo a eliminar
$id = $_GET['id'];

// Sentencia SQL para eliminar el equipo de la clasificación
$deleteSql = "DELETE FROM clasificacion WHERE id = ?";

// Preparar la consulta
$stmt = $mysqli->prepare($deleteSql);

// Verificar si la preparación fue exitosa
if (!$stmt) {
    die('Error en la preparación: ' . $mysqli->error);
}

// Vincular el parámetro para la consulta
$stmt->bind_param("i", $id);

// Ejecutar la sentencia
if ($stmt->execute()) {
    echo 'Equipo eliminado correctamente!';
    exit();
} else {
    echo 'Error al eliminar el equipo: ' . $stmt->error;
}

// Cerrar la sentencia y la conexión
$stmt->close();
$mysqli->close();
?>