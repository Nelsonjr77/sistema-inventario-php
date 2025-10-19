<?php
/**
 * Configuración de Base de Datos
 * Archivo de conexión para el sistema de inventario
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_inventario');
define('DB_USER', 'root');
define('DB_PASS', ''); // Contraseña por defecto de XAMPP está vacía
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
// Detectar la URL base automáticamente
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptPath = dirname(dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = $protocol . '://' . $host . $scriptPath;

define('BASE_URL', $baseUrl);
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');

// Clase de conexión a la base de datos
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    // Prevenir clonación del objeto
    private function __clone() {}

    // Prevenir deserialización del objeto
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Función helper para obtener la conexión
function getDB() {
    return Database::getInstance()->getConnection();
}

// Función para respuestas JSON
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Función para sanitizar inputs
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
