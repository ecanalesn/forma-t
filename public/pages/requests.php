<?php
// Se inicia la sesión
session_start();

// Se incluye la clase CourseRequests
require_once '../../models/CourseRequests.php';

// Se comprueba si el usuario tiene el rol de admin
if ($_SESSION['role'] !== 'admin') {
    echo "Acceso restringido. Solo los administradores pueden ver las solicitudes.";
    exit;
}

// Se crea una instancia de la clase CourseRequests
$courseRequests = new CourseRequests();

$enrollmentRequests = $courseRequests->getAllRequests()
?>

<!-- Página Solicitudes -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes · Forma-T</title>
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
        <div class="admin-content">
            <h1>Solicitudes de Matrícula</h1>

            <!-- Se muestran las alertas si es necesario -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php 
                        if ($_GET['success'] == 'course_approved') {
                            echo 'Matrícula aceptada.';
                        } elseif ($_GET['success'] == 'course_rejected') {
                            echo 'Matrícula rechazada.';
                        }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php 
                        if ($_GET['error'] == 'action_failed') {
                            echo 'Hubo un error al procesar la solicitud. Inténtalo de nuevo.';
                        }
                    ?>
                </div>
            <?php endif; ?>

            <div class="tabs">
                <div class="tab-buttons">
                    <button class="tab-button active" onclick="openTab(event, 'pendientes')">Pendientes</button>
                    <button class="tab-button" onclick="openTab(event, 'aprobadas')">Aprobadas</button>
                    <button class="tab-button" onclick="openTab(event, 'rechazadas')">Rechazadas</button>
                </div>
            </div>

            <div id="pendientes" class="tab-content active">
                <h2>Solicitudes pendientes</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Curso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrollmentRequests as $enrollmentRequest): ?>
                            <?php if ($enrollmentRequest['status'] === 'pending'): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($enrollmentRequest['first_name'] . ' ' . $enrollmentRequest['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($enrollmentRequest['course_name']); ?></td>
                                    <td>
                                        <form action="../../controllers/admin_requests.php" method="POST">
                                            <input type="hidden" name="request_id" value="<?php echo $enrollmentRequest['request_id']; ?>">
                                            <button type="submit" name="status" value="approved" class="btn btn-success">Aprobar</button>
                                            <button type="submit" name="status" value="rejected" class="btn btn-danger">Rechazar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="aprobadas" class="tab-content">
                <h2>Solicitudes aprobadas</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrollmentRequests as $enrollmentRequest): ?>
                            <?php if ($enrollmentRequest['status'] === 'approved'): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($enrollmentRequest['first_name'] . ' ' . $enrollmentRequest['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($enrollmentRequest['course_name']); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="rechazadas" class="tab-content">
                <h2>Solicitudes rechazadas</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrollmentRequests as $enrollmentRequest): ?>
                            <?php if ($enrollmentRequest['status'] === 'rejected'): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($enrollmentRequest['first_name'] . ' ' . $enrollmentRequest['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($enrollmentRequest['course_name']); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <!-- Scripts -->
    <?php require_once '../includes/scripts.php'; ?>
    <script src="../resources/js/menu.js"></script>
    <script src="../resources/js/admin.js"></script>

</body>
</html>