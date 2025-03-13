<?php
session_start();
include '../../config.php';

$id = $_GET['id']; 
$stmt = $mysqli->prepare("SELECT * FROM clasificacion WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

$team = $result->fetch_assoc(); 
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posicion = $_POST['posicion'];
    $pts = $_POST['pts'];
    $pj = $_POST['pj'];

    $updateStmt = $mysqli->prepare("UPDATE clasificacion SET posicion = ?, pts = ?, pj = ? WHERE id = ?");
    $updateStmt->bind_param('iiii', $posicion, $pts, $pj, $id);

    if ($updateStmt->execute()) {
        echo '<div class="alert alert-success">¡Equipo actualizado correctamente!</div>';
    } else {
        echo '<div class="alert alert-danger">Error al actualizar el equipo: ' . $updateStmt->error . '</div>';
    }

    $updateStmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Clasificación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Editar Clasificación</h2>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Posición:</label>
                        <input type="number" name="posicion" class="form-control" value="<?php echo htmlspecialchars($team['posicion']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Puntos:</label>
                        <input type="number" name="pts" class="form-control" value="<?php echo htmlspecialchars($team['pts']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Partidos Jugados:</label>
                        <input type="number" name="pj" class="form-control" value="<?php echo htmlspecialchars($team['pj']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Clasificación</button>
                </form>
            </div>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Volver a la lista de equipos</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>