<?php
namespace App\Models;

use App\Core\Model;

class AuthModel extends Model
{
    public function __construct()
    {
        parent::__construct('users');
    }

    public function createUser(array $data): void
    {
        $this->insert($data);
    }

    // Pour la connexion (username ou email)
    public function findUserByUsernameOrEmail(string $identifier)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE username = :identifier
                   OR email    = :identifier
                LIMIT 1";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, ['identifier' => $identifier]);
        return $this->fetch($stmt);
    }

    // Code 6 chiffres => verification
    public function findByVerificationCode(string $code)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE verification_code = :code
                  AND is_verified = 0
                LIMIT 1";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, ['code' => $code]);
        return $this->fetch($stmt);
    }

    public function verifyUser(int $id): void
    {
        $sql = "UPDATE {$this->table}
                SET is_verified = 1,
                    verification_code = NULL
                WHERE id = :id";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, ['id' => $id]);
    }

    // Mot de passe oublié => reset_token
    public function findUserByEmail(string $email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, ['email' => $email]);
        return $this->fetch($stmt);
    }

    public function findUserById(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, ['id' => $id]);
        return $this->fetch($stmt);
    }

    public function setResetToken(int $userId, string $token): void
    {
        $sql = "UPDATE {$this->table}
                SET reset_token = :token
                WHERE id = :id";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, [
            'token' => $token,
            'id'    => $userId
        ]);
    }

    public function findByResetToken(string $token)
    {
        $sql = "SELECT * FROM {$this->table} WHERE reset_token = :token LIMIT 1";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, ['token' => $token]);
        return $this->fetch($stmt);
    }

    public function updatePasswordAndClearToken(int $id, string $hashedPassword): void
    {
        $sql = "UPDATE {$this->table}
                SET password = :pwd,
                    reset_token = NULL
                WHERE id = :id";
        $stmt = $this->prepare($sql);
        $this->execute($stmt, [
            'pwd' => $hashedPassword,
            'id'  => $id
        ]);
    }
}
