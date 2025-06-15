<?php
// Se inicia la sesión y se comprueba si el usuario está autenticado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /forma-t/public/pages/login.php");
    exit;
}

// Se incluye la clase User
require_once '../models/User.php';

// Se obtiene el ID del usuario a eliminar desde la URL
if (!isset($_POST['id'])) {
    echo "ID de usuario no proporcionado.";
    exit;
}

// Se comprueba si se envió el formulario para eliminar al usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['id'];

    // Se crea una instancia de la clase User
    $user = new User();

    // Se elimina el usuario utilizando la función deleteUser de la clase User
    $userIsDeleted = $user->deleteUser($userId);

    // Dependiendo del resultado, se redirige al administrador
    if ($userIsDeleted) {
        header("Location: /forma-t/public/pages/admin.php?success=user-deleted");
    } else {
        header("Location: /forma-t/public/pages/admin.php?error=user-deletion-failed");
    }
}
?>


