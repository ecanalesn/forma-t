<?php

// Se incluye la clase Connection
require_once 'Connection.php';

class User extends Connection {
    // Constructor de la clase, hereda la conexión de la clase Connection
    public function __construct() {
        parent::__construct();  // Llama al constructor de la clase padre (Connection)
    }

    // Función para autenticar al usuario
    public function login($email, $password) {
        
        // Se realiza la consulta SQL para comprobar si el nombre de usuario existe en la base de datos
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Se obtienen los datos del usuario
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se comprueba si el usuario existe y si la contraseña es correcta
        if ($user && hash('sha256', $password) === $user['password']) {
            return $user;
        } else {
            return null;
        }
    }

     // Función para registrar un nuevo usuario
     public function register($firstName, $lastName, $email, $password) {
        
        // Se asigna el rol como "student" automáticamente
        $role = 'student';

        // Se encripta la contraseña con SHA-256
        $hashedPassword = hash('sha256', $password);

        // Se realiza la consulta SQL para registrar al usuario
        $sql = "INSERT INTO users (first_name, last_name, email, password, role) VALUES (:first_name, :last_name, :email, :password, :role)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);

        // Se ejecuta la consulta de inserción
        return $stmt->execute();
    }

    // Función para comprobar si el correo ya se encuentra registrado
    public function getByEmail ($email) {
        
        // Se realiza la consulta SQL para verificar el correo
        $sql = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    // Función para obtener un usuario por ID
    public function getByUserId ($userId) {
        
        // Se realiza la consulta SQL para obtener los datos
        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        
        // Se ejecuta la consulta de inserción
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Función para obtener todos los usuarios
    public function getAllUsers() {

        // Se realiza la consulta SQL para obtener todos los usuarios
        $sql = "SELECT * FROM users"; 
        $stmt = $this->conn->prepare($sql);

        // Se ejecuta la consulta de inserción
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Función para editar el perfil de un usuario
    public function editProfile($firstName, $lastName, $userId, $phone, $password) {
        // Si la contraseña es proporcionada, se encripta y actualiza
        if (!empty($password)) {
            // Se encripta la contraseña con SHA-256
            $hashedPassword = hash('sha256', $password);

            // Se realiza la consulta para actualizar todos los campos
            $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, phone = :phone, password = :password WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        } else {
            // Si no hay contraseña nueva, no se actualiza el campo de la contraseña
            $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, phone = :phone WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':phone', $phone);

        // Se ejecuta la consulta de actualización
        return $stmt->execute();
    }

    // Función para crear un usuario con rol y estado específicos
    public function createUser($firstName, $lastName, $email, $role, $status, $password) {
  
        // Se encripta la contraseña con SHA-256
        $hashedPassword = hash('sha256', $password);

        // Se realiza la consulta SQL para crear el usuario
        $sql = "INSERT INTO users (first_name, last_name, email, role, status, password) VALUES (:first_name, :last_name, :email, :role, :status, :password)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':password', $hashedPassword);

         // Se ejecuta la consulta de inserción
         return $stmt->execute();
    }

    // Función para editar un usuario
    public function editUser($firstName, $lastName, $email, $role, $status, $userId) {
  
        // Se realiza la consulta SQL para editar el usuario
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, role = :role, status = :status WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':user_id', $userId);

         // Se ejecuta la consulta de inserción
         return $stmt->execute();
    }

    // Función para eliminar un usuario
    public function deleteUser($userId) {
  
        // Se realiza la consulta para eliminar el usuario
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        
        // Se ejecuta la consulta de inserción
        return $stmt->execute();
    }   
}  
?>

