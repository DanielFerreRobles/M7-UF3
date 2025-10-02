<?php
// Inicia o continúa la sesión del usuario
// Necesario si queremos validar permisos u obtener datos del usuario logueado
session_start();

// Incluye la configuración de la base de datos
// Aquí se define la conexión $mysqli
include '../../config.php';  

// Obtener el ID del usuario que se quiere eliminar desde la URL (por ejemplo deleteUser.php?id=3)
$id = $_GET['id'];

// Preparar la consulta SQL para eliminar un usuario específico por su ID
$deleteSql = "DELETE FROM USUARIOS WHERE id = ?";

// Preparar la sentencia usando la conexión mysqli
// Esto evita inyecciones SQL y permite usar parámetros seguros
$stmt = $mysqli->prepare($deleteSql);

// Comprobar si la preparación de la sentencia falló
if (!$stmt) {
    die('Error en la preparación: ' . $mysqli->error); // Muestra error y detiene la ejecución
}

// Asociar el parámetro "id" con la sentencia preparada
// "i" indica que es un entero
$stmt->bind_param("i", $id);

// Ejecutar la sentencia SQL
if ($stmt->execute()) {
    // Si la ejecución fue correcta, mostrar mensaje de éxito
    echo 'Usuario eliminado correctamente';
    exit(); // Termina la ejecución del script
} else {
    // Si hubo un error al ejecutar la consulta, mostrar mensaje con el error
    echo 'Error al eliminar el usuario: ' . $stmt->error;
}

// Cerrar la sentencia preparada
$stmt->close();

// Cerrar la conexión a la base de datos
$mysqli->close();
?>