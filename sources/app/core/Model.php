<?php

namespace App\Core;

class Model
{
	public static \PDO $pdo;
	private mixed $username;
	private mixed $password;

	private mixed $dbname;

	public string $table;

	private int $fetchMode = \PDO::FETCH_OBJ;

	/**
	 * Model constructor.
	 * @param $table
	 */
	public function __construct($table)
	{
		$this->username = $_ENV['DATABASE_USER'];
		$this->password = $_ENV['DATABASE_PASSWORD'];
		$this->dbname = $_ENV['DATABASE_NAME'];
		$this->table = $table;

		try {
			self::$pdo = new \PDO("mysql:host=mariadb;dbname=$this->dbname", $this->username, $this->password);
			self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			echo 'Connected to database';
		} catch (\PDOException $e) {
			die("Connection failed: " . $e->getMessage());
		}

	}

	/**
	 * Get the PDO object and prepare the sql statement.
	 */
	public function prepare($sql): bool|\PDOStatement
	{
		return self::$pdo->prepare($sql);
	}


	/**
	 * Execute the sql statement with the given parameters
	 */
	public function execute($statement, $params = [])
	{
		$statement->execute($params);
		return $statement;
	}

	/**
	 * Fetch all the records from the database
	 */
	public function fetchAll($statement)
	{
		$statement->setFetchMode($this->fetchMode);
		return $statement->fetchAll();
	}

	/**
	 * Fetch a single record from the database
	 */
	public function fetch($statement)
	{
		$statement->setFetchMode($this->fetchMode);
		return $statement->fetch();
	}

	/**
	 * Insert data into the database according to the given fields in the data array
	 * The data array should have the following format:
	 * [
	 * 'column_name' => 'value',
	 * 'column_name' => 'value',
	 * 'column_name' => 'value',
	 * 'column_name' => 'value',
	 * ]
	 * @param $data
	 * @return string
	 */
	public function insert($data) : string
	{
		// The method should return the id of the inserted row
		$columns = implode(', ', array_keys($data));
		$placeholders = implode(', ', array_map(fn($key) => ":$key", array_keys($data)));
		$sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
		$statement = $this->prepare($sql);
		$this->execute($statement, $data);
		return self::$pdo->lastInsertId();
	}

	/**
	 * Update the row with the given id with the given data
	 * @param array $data
	 * @param int $id
	 * @return int
	 */
	public function update(array $data, int $id) : int
	{
		// The method should return the number of affected rows
		$columns = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
		$sql = "UPDATE $this->table SET $columns WHERE id = :id";
		$statement = $this->prepare($sql);
		$statement->bindParam('id', $id);
		$this->execute($statement, $data);
		return $statement->rowCount();
	}

	/**
	 * Delete the row with the given id
	 * @param int $id
	 * @return int
	 */
	public function delete(int $id) : int
	{
		// The method should return the number of affected rows
		$sql = "DELETE FROM $this->table WHERE id = :id";
		$statement = $this->prepare($sql);
		$statement->bindParam('id', $id);
		$this->execute($statement);
		return $statement->rowCount();
	}

	/**
	 * Find a row with the given id
	 * @param int $id
	 * @return mixed
	 */
	public function findById(int $id): mixed
	{
		// The method should return the row with the given id
		$sql = "SELECT * FROM $this->table WHERE id = :id";
		$statement = $this->prepare($sql);
		$statement->bindParam('id', $id);
		$this->execute($statement);
		return $this->fetch($statement);
	}

	/**
	 * Find all the rows in the table
	 * @return mixed
	 */
	public function findAll(): mixed
	{
		// The method should return all the rows in the table
		$sql = "SELECT * FROM $this->table";
		$statement = $this->prepare($sql);
		$this->execute($statement);
		return $this->fetchAll($statement);
	}



}
