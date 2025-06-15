<?php
class Connection {
    protected $conn;

    // Se crea el constructor de la clase que establece la conexión
    public function __construct() {
        $this->connect();
    }

    // Se crea el método privado para establecer la conexión
    private function connect() {
        $host = 'localhost';
        $port = 3306;
        $dbname = 'format_db';
        $user = 'root';
        $password = '';

        try {
            $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }

    // Método para obtener la conexión
    public function getConnection() {
        return $this->conn;
    }
}
?>

