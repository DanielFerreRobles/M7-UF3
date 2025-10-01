<?php
// Inicia la sesión actual para poder manipularla
session_start(); 

// Borra todas las variables de sesión (como usuario, id, etc.)
session_unset(); 

// Destruye completamente la sesión (la elimina del servidor)
session_destroy();

// Redirige al usuario a la página de login (inicio de sesión)
header('Location: login.php');

// Termina el script para que no se ejecute nada más después
exit;
?>