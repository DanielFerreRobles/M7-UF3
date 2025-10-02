<?php
// Inicia sesión
session_start();

// Incluye configuración de base de datos
include '../../config.php';

// ===============================
// 1. OBTENER EL ID DE LA NOTICIA
// ===============================
// Se obtiene de la URL mediante GET
$id = $_GET['id'];

// ===============================
// 2. PREPARAR LA CONSULTA DE ELIMINACIÓN
// ===============================
$deleteSql = "DELETE FROM NOTICIAS WHERE id = ?";
$stmt = $mysqli->prepare($deleteSql);

// Si falla la preparación, mostrar error
if (!$stmt) {
    die('Error en la preparación: ' . $mysqli->error);
}

// ===============================
// 3. VINCULAR EL PARÁMETRO Y EJECUTAR
// ===============================
$stmt->bind_param("i", $id); // "i" indica que es un entero

if ($stmt->execute()) {
    // Si se ejecuta correctamente
    echo 'Noticia eliminada correctamente!';
    exit(); // Termina la ejecución
} else {
    // Si hay error al ejecutar
    echo 'Error al eliminar la noticia: ' . $stmt->error;
}

// ===============================
// 4. CERRAR SENTENCIA Y CONEXIÓN
// ===============================
$stmt->close();
$mysqli->close();
?>