<?php

namespace Repository\User;

use App\Core\Database;
use Domain\User\User;
use PDO;

class UserRepo
{
    private PDO $pdo;

    public function __construct(array $db_conn_info)
    {
        $this->pdo = Database::connect($db_conn_info);
    }
    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    public function addUser(User $user): string
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (
                user_uuid,
                user_surname,
                user_firstname,
                user_age,
                user_height,
                user_weight,
                user_hash_password
            ) VALUES (
                :user_uuid,
                :user_surname,
                :user_firstname,
                :user_age,
                :user_height,
                :user_weight,
                :user_hash_password
            )
        ");

        $stmt->execute([
            ':user_uuid' => $user->getUserUuid(),
            ':user_surname' => $user->getUserSurname(),
            ':user_firstname' => $user->getUserFirstname(),
            ':user_age' => $user->getUserAge(),
            ':user_height' => $user->getUserHeight(),
            ':user_weight' => $user->getUserWeight(),
            ':user_hash_password' => $user->getUserHashPassword(),
        ]);

        return $user->getUserUuid();
    }
}
