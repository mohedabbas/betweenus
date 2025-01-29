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
        // Insertion dans la table `users`
        $this->insert($data);
    }

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
}
