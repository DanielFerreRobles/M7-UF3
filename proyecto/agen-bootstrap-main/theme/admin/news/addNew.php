<?php
session_start();
include '../../config.php';

$result = $mysqli->query("SELECT * FROM news ORDER BY id DESC");
$arrayNews = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $description = $_POST['description'];
    $new_date = $_POST['new_date'];
    $photo = $_POST['photo'];

    $stmt = $mysqli->prepare("INSERT INTO news (title, subtitle, description, new_date, photo) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('sssss', $title, $subtitle, $description, $new_date, $photo);

    if ($stmt->execute()) {
        echo 'Noticia añadida correctamente!';
        exit();
    } else {
        echo 'Error al agregar noticia: ' . $stmt->error;
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
    <title>Añadir Noticia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Añadir Noticia</h2>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtítulo:</label>
                        <input type="text" name="subtitle" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción:</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha:</label>
                        <input type="date" name="new_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL de la Imagen:</label>
                        <input type="text" name="photo" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Añadir Noticia</button>
                </form>
            </div>
        </div>

        <h2 class="mt-4">Noticias Añadidas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrayNews as $new) { ?>
                <tr>
                    <td><?php echo $new['id']; ?></td>
                    <td><?php echo $new['title']; ?></td>
                    <td><?php echo $new['subtitle']; ?></td>
                    <td><?php echo $new['description']; ?></td>
                    <td><?php echo $new['new_date']; ?></td>
                    <td><img src="<?php echo $new['photo']; ?>" alt="img" width="100"></td>
                    <td>
                        <a href="editNews.php?id=<?php echo $new['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="deleteNews.php?id=<?php echo $new['id']; ?>" class="btn btn-danger btn-sm">
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