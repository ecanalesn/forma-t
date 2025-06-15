<?php
// Se inicia la sesión
session_start();  

// Se incluye la clase User
require_once '../models/User.php';

// Se inicia la sesión si el formulario es enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Se comprueba que el correo electrónico tenga el formato correcto
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Si el correo electrónico no es válido, se redirige con un mensaje de error
        header("Location: /forma-t/public/pages/login.php?error=invalid-email-format");
        exit;  
    }

    // Se instacia un objeto User para poder iniciar sesión
    $user = new User();
    $userLogged = $user->login($email, $password);

    // Se comprueba si el usuario existe y si la contraseña es correcta
    if ($userLogged) { 
        // Si la contraseña es correcta, se redirige al dashboard 
        $_SESSION['user_id'] = $userLogged['user_id'];  
        $_SESSION['role'] = $userLogged['role']; 
        $_SESSION['user_name'] = $userLogged['first_name'];  
        header("Location: /forma-t/public/pages/dashboard.php");
        exit;  
    } else {
        // Si la autenticación falla, se redirige a la página de login con un parámetro de error
        header("Location: /forma-t/public/pages/login.php?error=incorrect-credentials");
        exit;  
    }
}
?>


