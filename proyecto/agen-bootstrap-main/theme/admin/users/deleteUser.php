<?php
session_start();
include '../../config.php'; // Conexión a la base de datos

// ================================
// 1️⃣ Obtener ID del usuario a eliminar
// ================================
if (!isset($_GET['id'])) {
    die("Error: no se ha especificado un ID de usuario.");
}
$id = intval($_GET['id']); // Convertir a entero por seguridad

// ================================
// 2️⃣ Preparar la consulta DELETE
// ================================
$deleteSql = "DELETE FROM USUARIOS WHERE id = ?";
$stmt = $mysqli->prepare($deleteSql);

if (!$stmt) {
    die('Error en la preparación de la consulta: ' . $mysqli->error);
}

// Vincular parámetro (id del usuario)
$stmt->bind_param("i", $id);

// ================================
// 3️⃣ Ejecutar la eliminación
// ================================
if ($stmt->execute()) {
    // Éxito: mostrar mensaje y opcionalmente redirigir
    echo '<div class="alert alert-success text-center mt-3">✅ Usuario eliminado correctamente</div>';
} else {
    // Error al eliminar
    echo '<div class="alert alert-danger text-center mt-3">❌ Error al eliminar el usuario: ' . $stmt->error . '</div>';
}

// ================================
// 4️⃣ Cerrar statement y conexión
// ================================
$stmt->close();
$mysqli->close();
?>