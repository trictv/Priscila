<?php

/**
 * Database class to handle connections using PDO.
 *
 * Implements the Singleton pattern to ensure only one instance of the
 * database connection is created.
 */
class Database
{
    private static $instance = null;
    private $pdo;
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $charset = DB_CHARSET;

    /**
     * Private constructor to prevent direct creation of object.
     */
    private function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            // In a real application, you'd log this error and show a generic message.
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Gets the single instance of the Database class.
     *
     * @return Database The single instance of the Database class.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            // Include config only when the instance is first created
            if (!defined('DB_HOST')) {
                require_once __DIR__ . '/config.php';
            }
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Gets the PDO connection object.
     *
     * @return PDO The PDO instance.
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * A simple query execution method.
     * For more complex queries, you might want to create separate methods for
     * SELECT, INSERT, UPDATE, DELETE with prepared statements.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return PDOStatement The PDOStatement object.
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
