<?php
// Se inicia la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se incluye la clase Course
require_once '../../models/Course.php';

// Se comprueba si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se obtiene el ID del usuario desde la sesión
$userId = $_SESSION['user_id'];

// Se crea una instancia de la clase Course
$course = new Course();

// Se define la consulta según el rol del usuario
if ($_SESSION['role'] === 'student') {

    // Se obtienen los cursos matriculados utilizando la función getEnrollmentCoursesByUserId de la clase Course
    $courseIsEnrollment = $course->getEnrollmentCoursesByUserId($userId);

    // Si no hay cursos matriculados, se asigna un array vacío
    if ($courseIsEnrollment === null) {
        $courseIsEnrollment = [];
    }

} else if ($_SESSION['role'] === 'admin') {

    // Si es administrador, se muestran todos los cursos activos utilizando la función getActiveCourses de la clase Course
    $activeCourses = $course->getActiveCourses();

    // Si no hay cursos activos, se asigna un array vacío
    if ($activeCourses === null) {
        $activeCourses = [];
    }
}
?>

<!-- Página Mis Cursos -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis cursos · Forma-T</title>
    <?php require_once '../includes/styles.php'; ?>
    <link rel="stylesheet" href="../resources/css/menu.css"/>
    <link rel="stylesheet" href="../resources/css/dashboard.css">   
</head>
<body>  
    <!-- Incluye la cabecera -->
    <?php require_once '../includes/header.php'; ?> 

    <!-- Incluye el menú -->
    <div class="layout-container">
        <?php require_once '../includes/menu.php'; ?>  

        <!-- Contenido principal -->
        <div class="content">
            <div class="courses-section">
                <h2><?php echo 'Cursos matriculados'; ?></h2>
                <div class="courses-grid">
                    <?php if (count($courseIsEnrollment) > 0): ?>
                        <?php foreach ($courseIsEnrollment as $courseItem): ?>
                            <div class="course-card">
                                <img src="<?= htmlspecialchars($courseItem['image']); ?>" alt="<?= htmlspecialchars($courseItem['course_name']); ?>" class="course-image">
                                <div class="course-content">
                                    <h3 class="course-title"><?= htmlspecialchars($courseItem['course_name']); ?></h3>
                                    <span class="course-category">Categoría: <?= htmlspecialchars($courseItem['category']); ?></span>
                                    <a href="course.php?id=<?= $courseItem['course_id']; ?>" class="course-button btn btn-success">Ver curso</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay cursos disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

   <!-- Scripts -->
   <?php require_once '../includes/scripts.php'; ?>
</body>
</html>

