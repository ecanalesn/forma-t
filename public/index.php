<?php
session_start();

// Incluir archivos necesarios
require_once '../models/Connection.php';
require_once '../models/User.php';
require_once '../models/Course.php';
require_once '../models/CourseRequests.php';

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($base_path, '', $request_uri);
$path = trim($path, '/');

// Si no hay ruta, mostrar dashboard
if (empty($path)) {
    $path = 'dashboard';
}

// Mapeo de rutas a archivos
$routes = [
    'dashboard' => 'dashboard.php',
    'login' => 'login.php',
    'register' => 'register.php',
    'profile' => 'profile.php',
    'course' => 'course.php',
    'my-courses' => 'my-courses.php',
    'requests' => 'requests.php',
    'admin' => 'admin.php',
    'create-course' => 'create-course.php',
    'create-user' => 'create-user.php',
    'edit-course' => 'edit-course.php',
    'edit-user' => 'edit-user.php',
    'delete-course' => 'delete-course.php',
    'delete-user' => 'delete-user.php'
];

// Verificar si la ruta existe
if (isset($routes[$path])) {
    $file_path = 'pages/' . $routes[$path];
    if (file_exists($file_path)) {
        include $file_path;
    } else {
        http_response_code(404);
        echo "Página no encontrada";
    }
} else {
    http_response_code(404);
    echo "Página no encontrada";
}
?>
