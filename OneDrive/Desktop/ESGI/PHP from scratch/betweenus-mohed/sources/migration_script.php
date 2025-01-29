<?php

use App\Core\Model;
use App\Core\Migration;

require __DIR__ . '/app/core/Model.php';
require __DIR__ . '/app/core/Migration.php';


class MigrationManager extends Model
{
	private string $migrationPath = __DIR__ . '/migrations';

	public function __construct($tableName)
	{
		parent::__construct($tableName);
	}

	/**
	 * @throws Exception
	 */
	public function run(string $command): void
	{
		if ($command === 'up') {
			$this->migrateUp();
		} else if ($command === 'down') {
			$this->migrateDown();
		} else {
			echo "Command not found \n";
		}
	}

	/**
	 * @throws Exception
	 */
	private function migrateUp(): void
	{
		$this->createMigrationsTable();
		// All .php files in /database/migrations
		$allFiles = $this->getAllMigrationFiles();
		// Already migrated
		$migratedFiles = $this->getMigratedFiles();
		// Files we still need to run
		$pendingFiles = array_diff($allFiles, $migratedFiles);

		if (empty($pendingFiles)) {
			echo "No new migrations to run.\n";
			return;
		}

		sort($pendingFiles); // ensure ascending (timestamp-based) order
		$batch = $this->getNextBatchNumber();

		foreach ($pendingFiles as $file) {
			echo "Migrating: $file\n";
			$migrationInstance = $this->requireMigration($file);
			$migrationInstance->up();

			// record in DB
			$this->recordMigration($file, $batch);
			echo "Migrated: $file\n";
		}
	}

	/**
	 * @throws Exception
	 */
	private function migrateDown(): void
	{
		$this->createMigrationsTable();

		$lastBatch = $this->getLastBatchNumber();
		if ($lastBatch < 1) {
			echo "No migrations to rollback.\n";
			return;
		}
		// get all migrations in that batch
		$migrationsInBatch = $this->getMigrationsByBatch($lastBatch);
		// reverse order so rollback is symmetrical
		rsort($migrationsInBatch);

		foreach ($migrationsInBatch as $file) {
			echo "Rolling back: $file\n";
			$migrationInstance = $this->requireMigration($file);
			$migrationInstance->down();

			$this->removeMigrationRecord($file);
			echo "Rolled back: $file\n";
		}
		echo "Batch $lastBatch rolled back.\n";
	}
	private function createMigrationsTable(): void
	{
		$sql = <<<SQL
        CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `migration` VARCHAR(255) NOT NULL,
            `batch` INT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        SQL;
		$this->pdo->exec($sql);
	}

	private function getAllMigrationFiles(): array
	{
		$files = glob($this->migrationPath . '/*.php');
		return array_map('basename', $files); // only file name, not full path
	}

	private function getMigratedFiles(): array
	{
		$sql = "SELECT migration FROM migrations";
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	private function getMigrationsByBatch(int $batch): array
	{
		$stmt = $this->pdo->prepare("SELECT migration FROM migrations WHERE batch = :batch ORDER BY migration DESC");
		$stmt->execute(['batch' => $batch]);
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	private function recordMigration(string $filename, int $batch): void
	{
		$stmt = $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)");
		$stmt->execute([
			'migration' => $filename,
			'batch' => $batch
		]);
	}

	private function removeMigrationRecord(string $filename): void
	{
		$stmt = $this->pdo->prepare("DELETE FROM migrations WHERE migration = :migration");
		$stmt->execute(['migration' => $filename]);
	}

	private function requireMigration(string $filename): Migration
	{
		$fullPath = $this->migrationPath . '/' . $filename;
		// require returns the instance from the file (the anonymous class)
		$instance = require $fullPath;

		// check if the instance is a Migration
		if (!$instance instanceof Migration) {
			throw new Exception("Invalid migration $filename");
		}

		$instance->setPdo($this->pdo);

		// pass PDO to the newly created Migration instance
		return $instance;
	}

	private function getLastBatchNumber(): int
	{
		$stmt = $this->pdo->query("SELECT MAX(batch) FROM migrations");
		return (int)$stmt->fetchColumn();
	}

	private function getNextBatchNumber(): int
	{
		return $this->getLastBatchNumber() + 1;
	}

}


// CLI entry POINT
// Run the migration manager

if (php_sapi_name() === 'cli') {
	$command = $argv[1];

	if (!in_array($command, ['up', 'down'])) {
		echo "Invalid command. Use 'up' or 'down' \n";
		exit(1);
	}
	$migrationManager = new MigrationManager('migrations');
	$migrationManager->run($command);
}
