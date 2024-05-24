<?php
// Iniciar la sesión
session_start();

// Destruir la sesión
session_destroy();

// Redireccionar a la página de inicio
header("location: bienvenido.php");
exit;
?>