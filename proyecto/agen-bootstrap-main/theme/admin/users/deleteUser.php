<?php
session_start();
include '../../config.php'; // Conexión a MySQL usando $mysqli

// Verificar que se pase el id del usuario
if (!isset($_GET['id'])) {
    header("Location: addUser.php");
    exit;
}

$usuario_id = $_GET['id'];

// Evitar que un admin se borre a sí mismo
if ($usuario_id == $_SESSION['usuario_id']) {
    header("Location: addUser.php");
    exit;
}

// Ejecutar eliminación
$stmt = $mysqli->prepare("DELETE FROM USUARIOS WHERE id=?");
$stmt->bind_param("i", $usuario_id);

$stmt->execute();

// Redirigir de vuelta al listado
header("Location: addUser.php");
exit;
?>