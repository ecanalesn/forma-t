<?php
//Se inicia la sesión
session_start();

// Se inicia la sesión si no está iniciada
if (!isset($_SESSION)) {
    session_start();
}
// Se incluye la clase CourseRequests
require_once '../models/CourseRequests.php';

// Se obtiene el ID del usuario desde la sesión
$sessionUserId = $_SESSION['user_id'];

// Se crea una instancia de la clase CourseRequests
$courseRequests = new CourseRequests();

// Se comprueba si existe el usuario y el curso
if (isset($_POST['course_id']) && isset($_POST['user_id'])) {
    $courseId = $_POST['course_id'];
    $userId = $_POST['user_id'];
 
    // Se crea consulta utilizando la función getUserRequestCourse de la clase CourseRequests
    $courseRequest = $courseRequests->getUserRequestCourse($userId, $courseId);

    if (!$courseRequest) {
        // Se crea consulta utilizando la función createEnrollment de la clase CourseRequests si el usuario no ha solicitado el curso anteriormente
        $courseRequests->createEnrollment($userId, $courseId);
        echo "success";
    } else {
        echo "already_requested";
    }
}

?>







