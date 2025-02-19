<?php
// Conexión a la base de datos

$mysqli = new mysqli("localhost", "username", "password", "database");

if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_error .')');
}else{
    echo "Conexión exitosa";
}