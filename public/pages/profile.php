<?php
// Se inicia la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se incluye la clase User
require_once '../../models/User.php';

// Se comprueba si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sessionUserId = $_SESSION['user_id'];

// Se crea una instancia de la clase User
$user = new User();

// Se obtienen todos los usuarios utilizando la función getByUserId de la clase User
$currentUser = $user->getByUserId($sessionUserId);
?>

<!-- Página Perfil -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil · Forma-T</title>
    <?php require_once '../includes/styles.php'; ?>
    <link rel="stylesheet" href="../resources/css/menu.css"/>
    <link rel="stylesheet" href="../resources/css/admin.css">
</head>
<body>
    <!-- Incluye la cabecera -->
    <?php require_once '../includes/header.php'; ?>

    <!-- Incluye el menú -->
    <div class="layout-container">
        <?php require_once '../includes/menu.php'; ?>  

        <!-- Contenido principal de la página -->
        <div class="admin-content">
            <h1>Mi Perfil</h1>

            <!-- Mensajes de éxito o error con estilo de Bootstrap -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo "Perfil actualizado correctamente."; ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                    if ($_GET['error'] == 'password-too-short') {
                        echo 'La contraseña debe tener al menos 6 caracteres.';
                    } elseif ($_GET['error'] == 'password-contains-spaces') {
                        echo 'La contraseña no puede contener espacios.';
                    } elseif ($_GET['error'] == 'invalid-email-format') {
                        echo 'El formato del correo electrónico no es válido. Introduce un correo electrónico correcto';
                    } elseif ($_GET['error'] == 'email-exists') {
                        echo 'El correo electrónico que has introducido ya está registrado.';
                    } elseif ($_GET['error'] == 'registration-failed') {
                        echo 'Error al registrar la cuenta. Inténtalo de nuevo más tarde.';
                    } elseif ($_GET['error'] == 'db-error') {
                        echo 'Hubo un error en la base de datos. Inténtalo de nuevo más tarde.';
                    } else {
                        echo "Error al actualizar el perfil.";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de editar perfil -->
            <form action="../../controllers/edit_profile_process.php" method="POST">
                <table class="table">
                    <tr>
                        <td><label for="first_name">Nombre:</label></td>
                        <td><input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($currentUser['first_name'] ?? ''); ?>" required></td>
                    </tr>

                    <tr>
                        <td><label for="last_name">Apellidos:</label></td>
                        <td><input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($currentUser['last_name'] ?? ''); ?>" required></td>
                    </tr>

                    <tr>
                        <td><label for="email">Correo Electrónico:</label></td>
                        <td><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($currentUser['email'] ?? ''); ?>" disabled></td>
                    </tr>

                    <tr>
                        <td><label for="password">Nueva Contraseña (opcional):</label></td>
                        <td><input type="password" id="password" name="password"></td>
                    </tr>

                    <tr>
                        <td><label for="phone">Teléfono móvil (opcional):</label></td>
                        <td><input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($currentUser['phone'] ?? ''); ?>"></td>
                    </tr>

                    <tr class="submit-row">
                        <td colspan="2">
                            <input type="submit" value="Guardar" class="update-button">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

   <!-- Scripts -->
    <?php require_once '../includes/scripts.php'; ?>
    <script src="../resources/js/menu.js"></script>
    <script src="../resources/js/dashboard.js"></script>
</body>
</html>



