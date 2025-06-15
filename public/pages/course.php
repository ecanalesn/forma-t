<?php
//Se inicia la sesión
session_start();

// Se incluye la clase Course
require_once '../../models/Course.php';

// Se incluye la clase CourseRequests
require_once '../../models/CourseRequests.php';

// Se comprueba si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se obtiene el ID del curso desde la URL
if (!isset($_GET['id'])) {
    die("Curso no encontrado.");
}

$userId = $_SESSION['user_id'];
$courseId = $_GET['id'];
$courseRequests = null; 

// Si el usuario no es estudiante, se muestra directamente el curso
if ($_SESSION['role'] === 'student') {

    // Se crea una instancia de la clase CourseRequests
    $courseRequests = new CourseRequests();

    // Se comprueba la consulta utilizando la función getUserRequestCourse de la clase CourseRequests
    $courseRequest = $courseRequests->getUserRequestCourse($userId, $courseId);

    if (!$courseRequest || $courseRequest['status'] !== 'approved' || $courseRequest['allowed_access'] != 1) {
        die("No tienes acceso a este curso hasta que tu solicitud sea aprobada.");
    }
}

// Se crea una instancia de la clase Course
$course = new Course();

// Se obtiene el curso utilizando la función getByCourseId de la clase Course
$currentCourse = $course->getByCourseId($courseId);

if (!$currentCourse) {
    die("Curso no encontrado.");
}

// Se asignan los datos del curso
$courseTitle = $currentCourse['course_name'];
$courseDescription = $currentCourse['description'];
$courseContent = $currentCourse['content'];
$courseVideo = $currentCourse['video_link'];
?>

<!-- Página Curso -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($courseTitle); ?></title>
    <?php require_once '../includes/styles.php'; ?>
    <link rel="stylesheet" href="../resources/css/menu.css">
    <link rel="stylesheet" href="../resources/css/course.css">
</head>

<body>
    <!-- Incluye la cabecera -->
    <?php require_once '../includes/header.php'; ?> 

    <!-- Incluye el menú -->
    <div class="layout-container">
        <?php require_once '../includes/menu.php'; ?> 
        <div class="course-container">
            <h1><?php echo htmlspecialchars($courseTitle); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($courseDescription)); ?></p>
            
            <div class="video-container">
                <iframe width="100%" height="400" src="<?php echo htmlspecialchars($courseVideo); ?>" 
                        title="Video del curso" frameborder="0" allow="accelerometer; autoplay; 
                        encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                </iframe>
            </div>

            <p><?php echo nl2br(htmlspecialchars($courseContent)); ?></p>
            
            <!-- Botón para ver el curso o volver -->
            <div class="back-button-container">
                <?php if ($_SESSION['role'] !== 'student' || ($courseRequest && $courseRequest['status'] === 'approved' && $courseRequest['allowed_access'] == 1)) { ?>
                <?php } ?>
                
                <!-- Botón para volver siempre visible -->
                <a href="dashboard.php" class="course-button back-button">← Volver</a>
            </div>
        </div>
    </div>

    <!-- Enlace al archivo JS -->
    <?php require_once '../includes/scripts.php'; ?>
    <script src="../resources/js/dashboard.js"></script>
</body>
</html>











