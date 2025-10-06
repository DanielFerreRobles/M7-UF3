<?php
// ================================
// Configuración de conexión a la base de datos
// ================================
$dbhost     = 'mysql-aprende-con-ferrer.alwaysdata.net';                // Host del servidor MySQL
$dbname     = 'aprende-con-ferrer_proyectom12';            // Nombre de la base de datos
$dbuser     = '397287';                                     // Usuario de la base de datos
$dbpassword = 'maluc656';                                   // Contraseña de la base de datos

// ================================
// Crear conexión usando mysqli
// ================================
$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

// ================================
// Verificar errores de conexión
// ================================
if ($mysqli->connect_error) {
    die('Error de conexión (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
}

// ================================
// Establecer codificación a UTF-8
// Esto asegura que acentos y caracteres especiales se manejen correctamente
// ================================
$mysqli->set_charset("utf8");
?>