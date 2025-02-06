<?php
use App\Core\Migration;


return new class extends Migration
{
	public function up(): void
	{
		// 1. Create the 'users' table (with a few extra fields for robustness)
		$sql_old = "
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

		$sql = "
		
 CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            profile_image VARCHAR(255) DEFAULT 'default.png',
            role ENUM('user', 'admin') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            verification_code VARCHAR(255) DEFAULT NULL,
            is_verified TINYINT(1) NOT NULL DEFAULT 0,
            reset_token VARCHAR(255) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE galleries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE gallery_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT,
    user_id INT,
    can_upload BOOLEAN DEFAULT 0,
    can_view BOOLEAN DEFAULT 1,
    FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    gallery_id INT,
    image_path VARCHAR(255) NOT NULL,
    caption TEXT,
    is_public BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE
);
		";

		// Execute the table creation
		$this->pdo->exec($sql);

		// 2. Insert an initial admin user
		// You can change the username, email, password, etc. as desired
		$insert = " INSERT INTO users (first_name,last_name,username,email,password,profile_image,role) 
		VALUES (:first_name,:last_name,:username,:email,:password,:profile_image,:role) ";

		$stmt = $this->pdo->prepare($insert);

		// We’ll hash the password for security
		$hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);

		$stmt->execute([
			'username'   => 'admin',
			'email'      => 'admin@betweenus.com',
			'password'   => $hashedPassword,
			'first_name' => 'Admin',
			'last_name'  => 'User',
			'profile_image' => 'default.jpg',
			'role'       => 'admin'
		]);
	}

	public function down(): void
	{
		$this->pdo->exec("DROP TABLE users");
	}
};
