<?php
// Datos para conectar con la base de datos
$dbhost     = 'mysql-aprende-con-ferrer.alwaysdata.net';  // Servidor
$dbname     = 'aprende-con-ferrer_proyectom12';           // Nombre de la base de datos
$dbuser     = '397287';                                   // Usuario
$dbpassword = 'maluc656';                                 // Contraseña

// Conectamos con la base de datos
$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

// Si hay un error al conectar, se muestra y se detiene el programa
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

// Ponemos el juego de caracteres en UTF-8 para que se vean bien las tildes y ñ
$mysqli->set_charset("utf8");
?>