<?php
session_start();
include '../../config.php';

$result = $mysqli->query("SELECT * FROM TESTIMONIALS ORDER BY id DESC");
$arrayTestimonials = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $description = $_POST['description'];
    $img = $_POST['photo'];
    $date = date('Y-m-d');

    $stmt = $mysqli->prepare(
        "INSERT INTO TESTIMONIALS (name, surname, description, imatge, data) 
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('sssss', $name, $surname, $description, $img, $date);

    if ($stmt->execute()) {
         echo'Testimonio añadido correctamente!';
         exit();
    } else {
        echo 'Error al añadir testimonio: ' . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Testimonio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Añadir Testimonio</h1>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="surname" class="form-label">Apellido:</label>
                <input type="text" id="surname" name="surname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción:</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Foto de perfil (URL):</label>
                <input type="text" id="photo" name="photo" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Añadir Testimonio</button>
        </form>

        <h2 class="mt-4">Testimonios Añadidos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Testimonio</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrayTestimonials as $testimonial) { ?>
                <tr>
                    <td><?php echo $testimonial['id']; ?></td>
                    <td><?php echo $testimonial['name']; ?></td>
                    <td><?php echo $testimonial['surname']; ?></td>
                    <td><?php echo $testimonial['description']; ?></td>
                    <td><img src="<?php echo $testimonial['imatge']; ?>" alt="img" width="100"></td>
                    <td>
                        <a href="admin/testimonials/editTestimonials.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="deleteTestimonials.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-danger btn-sm">
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