<?php
session_start();
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
        <h1 class="text-center mb-4">🔧 Panel de Administración</h1>
        <div class="d-flex justify-content-center gap-3 admin-testimonials">

        <a href="admin/users/addUser.php" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-person-plus"></i> Gestionar Usuarios
                </a>
                <a href="admin/news/addNew.php" class="btn btn-secondary w-100 mb-2">
                    <i class="bi bi-newspaper"></i> Gestionar Noticias
                </a>       
                <a href="admin/clasificacion/addClasificacion.php" class="btn btn-secondary w-100 mb-2">
                    <i class="bi bi-newspaper"></i> Gestionar clasificación
                </a>                  
        </div>
    </div>
</body>
</html>







