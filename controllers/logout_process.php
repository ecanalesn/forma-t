<?php
// Se inicia la sesión
session_start();  

// Se cierra la sesión
session_unset();  
session_destroy();  

// Se redirige al login
header("Location: /forma-t/public/pages/login.php");
exit;  

?>