<?php
// Se inicia la sesión si aún no está iniciada
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se incluye la clase User
require_once '../../models/User.php';

// Se comprueba si se proporcionó el ID del usuario
if (!isset($_GET['id'])) {
    $errorMessage =  "ID de usuario no proporcionado.";
    exit;
}

$userId = $_GET['id'];

// Se crea una instancia de la clase User
$user = new User();

// Se obtienen todos los usuarios utilizando la función getByUserId de la clase User
$currentUser = $user->getByUserId($userId);

if (!$currentUser) {
    $errorMessage =  "Usuario no encontrado.";
    exit;
}
?>

<!-- Página Eliminar Usuario -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar usuario · Forma-T</title>
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
        <div class="content">
            <h1>Eliminar usuario</h1>

            <p>¿Estás seguro de que deseas eliminar a este usuario?</p>

            <form action="../../controllers/delete_user_process.php" method="POST">
                <input type="hidden" value="<?php echo $userId; ?>" name="id">
                <table class="table">
                    <tr>
                        <td><strong>Nombre:</strong></td>
                        <td><?php echo htmlspecialchars($currentUser['first_name']); ?></td>
                    </tr>

                    <tr>
                        <td><strong>Apellidos:</strong></td>
                        <td><?php echo htmlspecialchars($currentUser['last_name']); ?></td>
                    </tr>

                    <tr>
                        <td><strong>Correo:</strong></td>
                        <td><?php echo htmlspecialchars($currentUser['email']); ?></td>
                    </tr>

                    <tr class="submit-row">
                        <td colspan="2">
                            <button type="submit" value="Eliminar Usuario" class="delete-button">Eliminar Usuario</button>
                        </td>
                    </tr>
                </table>
            </form>

            <!-- Se muestra error si hay algún problema al actualizar -->
            <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scripts -->
    <?php require_once '../includes/scripts.php'; ?>
    <script src="../resources/js/admin.js"></script>
</body>
</html>


