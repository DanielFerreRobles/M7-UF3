<?php
session_start();
include '../../config.php'; // Conexión a mi base de datos

// Si NO recibimos el id del usuario que queremos eliminar, NO podremos eliminarlo
if (!isset($_GET['id'])) {
    header("Location: addUser.php");
    exit;
}
//Si recibimos correctamente el id del usuario que queremos eliminar, SI podremos eliminarlo. Guardamos el id en la variable "usuario_id"
$usuario_id = $_GET['id'];

// Evitar que un admin se elimine a sí mismo
if ($usuario_id == $_SESSION['usuario_id']) {
    header("Location: addUser.php");
    exit;
}

// Ejecutar eliminación
$stmt = $mysqli->prepare("DELETE FROM USUARIOS WHERE id=?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

// Redirigir de vuelta al listado de usuarios o para añadir nuevos
header("Location: addUser.php");
exit;
?>