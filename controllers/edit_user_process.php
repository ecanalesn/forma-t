<?php
// Se inicia la sesión y se comprueba si el usuario está autenticado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /forma-t/public/pages/login.php"); 
    exit;
}

// Se incluye la clase User
require_once '../models/User.php';

// Se obtiene el ID del usuario a editar desde la URL
if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Se crea una instancia de la clase User
    $user = new User();

    // Se edita el usuario utilizando la función editUser de la clase User
    $userIsEdited = $user->editUser($firstName, $lastName, $email, $role, $status, $userId);

    // Se ejecuta la actualización
    if ($userIsEdited) {
        header("Location: /forma-t/public/pages/admin.php?success=user-edited");
        exit;
    } else {
        header("Location: /forma-t/public/pages/admin.php?error=user-edition-failed");
    }
}
?>


