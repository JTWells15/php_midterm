<?php
class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST');
        $this->port = getenv('DB_PORT') ?: '5432';
        $this->db_name = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');
    }

    public function getConnection() {
        $this->conn = null;

        try {
            if (!$this->host || !$this->db_name || !$this->username) {
                throw new Exception('Database environment variables are missing.');
            }

            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (Exception $exception) {
            echo json_encode([
                'message' => 'Connection error',
                'error' => $exception->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}
?>