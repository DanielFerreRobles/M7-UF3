<?php
include '../../config.php'; 

$id = $_GET['id']; 

$sql = "SELECT * FROM TESTIMONIALS WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$testimonial = $result->fetch_assoc(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $description = $_POST['description'];
    $imatge = $_POST['imatge'];
    $data = $_POST['data'];

    $updateSql = "UPDATE TESTIMONIALS SET name = ?, surname = ?, description = ?, imatge = ?, data = ? WHERE id = ?";
    $updateConsulta = $mysqli->prepare($updateSql);

    $updateConsulta->bind_param("sssssi", $name, $surname, $description, $imatge, $data, $id);

    if ($updateConsulta->execute()) {
        echo '<div class="alert alert-success">¡Testimonio actualizado con éxito!</div>';
    } else {
        echo '<div class="alert alert-danger">Error al actualizar el testimonio: ' . $updateConsulta->error . '</div>';
    }

    $updateConsulta->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Testimonio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1>Editar Testimonio</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $testimonial['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="surname" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $testimonial['surname']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="6" required><?php echo $testimonial['description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="imatge" class="form-label">Foto URL</label>
                <input type="url" class="form-control" id="imatge" name="imatge" value="<?php echo $testimonial['imatge']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="data" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="data" name="data" value="<?php echo $testimonial['data']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>