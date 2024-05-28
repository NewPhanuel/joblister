<?php
declare(strict_types=1);

class Database
{
    public PDO $conn;


    /**
     * Constructor for the database class
     *
     * @param array $config
     * @throws PDOException
     */
    public function __construct(array $config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ];

        try {
            $this->conn = new PDO($dsn, $config["username"], $config["password"], $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: {$e->getMessage()}");
        }
    }


    public function query(string $query, array $params = [])
    {
        try {
            $statement = $this->conn->prepare($query);

            // Bind Params
            foreach ($params as $param => $value) {
                $statement->bindValue(':' . $param, $value);
            }

            $statement->execute();
            return $statement;
        } catch (PDOException $e) {
            throw new Exception("Query failed to execute: {$e->getMessage()}");
        }
    }
}