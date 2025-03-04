<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">🔧 Panel de Administración</h2>
        <div class="d-flex justify-content-center">
            <div class="col-md-6 text-center">
                <a href="admin/users/addUser.php" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-person-plus"></i> Añadir Usuario
                </a>
                <a href="admin/news/addNew.php" class="btn btn-secondary w-100 mb-2">
                    <i class="bi bi-newspaper"></i> Añadir Noticia
                </a>
                <a href="admin/projects/addProjects.php" class="btn btn-success w-100 mb-2">
                    <i class="bi bi-briefcase"></i> Añadir Proyecto
                </a>
                <a href="admin/testimonials/addTestimonials.php" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-chat-left-quote"></i> Añadir Testimonio
                </a>
            </div>
        </div>
    </div>
</body>
</html>