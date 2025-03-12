<?php
session_start();
include '../../config.php';

$result = $mysqli->query("SELECT * FROM PROJECTS ORDER BY id DESC");
$arrayProjects = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $categoria = $_POST['categoria'];

    $stmt = $mysqli->prepare("INSERT INTO PROJECTS (title, description, url, categoria) VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('ssss', $title, $description, $url, $categoria);

    if ($stmt->execute()) {
        echo'Proyecto añadido correctamente!';
        exit();
   } else {
       echo 'Error al añadir proyecto: ' . $stmt->error;
   }

    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Añadir Proyecto</h2>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción:</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL del Proyecto:</label>
                        <input type="text" name="url" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoría:</label>
                        <input type="text" name="categoria" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Añadir Proyecto</button>
                </form>
            </div>
        </div>

        <h2 class="mt-4">Proyectos Añadidos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>URL</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrayProjects as $project) { ?>
                <tr>
                    <td><?php echo $project['id']; ?></td>
                    <td><?php echo $project['title']; ?></td>
                    <td><?php echo $project['description']; ?></td>
                    <td><a href="<?php echo $project['url']; ?>" target="_blank">Ver Proyecto</a></td>
                    <td><?php echo $project['categoria']; ?></td>
                    <td>s
                        <a href="editProject.php?id=<?php echo $project['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="deleteProjects.php?id=<?php echo $project['id']; ?>" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>