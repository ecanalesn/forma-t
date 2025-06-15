<?php
//Se inicia la sesión
session_start();

// Se incluye la clase CourseRequests
require_once '../models/CourseRequests.php';

// Se crea una instancia de la clase CourseRequests
$courseRequests = new CourseRequests();

// Se comprueba si el usuario es admin para aprobar o rechazar solicitudes
if ($_SESSION['role'] === 'admin' && isset($_POST['request_id'], $_POST['status'])) {
    $requestId = $_POST['request_id'];
    $status = $_POST['status']; 

    if ($status === 'approved' || $status === 'rejected') {

        // Se crea consulta utilizando la función changeStatus de la clase CourseRequests
        $courseRequests->changeStatus($status, $requestId);

        $statusMessage = ($status === 'approved') ? 'course_approved' : 'course_rejected';
        header("Location: /forma-t/public/pages/requests.php?success=" . $statusMessage);
        exit;
    } else {
        echo "Acción no válida.";
    }
} else {
    echo "Acceso restringido. Solo los administradores pueden aprobar o rechazar solicitudes.";
}
?>






