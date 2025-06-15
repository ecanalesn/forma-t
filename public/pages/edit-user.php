<?php
// Se inicia la sesión si aún no está iniciada
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se incluye la clase User
require_once '../../models/User.php';

// Se obtiene el ID del usuario desde la URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Se crea una instancia de la clase User
    $user = new User();

    // Se obtienen todos los usuarios utilizando la función getByUserId de la clase User
    $currentUser = $user->getByUserId($userId);

    if (!$currentUser) {
        $errorMessage = "Usuario no encontrado.";
        exit;
    }
} else {
    $errorMessage = "ID de usuario no proporcionado.";
    exit;
}
?>

<!-- Página Editar Usuario -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario · Forma-T</title>
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
            <h1>Editar usuario</h1>

            <form action="http://localhost/forma-t/controllers/edit_user_process.php" method="POST">
                <input type="hidden" value="<?php echo $currentUser['user_id']; ?>" name="id">
                <table class="table">
                    <tr>
                        <td><label for="firstName">Nombre:</label></td>
                        <td><input type="text" name="first_name" value="<?php echo htmlspecialchars($currentUser['first_name']); ?>" required></td>
                    </tr>

                    <tr>
                        <td><label for="lastName">Apellidos:</label></td>
                        <td><input type="text" name="last_name" value="<?php echo htmlspecialchars($currentUser['last_name']); ?>" required></td>
                    </tr>

                    <tr>
                        <td><label for="email">Correo:</label></td>
                        <td><input type="email" name="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" required></td>
                    </tr>

                    <tr>
                        <td><label for="role">Rol:</label></td>
                        <td>
                            <select name="role">
                                <option value="admin" <?php if ($currentUser['role'] == 'admin') echo 'selected'; ?>>Administrador</option>
                                <option value="student" <?php if ($currentUser['role'] == 'student') echo 'selected'; ?>>Estudiante</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="status">Estado:</label></td>
                        <td>
                            <select name="status">
                                <option value="1" <?php if ($currentUser['status'] == 1) echo 'selected'; ?>>Activo</option>
                                <option value="0" <?php if ($currentUser['status'] == 0) echo 'selected'; ?>>Inactivo</option>
                            </select>
                        </td>
                    </tr>

                    <tr class="submit-row">
                        <td colspan="2">
                            <input type="submit" value="Actualizar Usuario" class="update-button">
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
</body>
</html>


