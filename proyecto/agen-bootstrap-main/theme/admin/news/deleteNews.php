<?php
session_start(); // Iniciar sesión
include '../../config.php'; // Conexión a la base de datos

// ================================
// 1️⃣ Obtener el ID de la noticia desde la URL
// ================================
$id = $_GET['id'];

// ================================
// 2️⃣ Preparar la sentencia SQL para eliminar la noticia
// ================================
$deleteSql = "DELETE FROM NOTICIAS WHERE id = ?";
$stmt = $mysqli->prepare($deleteSql);

// Comprobar si la preparación fue correcta
if (!$stmt) {
    die('Error en la preparación: ' . $mysqli->error);
}

// ================================
// 3️⃣ Vincular el parámetro (id de la noticia)
// ================================
$stmt->bind_param("i", $id);

// ================================
// 4️⃣ Ejecutar la sentencia DELETE
// ================================
if ($stmt->execute()) {
    // Si todo bien, mostrar mensaje y salir
    echo '<div class="alert alert-success text-center mt-3">✅ Noticia eliminada correctamente!</div>';
    exit();
} else {
    // Si hubo error al eliminar, mostrar mensaje
    echo '<div class="alert alert-danger text-center mt-3">❌ Error al eliminar la noticia: ' . $stmt->error . '</div>';
}

// ================================
// 5️⃣ Cerrar la sentencia y la conexión
// ================================
$stmt->close();
$mysqli->close();
?>