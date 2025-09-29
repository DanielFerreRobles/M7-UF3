<?php
// Conexión a la base de datos
$dbhost = 'mysql-aprende-con-ferrer.alwaysdata.net';
$dbname = 'aprende-con-ferrer_proyectom12';
$dbuser = '397287';
$dbpassword = 'maluc656';




$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_error .')');
}else{
}