<?php
// Se inicia la sesión y se comprueba si el usuario está autenticado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /forma-t/public/pages/login.php");
    exit;
}

// Se incluye la clase Course
require_once '../models/Course.php';

// Se comprueba si se propoporcionó el ID del curso
if (!isset($_POST['id'])) {
    echo "ID de curso no proporcionado.";
    exit;
}

// Se comprueba si se envió el formulario para eliminar el curso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = $_POST['id'];

    // Se crea una instancia de la clase User
    $course = new Course();

    // Se elimina el usuario utilizando la función deleteUser de la clase User
    $courseIsDeleted = $course->deleteCourse($courseId);

    // Se ejecuta la eliminación
    if ($courseIsDeleted ) {
        header("Location: /forma-t/public/pages/admin.php?success=course-deleted&tab=course");
    } else {
        header("Location: /forma-t/public/pages/admin.php?error=course-deletion-failed&tab=course");
    }
}
?>


