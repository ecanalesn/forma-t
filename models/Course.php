<?php
// Se incluye la clase Connection
require_once   'Connection.php';

class Course extends Connection {
    // Constructor de la clase que hereda la conexión de la clase Connection
    public function __construct() {
        parent::__construct(); 
    }


    // Función para obtener un curso por ID
    public function getByCourseId($courseId) {

    // Se realiza la consulta SQL para obtener todos los cursos por ID
    $sql = "SELECT * FROM courses WHERE course_id = :course_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
    
    // Se ejecuta la consulta de inserción
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
    }   
    
    // Función para mostrar todos los cursos
    public function getAllCourses() {

        // Se realiza la consulta SQL para obtener todos los cursos
        $sql = "SELECT * FROM courses"; 
        $stmt = $this->conn->prepare($sql);

        // Se ejecuta la consulta de inserción
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para comprobar si un curso está activo
    public function getActiveCourses() {

        // Se realiza la consulta SQL para obtener todos los cursos
        $sql = "SELECT * FROM courses WHERE status = 1"; 
        $stmt = $this->conn->prepare($sql);

        // Se ejecuta la consulta de inserción
        $stmt->execute();

        // Se devuelven todos los resultados como un array de arrays
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para obtener los cursos matriculados por ID de usuario
    public function getEnrollmentCoursesByUserId($userId) {
  
        // Se realiza la consulta SQL para crear un nuevo curso
        $sql = "SELECT c.* FROM courses c INNER JOIN course_requests cr ON c.course_id = cr.course_id WHERE cr.user_id = :user_id AND cr.status = 'approved' AND cr.allowed_access = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Se devuelven todos los resultados como una matriz asociativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para crear un curso
    public function createCourse($courseName, $description, $category, $image, $status, $content, $videoLink) {
  
        // Se realiza la consulta SQL para crear un nuevo curso
        $sql = "INSERT INTO courses (course_name, description, category, image, status, content, video_link) VALUES (:course_name, :description, :category, :image, :status, :content, :video_link)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':course_name', $courseName);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':video_link', $videoLink);

        // Se ejecuta la consulta de inserción
        return $stmt->execute();
    }

    // Función para editar un curso
    public function editCourse($courseName, $description, $category, $image, $status, $content, $videoLink, $courseId) {
  
        // Se realiza la consulta SQL para actualizar el curso con todos los parámetros correctos
        $sql = "UPDATE courses SET course_name = :course_name, description = :description, category = :category, image = :image, status = :status, content = :content, video_link = :video_link WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':course_name', $courseName);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':video_link', $videoLink);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);

        // Se ejecuta la consulta de inserción
        return $stmt->execute();
    }

    // Función para eliminar un curso
    public function deleteCourse($courseId) {

        // Se realiza la consulta SQL para eliminar el curso
        $sql = "DELETE FROM courses WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':course_id', $courseId);

        // Se ejecuta la consulta de inserción
        return $stmt->execute();
    }   
}  
?>

