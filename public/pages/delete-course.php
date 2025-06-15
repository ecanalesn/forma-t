<?php
// Se inicia la sesión y se comprueba si el usuario está autenticado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se incluye la clase Course
require_once '../../models/Course.php';

// Se obtiene el ID del curso desde la URL
if (!isset($_GET['id'])) {
    $errorMessage = "ID de curso no proporcionado.";
    exit;
}

$courseId = $_GET['id'];

// Se crea una instancia de la clase Course
$course = new Course();

// Se obtienen todos los cursos utilizando la función getByCourseId de la clase Course
$currentCourse = $course->getByCourseId($courseId);

if (!$currentCourse) {
    echo "Curso no encontrado.";
    exit;
}
?>

<!-- Página Eliminar Curso -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar curso · Forma-T</title>
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
            <h1>Eliminar curso</h1>

            <p>¿Estás seguro de que deseas eliminar este curso?</p>

            <form action="../../controllers/delete_course_process.php?" method="POST">
            <input type="hidden" value="<?php echo $courseId; ?>" name="id">
                <table class="table">
                    <tr>
                        <td><strong>Nombre del Curso:</strong></td>
                        <td><?php echo htmlspecialchars($currentCourse['course_name']); ?></td>
                    </tr>

                    <tr>
                        <td><strong>Categoría:</strong></td>
                        <td><?php echo htmlspecialchars($currentCourse['category']); ?></td>
                    </tr>

                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td><?php echo $currentCourse['status'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                    </tr>

                    <tr class="submit-row">
                        <td colspan="2">
                            <button type="submit" value="Eliminar Curso" class="delete-button">Eliminar Curso</button>
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

