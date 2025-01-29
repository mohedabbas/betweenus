<?php
namespace App\Core;

class Model
{
    public \PDO $pdo;
    public string $table;
    private int $fetchMode = \PDO::FETCH_OBJ;

    public function __construct(string $table)
    {
        $this->table = $table;

        // Config BDD (adaptez)
        $dbHost = 'mariadb'; 
        $dbName = 'database'; 
        $dbUser = 'user';     
        $dbPass = 'password'; 

        try {
            $this->pdo = new \PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Échec connexion: " . $e->getMessage());
        }
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function execute(\PDOStatement $stmt, array $params = []): \PDOStatement
    {
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(\PDOStatement $stmt)
    {
        $stmt->setFetchMode($this->fetchMode);
        return $stmt->fetch();
    }

    public function fetchAll(\PDOStatement $stmt): array
    {
        $stmt->setFetchMode($this->fetchMode);
        return $stmt->fetchAll();
    }

    public function insert(array $data): string
    {
        $columns      = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, $data);

        return $this->pdo->lastInsertId();
    }
}
