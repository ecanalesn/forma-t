<?php
// Se inicia la sesión y se verifica si el usuario está autenticado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!-- Página Crear Usuario -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario · Forma-T</title>
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
            <h1>Crear usuario</h1>

            <!-- Formulario de creación de usuario -->
            <form action="../../controllers/create_user_process.php" method="POST">
                <table class="table">
                    <tr>
                        <td><label for="first_name">Nombre:</label></td>
                        <td><input type="text" name="first_name" required></td>
                    </tr>

                    <tr>
                        <td><label for="last_name">Apellidos:</label></td>
                        <td><input type="text" name="last_name" required></td>
                    </tr>

                    <tr>
                        <td><label for="email">Correo:</label></td>
                        <td><input type="email" name="email" required></td>
                    </tr>

                    <tr>
                        <td><label for="role">Rol:</label></td>
                        <td>
                            <select name="role" required>
                                <option value="admin">Administrador</option>
                                <option value="student">Estudiante</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="status">Estado:</label></td>
                        <td>
                            <select name="status" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="password">Contraseña:</label></td>
                        <td><input type="password" name="password" required></td>
                    </tr>

                    <tr class="submit-row">
                        <td colspan="2">
                            <input type="submit" value="Crear Usuario" class="create-button">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

     <!-- Scripts -->
     <?php require_once '../includes/scripts.php'; ?>
    <script src="../resources/js/admin.js"></script>
</body>
</html>

