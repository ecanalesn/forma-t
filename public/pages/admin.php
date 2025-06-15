<?php
// Se inicia la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se incluye la clase User
require_once '../../models/User.php';

// Se incluye la clase Course
require_once '../../models/Course.php';

// Se comprueba si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}

// Se crea una instancia de la clase User
$user = new User();

// Se obtienen todos los usuarios utilizando la función getAllUsers de la clase User
$showAllUsers = $user->getAllUsers();

// Se crea una instancia de la clase Course
$course = new Course();

// Se obtienen todos los cursos utilizando la función getAllCourses de la clase Course
$showAllCourses = $course->getAllCourses();
?>

<!-- Página Administración -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración · Forma-T</title>
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
            <h1>Panel de Administración</h1>

            <!-- Mostrar alertas -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php 
                        if ($_GET['success'] == 'user-created') {
                            echo 'Usuario creado correctamente.';
                        } elseif ($_GET['success'] == 'user-edited') {
                            echo 'Usuario actualizado correctamente.';
                        } elseif ($_GET['success'] == 'user-deleted') {
                            echo 'Usuario eliminado correctamente.';
                        } elseif ($_GET['success'] == 'course-created') {
                            echo 'Curso creado correctamente.';
                        } elseif ($_GET['success'] == 'course-edited') {
                            echo 'Curso actualizado correctamente.';
                        } elseif ($_GET['success'] == 'course-deleted') {
                            echo 'Curso eliminado correctamente.';
                        }
                    ?>
                </div>
            <?php endif; ?>

            <!-- Se muestra el mensaje de error si es necesario -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php 
                        if ($_GET['error'] == 'user-creation-failed') {
                            echo 'Error al crear el usuario. Inténtalo de nuevo.';
                        } elseif ($_GET['error'] == 'user-edition-failed') {
                            echo 'Error al actualizar el usuario. Inténtalo de nuevo.';
                        } elseif ($_GET['error'] == 'user-deletion-failed') {
                            echo 'Error al eliminar el usuario. Inténtalo de nuevo.';
                        } elseif ($_GET['error'] == 'course-creation-failed') {
                            echo 'Error al crear el curso. Inténtalo de nuevo.';
                        } elseif ($_GET['error'] == 'course-edition-failed') {
                            echo 'Error al actualizar el curso. Inténtalo de nuevo.';
                        } elseif ($_GET['error'] == 'course-deletion-failed') {
                            echo 'Error al eliminar el curso. Inténtalo de nuevo.';
                        }
                    ?>
                </div>
            <?php endif; ?>

            <!-- Pestañas para Usuarios y Cursos -->
            <div class="tabs">
                <div class="tab-buttons">
                    <button class="tab-button active" onclick="openTab(event, 'users')" data-tab="users">Usuarios</button>
                    <button class="tab-button" onclick="openTab(event, 'courses')" data-tab="courses">Cursos</button>
                </div>
            </div>

            <!-- Botones para Crear Usuario y Crear Curso debajo de las pestañas -->
            <div class="btn-container">
                <a href="create-user.php?tab=users" class="btn-create" id="createUserBtn">Crear Usuario</a>
                <a href="create-course.php?action=create&tab=cursos" class="btn-create" id="createCourseBtn">Crear Curso</a>
            </div>

            <!-- Contenido de las Pestañas -->
            <div id="users" class="tab-content active">
                <h2>Usuarios</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Se comprueba si hay resultados
                        if ($showAllUsers && count($showAllUsers) > 0) { // Comprobamos si hay usuarios
                            foreach ($showAllUsers as $user) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($user['user_id']) . '</td>';  // Acceso correcto: Array
                                echo '<td>' . htmlspecialchars($user['first_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($user['last_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                                echo '<td>' . htmlspecialchars($user['role']) . '</td>';
                                echo '<td>' . ($user['status'] == 1 ? 'Activo' : 'Inactivo') . '</td>';
                                echo '<td><a href="edit-user.php?id=' . $user['user_id'] . '&tab=users">Editar</a> | <a href="delete-user.php?id=' . $user['user_id'] . '&tab=users">Eliminar</a></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7">No hay usuarios registrados.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Contenido de los Cursos -->
            <div id="courses" class="tab-content">
                <h2>Cursos</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Curso</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Se comprueba si hay resultados
                        if ($showAllCourses) {
                            // Mostrar los cursos
                            foreach ($showAllCourses as $course) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($course['course_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($course['course_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($course['category']) . '</td>';
                                echo '<td>' . ($course['status'] == 1 ? 'Activo' : 'Inactivo') . '</td>';
                                echo '<td><a href="edit-course.php?id=' . $course['course_id'] . '&action=edit&tab=cursos">Editar</a> | <a href="delete-course.php?id=' . $course['course_id'] . '&action=delete&tab=cursos">Eliminar</a></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No hay cursos disponibles.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Enlace al archivo JS --> 
    <?php require_once '../includes/scripts.php'; ?>
    <script src="../resources/js/menu.js"></script>
    <script src="../resources/js/admin.js"></script>

</body>
</html>