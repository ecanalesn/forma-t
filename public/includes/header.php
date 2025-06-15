<?php
// Se incluye la clase CourseRequests
require_once '../../models/CourseRequests.php';

// Se obtiene el nombre del usuario desde la sesión
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
$sessionUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Se crea una instancia de la clase CourseRequests
$courseRequests = new CourseRequests();

// Se comprueba si el usuario es un estudiante y tiene notificación
$unreadCount = 0; 
if (isset($_SESSION['role']) && $_SESSION['role'] === 'student' && $sessionUserId) {
    
    // Se crea la consulta utilizando la función getProcessedRequestsByUser de la clase CourseRequests
    $requests = $courseRequests->getProcessedRequestsByUser($sessionUserId);

    // Se cuentan las notificaciones no leídas
    foreach ($requests as $request) {
    // Se comprueba si la solicitud tiene el campo 'is_read' y si es igual a 0
    if (isset($request['is_read']) && $request['is_read'] == 0) {
        $unreadCount++;
    }
    }
}

// Se comprueba si se ha hecho clic en una notificación para marcarla como leída
if (isset($_GET['notification_id'])) {
    $requestId = $_GET['notification_id'];

    // Se ejecuta la consulta utilizando la función readRequest de la clase courseRequests
    $courseRequests->readRequest($requestId);

    // Se redirige a la misma página para evitar el reenvío del formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMA-T</title>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <h1 class="logo">FORMA-T</h1>
            
            <div style="display: flex; align-items: center;">
                <!-- Se muestra la campana con notificaciones si el usuario es estudiante -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>
                    <div class="dropdown" style="position: relative; margin-right: 15px;">
                        <!-- Icono de la campana -->
                        <button class="btn btn-link" id="bellDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-bell" style="font-size: 24px;"></i> 
                            <!-- Mostrar el número de notificaciones no leídas -->
                            <?php if ($unreadCount > 0): ?>
                                <span class="notification-badge"><?php echo $unreadCount; ?></span>
                            <?php endif; ?>
                        </button>
                        <!-- Lista del menú de notificaciones -->
                        <ul class="dropdown-menu" aria-labelledby="bellDropdownButton">
                            <?php if (count($requests) > 0): ?>
                                <?php foreach ($requests as $request): ?>
                                    <li class="notification-item <?php echo $request['status']; ?>">
                                        <p onclick="markAsRead(<?php echo $request['request_id']; ?>)">
                                            <?php echo ($request['status'] === 'approved') ? 'Tu solicitud de matrícula ha sido aceptada.' : 'Tu solicitud de matrícula ha sido rechazada.'; ?>
                                        </p>
                                        <small>Fecha: <?php echo date("d/m/Y H:i", strtotime($request['request_date'])); ?></small>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="no-notifications">
                                    <p>No tienes notificaciones.</p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
         
                <!-- Se muestra el nombre de usuario si está autenticado -->
                <?php if ($userName): ?>
                    <button class="name-button" id="nameButton">
                        <span id="user_name"><?php echo htmlspecialchars($userName); ?></span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <!-- Scripts -->
    <script src="../resources/js/header.js"></script>
</body>
</html>

