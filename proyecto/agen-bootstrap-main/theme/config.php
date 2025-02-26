<?php
// Conexión a la base de datos
$dbhost = 'mysql-ferrer.alwaysdata.net';
$dbname = 'ferrer_proyecto_uf3';
$dbuser = 'ferrer';
$dbpassword = 'maluc656';




$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_error .')');
}else{
}