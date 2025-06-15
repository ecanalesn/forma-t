<?php
// Se incluye la clase User
require_once '../models/User.php';

// Se comprueba si los datos del formulario se encuentran
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Se valida la longitud de la contraseña
    if (strlen($password) < 6) {
        // Si la contraseña es menor de 6 caracteres, se redirige con un mensaje de error
        header("Location: /forma-t/public/pages/register.php?error=password-too-short");
        exit;
    }

    // Se valida que la contraseña no contenga espacios
    if (strpos($password, ' ') !== false) {
            // Si la contraseña contiene espacios, se redirige con un mensaje de error
        header("Location: /forma-t/public/pages/register.php?error=password-contains-spaces");
        exit;
    }

    // Se realiza una validación adicional del correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Si el correo electrónico no es válido, se redirige con un mensaje de error
        header("Location: /forma-t/public/pages/register.php?error=invalid-email-format");
        exit;
    }

    // Se crea una instancia de la clase User
    $user = new User();

    try {
        if ($user->getByEmail($email)) {
            // Si el correo ya está registrado, se redirige con parámetro de error
            header("Location: /forma-t/public/pages/register.php?error=email-exists");
            exit();
        } else {
            // Se ejecuta la consulta de inserción
            if ($user->register($firstName, $lastName, $email, $password)) {
                // Se redirige al login si el registro es correcto
                header("Location: /forma-t/public/pages/register.php?success=1");
                exit();
            } else {
                // Se muestra error si el registro no fue correcto
                header("Location: /forma-t/public/pages/register.php?error=registration-failed");
                exit();
            }
        }
    } catch (PDOException $e) {
        // Se manejan los errores en la base de datos
        header("Location: /forma-t/public/pages/register.php?error=db-error");
        exit();
    }
}
?>





