<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRO</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $passwd = $_POST['passwd'];
        $username = $_POST['username'];
        $email = $_POST['email'];
    }
    ?>

    <form method="post">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="passwd">Contraseña:</label>
        <input type="password" id="passwd" name="passwd" required><br><br>
        
        <input type="submit" value="Enviar">
    </form>
</body>
</html>