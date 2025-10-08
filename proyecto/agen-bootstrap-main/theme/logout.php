<?php
// Inicia la sesión actual
session_start(); 

// Elimina todas las variables de sesión
session_unset(); 

// Destruye completamente la sesión
session_destroy();

// Redirige al usuario a la página de login.php
header('Location: login.php');

// Detiene el script para que no se ejecute nada más después de la redirección
exit;
?>