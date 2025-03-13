<?php
session_start();
include '../../config.php';

// Obtener la clasificación desde la base de datos
$result = $mysqli->query("SELECT * FROM clasificacion ORDER BY posicion ASC");
$arrayClasificacion = $result->fetch_all(MYSQLI_ASSOC);

// Insertar nuevo equipo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo = $_POST['equipo'];
    $posicion = $_POST['posicion'];
    $pts = $_POST['pts'];
    $pj = $_POST['pj'];

    $stmt = $mysqli->prepare("INSERT INTO clasificacion (equipo, posicion, pts, pj) VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        die('Error en la preparación: ' . $mysqli->error);
    }

    $stmt->bind_param('ssss', $equipo, $posicion, $pts, $pj);

    if ($stmt->execute()) {
        echo 'Equipo añadido correctamente!';
        exit();
    } else {
        echo 'Error al añadir equipo: ' . $stmt->error;
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
    <title>Añadir Equipo a la Clasificación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Añadir Equipo a la Clasificación</h2>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Equipo:</label>
                        <input type="text" name="equipo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Posición:</label>
                        <input type="number" name="posicion" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Puntos:</label>
                        <input type="number" name="pts" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Partidos Jugados:</label>
                        <input type="number" name="pj" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Añadir Equipo</button>
                </form>
            </div>
        </div>

        <h2 class="mt-4">Clasificación Actual</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipo</th>
                    <th>Posición</th>
                    <th>Puntos</th>
                    <th>Partidos Jugados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrayClasificacion as $equipo) { ?>
                <tr>
                    <td><?php echo $equipo['id']; ?></td>
                    <td><?php echo $equipo['equipo']; ?></td>
                    <td><?php echo $equipo['posicion']; ?></td>
                    <td><?php echo $equipo['pts']; ?></td>
                    <td><?php echo $equipo['pj']; ?></td>
                    <td>
                        <a href="editClasificacion.php?id=<?php echo $equipo['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="deleteClasificacion.php?id=<?php echo $equipo['id']; ?>" class="btn btn-danger btn-sm">
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