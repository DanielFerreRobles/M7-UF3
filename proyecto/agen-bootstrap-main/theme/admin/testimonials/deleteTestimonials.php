<?php
session_start();
include '../../config.php'; 


    $id = $_GET['id'];

    $deleteSql = "DELETE FROM TESTIMONIALS WHERE id = ?";

    $stmt = $mysqli->prepare($deleteSql);
    
    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Testimonio eliminado correctamente!';
    } else {
        $_SESSION['error'] = 'Error al eliminar el testimonio: ' . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    exit();

?>