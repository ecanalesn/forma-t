<?php
// Se inicia la sesión y se comprueba si el usuario está autenticado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!-- Página Crear Curso -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear curso · Forma-T</title>
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
            <h1>Crear curso</h1>

            <!-- Formulario de creación de curso -->
            <form action="../../controllers/create_course_process.php" method="POST" enctype="multipart/form-data">
                <table class="table">
                    <tr>
                        <td><label for="course_name">Nombre del Curso:</label></td>
                        <td><input type="text" name="course_name" required style="width: 100%; border: 1px solid"></td>
                    </tr>

                    <tr>
                        <td><label for="description">Descripción:</label></td>
                        <td>
                            <textarea name="description" required style="width: 100%; min-height: 100px; resize: vertical;"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="category">Categoría:</label></td>
                        <td>
                            <select name="category" required>
                                <option value="Instrumento">Instrumento</option>
                                <option value="Teoría Musical">Teoría Musical</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="image">Imagen:</label></td>
                        <td>
                            <input type="file" name="image" style="width: 100%; max-width: 100%;">
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
                        <td><label for="content">Contenido:</label></td>
                        <td>
                            <textarea name="content" required style="width: 100%; min-height: 100px; resize: vertical;"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="video_link">Url del vídeo:</label></td>
                        <td><input type="url" name="video_link" required style="width: 100%; border: 1px solid"></td>
                    </tr>

                    <tr class="submit-row">
                        <td colspan="2">
                            <input type="submit" value="Crear Curso" class="create-button">
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



