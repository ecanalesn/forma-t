<?php

// Se incluye la clase Connection
require_once 'Connection.php';

class CourseRequests extends Connection {
    // Constructor de la clase, hereda la conexión de la clase Connection
    public function __construct() {
        parent::__construct();  // Llama al constructor de la clase padre (Connection)
    }

    // Función para consultar las solicitudes procesadas obteniendo el id del usuario
    public function getProcessedRequestsByUser($userId) {
  
        // Se realiza la consulta SQL para obtener las solicitudes aceptadas o rechazadas
        $sql = "SELECT request_id, status, request_date, is_read FROM course_requests WHERE user_id = :user_id AND status IN ('approved', 'rejected') ORDER BY request_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        // Se ejecuta la consulta de inserción
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

       // Función para consultar todas las solicitudes
       public function getAllRequests() {
  
        // Se realiza la consulta SQL para obtener todas las solicitudes
        $sql = "SELECT r.*, c.course_name, u.first_name, u.last_name FROM course_requests r JOIN courses c ON r.course_id = c.course_id JOIN users u ON r.user_id = u.user_id";
        $stmt = $this->conn->prepare($sql);

        // Se ejecuta la consulta de inserción
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para leer las solicitudes
    public function readRequest($requestId) {
  
        // Se realiza la consulta SQL para actualizar la notificación a leída
        $sql = "UPDATE course_requests SET is_read = 1 WHERE request_id = :request_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
        $stmt->execute();

        // Se ejecuta la consulta de inserción
        return $stmt->execute();
    }

    // Función para obtener las solicitudes pendientes
    public function getPendingCount() {

        // Se realiza la consulta SQL para obtener el número de solicitudes pendientes
        $query = "SELECT COUNT(*) AS pending_count FROM course_requests WHERE status = 'pending'";
        $stmt = $this->conn->prepare($query);
  
        // Se ejecuta la consulta de inserción
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['pending_count'] ?? 0;
    } 

    // Función para obtener el ID del usuario y curso
    public function getUserRequestCourse($userId, $courseId) {
        // Se realiza la consulta SQL para comprobar si se ha solicitado el curso anteriormente
        $sql = "SELECT request_id, status, allowed_access FROM course_requests WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        // Se ejecuta la consulta de inserción
        $stmt->execute();
        // Se ejecuta la consulta de inserción
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Función para crear una matrícula a un curso
    public function createEnrollment($userId, $courseId) {
        
        // Se realiza la consulta SQL para crear la solicitud de matrícula
        $sql = "INSERT INTO course_requests (user_id, course_id, status) VALUES (:user_id, :course_id, 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
    
        // Se ejecuta la consulta de inserción
        return $stmt->execute();
    }

    // Función para cambiar el estado de la solicitud de la matrícula
    public function changeStatus($status, $requestId) {

        // Se realiza la consulta SQL para cambiar el estado de la matrícula
        $sql = "UPDATE course_requests SET status = :status";

        // Si la solicitud es aprobada, también se actualizará allowed_access a TRUE
        if ($status === 'approved') {
            $sql .= ", allowed_access = TRUE";
        }

        // Se añade la condición para la solicitud específica
        $sql .= " WHERE request_id = :request_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
        $stmt->execute(['status' => $status, 'request_id' => $requestId]);

        // Se ejecuta la consulta de inserción
        return $stmt->execute();
    }
}  
?>