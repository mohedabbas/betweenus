<?php
use App\Core\Migration;


return new class extends Migration
{
	public function up(): void
	{
		// 1. Create the 'users' table (with a few extra fields for robustness)
		$sql = "
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(255) NOT NULL UNIQUE,
            `email` VARCHAR(255) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `first_name` VARCHAR(100) NOT NULL,
            `last_name` VARCHAR(100) NOT NULL,
            `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

		// Execute the table creation
		$this->pdo->exec($sql);

		// 2. Insert an initial admin user
		// You can change the username, email, password, etc. as desired
		$insert = "
        INSERT INTO `users` 
            (username, email, password, first_name, last_name, is_admin)
        VALUES
            (:username, :email, :password, :first_name, :last_name, :is_admin)
    ";

		$stmt = $this->pdo->prepare($insert);

		// We’ll hash the password for security
		$hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);

		$stmt->execute([
			'username'   => 'admin',
			'email'      => 'admin@example.com',
			'password'   => $hashedPassword,
			'first_name' => 'Admin',
			'last_name'  => 'User',
			'is_admin'   => 1
		]);
	}

	public function down(): void
	{
		$this->pdo->exec("DROP TABLE users");
	}
};
