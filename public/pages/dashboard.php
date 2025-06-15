<?php
// Se inicia la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se incluye la clase Course
require_once  '../../models/Course.php';

// Se incluye la clase CourseRequests
require_once  '../../models/CourseRequests.php';

// Se comprueba si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se crea una instancia de la clase Course
$course = new Course();

// Se comprueba si el curso está activo utilizando la función getActiveCourses de la clase Course
$activeCourses = $course->getActiveCourses();

// Se guarda el ID de usuario
$userId = $_SESSION['user_id'];

?>

<!-- Página Inicio -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos · Forma-T</title>
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
            <h1>¡Bienvenido a Forma-T!</h1>
            <p>Plataforma de Formación online en Música</p>

            <div class="courses-section">
                <h2>Catálogo de cursos</h2>
                <div class="courses-grid">
                    <?php
                        if ($activeCourses) {
                            // Se crea una instancia de la clase CourseRequests
                            $courseRequests = new CourseRequests();

                            foreach ($activeCourses as $course) {
                                // Se obtiene el ID del curso actual
                                $courseId = $course['course_id']; 

                                // Se crea la consulta utilizando la función getUserRequestCourse de la clase CourseRequests
                                $courseRequest = $courseRequests->getUserRequestCourse($userId, $courseId);

                                echo '<div class="course-card">';
                                echo '<img src="' . htmlspecialchars($course['image']) . '" alt="' . htmlspecialchars($course['course_name']) . '" class="course-image">';
                                echo '<div class="course-content">';
                                echo '<h3 class="course-title">' . htmlspecialchars($course['course_name']) . '</h3>';
                                echo '<span class="course-category">Categoría: ' . htmlspecialchars($course['category']) . '</span>';

                                // Se cambia el botón según el estado de la solicitud
                                if ($_SESSION['role'] === 'student') {
                                    if ($courseRequest && $courseRequest['status'] == 'approved'){
                                        echo '<a href="course.php?id=' . $course['course_id'] . '" class="course-button btn btn-success">Ver curso</a>';
                                    } elseif ($courseRequest && $courseRequest['status'] == 'rejected'){
                                        echo '<span class="text-danger">Tu solicitud ha sido rechazada. No puedes acceder a este curso.</span>';
                                    } else {
                                        echo '<button class="course-button btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollmentModal" 
                                            data-course-id="' . $course['course_id'] . '" 
                                            data-user-id="' . $_SESSION['user_id'] . '">Matricular</button>';     
                                    }
                                } else {
                                    echo '<a href="course.php?id=' . $course['course_id'] . '" class="course-button btn btn-primary">Ver curso</a>';
                                }

                                echo '</div>'; 
                                echo '</div>';
                            } 

                        } else {
                            echo '<p>No hay cursos disponibles.</p>';
                        }
                    ?>                
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Bootstrap -->
    <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="enrollmentModalTitle">En proceso</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="enrollmentModalBody" class="modal-body">
                Enviando solicitud...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php require_once '../includes/scripts.php'; ?>
    <script src="../resources/js/menu.js"></script>
    <script src="../resources/js/dashboard.js"></script>
</body>
</html>
