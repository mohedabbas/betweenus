<?php

namespace App\Core;

use PDO;

/**
 * Abstract Migration Class:
 * - Provides a PDO connection via $this->pdo
 * - Requires each migration to define up() and down() methods
 */
abstract class Migration
{
	protected PDO $pdo;

	// Inject the PDO connection into the migration
	public function setPdo(PDO $pdo): void
	{
		$this->pdo = $pdo;
	}

	/**
	 * Run the migration (e.g. CREATE TABLE, ADD COLUMN, etc.)
	 */
	abstract public function up(): void;

	/**
	 * Roll back the migration (e.g. DROP TABLE, REMOVE COLUMN, etc.)
	 */
	abstract public function down(): void;

	public function __toString(): string
	{
		// TODO: Implement __toString() method.
		return 'Migration';
	}
}
