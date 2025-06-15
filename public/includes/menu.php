<?php
// Se inicia la sesión si no está iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Se incluye la clase CourseRequests
require_once '../../models/CourseRequests.php';

// Se declara el número de solicitudes pendientes por defecto
$pendingCount = 0;

// Se comprueba si la conexión existe antes de ejecutar consultas
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {

    // Se crea una instancia de la clase CourseRequests
    $courseRequests = new CourseRequests();

    // Se crea una consulta utilizando la función getPendingCount de la clase CourseRequests
    $pendingCount = $courseRequests->getPendingCount();
}
?>

<!-- Menú -->
<div class="sidebar">
    <ul>
        <li><a href="../pages/dashboard.php" class="menu-item inicio"><i class="fa-solid fa-house"></i>Inicio</a></li>

        <?php if ($_SESSION['role'] === 'student'): ?>
            <li><a href="../pages/my-courses.php" class="menu-item cursos"><i class="fa-solid fa-graduation-cap"></i>Mis cursos</a></li>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li><a href="../pages/admin.php" class="menu-item configuracion"><i class="fa-solid fa-gear"></i>Administración</a></li>
            <li>
                <a href="../pages/requests.php" class="menu-item configuracion">
                    <i class="fa-solid fa-check-to-slot"></i>Solicitudes
                    <?php if ($pendingCount > 0): ?>
                        <span class="badge-custom"><?= htmlspecialchars($pendingCount) ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endif; ?>

        <li><a href="../../controllers/logout_process.php" class="menu-item logout"><i class="fa-solid fa-right-from-bracket"></i>Salir</a></li>
    </ul>
</div>

<!-- Botón de menú hamburguesa -->
<button class="menu-toggle"><i class="fa-solid fa-bars"></i></button>








