<?php
// Inicia o continúa la sesión
// Esto permite acceder a información de la sesión, como si el usuario está logueado o su rol
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>

    <!-- Bootstrap CSS para estilos modernos y responsivos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons para usar iconos en los botones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <!-- Contenedor principal centrado -->
    <div class="container mt-5">
        <!-- Título del panel -->
        <h1 class="text-center mb-4">🔧 Panel de Administración</h1>

        <!-- Contenedor de botones del panel con separación entre ellos -->
        <div class="d-flex justify-content-center gap-3 admin-testimonials">

            <!-- Botón para gestionar usuarios -->
            <a href="admin/users/addUser.php" class="btn btn-primary w-100 mb-2">
                <!-- Icono de añadir usuario -->
                <i class="bi bi-person-plus"></i> Gestionar Usuarios
            </a>

            <!-- Botón para gestionar noticias -->
            <a href="admin/news/addNew.php" class="btn btn-secondary w-100 mb-2">
                <!-- Icono de periódico -->
                <i class="bi bi-newspaper"></i> Gestionar Noticias
            </a>      

        </div>
    </div>

</body>
</html>