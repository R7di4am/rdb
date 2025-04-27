<?php
namespace rdb;
use PDO;
use PDOException;
use PDOStatement;
use Exception;
class rdb {
    private PDO $conn;
    private ?PDOStatement $stmt = null;
    private ?array $results = null;  // Store results

    // Constructor to set the PDO connection
    public function __construct(PDO $pdo)
    {
        $this->conn = $pdo;
    }

    // Run the query once and store the result
    public function query(string $query, array $params = []): bool
    {
        $this->stmt = $this->conn->prepare($query);
        if (!$this->stmt) {
            throw new Exception("Prepare failed: " . implode(" ", $this->conn->errorInfo()));
        }

        if (!$this->stmt->execute($params)) {
            throw new Exception("Execute failed: " . implode(" ", $this->stmt->errorInfo()));
        }
        $this->results = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return true;
    }

    // Return the result set (array)
    public function result(): ?array
    {
        return $this->results;
    }

    // Count the number of rows in the result set
    public function count(): int
    {
        return $this->results ? count($this->results) : 0;
    }
    public function affected_rows(): int
    {
        return $this->stmt ? $this->stmt->rowCount() : 0;
    }
    // Fetch all results as an associative array
    public function fetch_all(): ?array
    {
        return $this->results;
    }

    // Fetch a single row as an associative array
    public function fetch_assoc(int $index = 0): ?array
    {
        return $this->results[$index] ?? null;  // Fetch by index
    }

    // Fetch a single row as an object
    public function fetch_object(int $index = 0, string $class = "stdClass"): ?object
    {
        $row = $this->results[$index] ?? null;
        return $row ? (object) $row : null;
    }

    // Begin a transaction
    public function beginTransaction(): void
    {
        $this->conn->beginTransaction();
    }

    // Commit a transaction
    public function commit(): void
    {
        $this->conn->commit();
    }

    // Rollback a transaction
    public function rollback(): void
    {
        $this->conn->rollBack();
    }

    // Destructor (cleanup)
    public function __destruct()
    {
        $this->results = null;
    }
}
