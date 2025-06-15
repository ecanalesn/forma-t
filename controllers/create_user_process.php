<?php
// Se inicia la sesión y se comprueba si el usuario está autenticado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /forma-t/public/pages/login.php");
    exit;
}

// Se incluye la clase User
require_once '../models/User.php';

// Se comprueba si se envió el formulario de creación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = $_POST['password'];

    // Se realiza una validación adicional del correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /forma-t/public/pages/admin.php?error=invalid-email-format");
        exit;
    }

    // Se crea una instancia de la clase User
    $user = new User();

    // Se crea el usuario utilizando la función createUser de la clase User
    $userIsCreated = $user->createUser($firstName, $lastName, $email, $role, $status, $password);

    // Dependiendo del resultado, se redirige al administrador
    if ($userIsCreated) {
        header("Location: /forma-t/public/pages/admin.php?success=user-created");
    } else {
        header("Location: /forma-t/public/pages/admin.php?error=user-creation-failed");
    }
    exit;
}

?>




