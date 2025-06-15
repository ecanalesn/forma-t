<?php
// Se inicia la sesión si aún no está iniciada
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se incluye la clase Course
require_once '../../models/Course.php';

// Se obtiene el ID del curso desde la URL
if (isset($_GET['id'])) {
    $courseId = $_GET['id'];

    // Se crea una instancia de la clase Course
    $course = new Course();

    // Se obtienen todos los cursos utilizando la función getByCourseId de la clase Course
    $currentCourse = $course->getByCourseId($courseId);

    if (!$currentCourse) {
        $errorMessage = "Curso no encontrado.";
    }
} else {
    $errorMessage = "ID de curso no proporcionado.";
}
?>

<!-- Página Editar Curso -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar curso · Forma-T</title>
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
            <h1>Editar curso</h1>

            <!-- Formulario de edición del curso -->
            <form action="../../controllers/edit_course_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $currentCourse['course_id']; ?>" name="id">
            <table class="table">
                <tr>
                    <td><label for="courseName">Nombre del Curso:</label></td>
                    <td><input type="text" name="course_name" value="<?php echo htmlspecialchars($currentCourse['course_name']); ?>" required style="width: 100%; border: 1px solid"></td>
                </tr>

                <tr>
                    <td><label for="description">Descripción:</label></td>
                    <td>
                        <textarea name="description" required style="width: 100%; min-height: 100px; resize: vertical;"><?php echo htmlspecialchars($currentCourse['description']); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td><label for="category">Categoría:</label></td>
                    <td>
                        <select name="category" required>
                            <option value="Instrumento" <?php echo $currentCourse['category'] == 'Instrumento' ? 'selected' : ''; ?>>Instrumento</option>
                            <option value="Teoría Musical" <?php echo $currentCourse['category'] == 'Teoría Musical' ? 'selected' : ''; ?>>Teoría Musical</option>
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
                            <option value="1" <?php echo $currentCourse['status'] == 1 ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo $currentCourse['status'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><label for="content">Contenido:</label></td>
                    <td>
                        <textarea name="content" required style="width: 100%; min-height: 100px; resize: vertical;"><?php echo htmlspecialchars($currentCourse['content']); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td><label for="video_link">Url del vídeo:</label></td>
                    <td><input type="url" name="video_link" value="<?php echo htmlspecialchars($currentCourse['video_link']); ?>" required style="width: 100%; border: 1px solid"></td>
                </tr>

                <tr class="submit-row">
                    <td colspan="2">
                        <input type="submit" value="Actualizar Curso" class="update-button">
                    </td>
                </tr>
            </table>

            </form>

            <!-- Mostrar error si hay algún problema al actualizar -->
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

