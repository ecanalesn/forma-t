<?php
// Se inicia la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se incluye la clase User
require_once '../models/User.php';

// Se comprueba si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Se obtiene el ID del usuario desde la sesión
$sessionUserId = $_SESSION['user_id'];

// Se procesa la actualización de datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $password = ($_POST['password']);
    $phone = trim($_POST['phone']);

    // Se valida la contraseña (si se modificó)
    if (!empty($password)) {
        if (strlen($password) < 6) {
            header("Location: /forma-t/public/pages/profile.php?error=password-too-short");
            exit;
        }
        if (strpos($password, ' ') !== false) {
            header("Location: /forma-t/public/pages/profile.php?error=password-contains-spaces");
            exit;
        }
    }

    // Se crea una instancia de la clase User
    $user = new User();

    // Se edita el perfil utilizando la función editProfile de la clase User
    $profileIsEdited = $user->editProfile($firstName, $lastName, $sessionUserId, $phone, $password);

    if ($profileIsEdited) {
        header("Location: /forma-t/public/pages/profile.php?success=profile-edited");
        exit;
    } else {
        header("Location: /forma-t/public/pages/profile.php?error=profile-edition-failed");
    }
}
?>