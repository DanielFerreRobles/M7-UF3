<?php
session_start();

include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Usuario encontrado, redirigir a index.php
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>