<?php

namespace App\Repository\User;

use App\Core\Database;
use App\Domain\User\User;
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
            INSERT INTO client (
                client_surname,
                client_firstname,
                client_gender,
                client_age,
                client_height,
                client_weight,
                client_phone,
                client_hash_password,
                client_avatar_path
            ) VALUES (
                :client_surname,
                :client_firstname,
                :client_gender,
                :client_age,
                :client_height,
                :client_weight,
                :client_phone,
                :client_hash_password,
                :client_avatar_path
            )
            RETURNING client_uuid;
        ");

        $stmt->execute([
                ':client_surname' => $user->getUserSurname(),
                ':client_firstname' => $user->getUserFirstname(),
                ':client_gender' => $user->getUserGender(),
                ':client_age' => $user->getUserAge(),
                ':client_height' => $user->getUserHeight(),
                ':client_weight' => $user->getUserWeight(),
                ':client_phone' => $user->getUserPhone(),
                ':client_hash_password' => $user->getUserHashPassword(),
                ':client_avatar_path' => $user->getUserAvatarPath(),
        ]);
        $user->setUserUuid($stmt->fetchColumn());
        error_log($user->getUserUuid());

        if ($user->getUserIsTrainer() !== false) {
            $trainer_role_id = 1;
            $stmt = $this->pdo->prepare("
              INSERT INTO client_role (
                client_id,
                role_id
              ) VALUES (
                :client_uuid,
                :role_id
              )
            ");

            $stmt->execute([
              ':client_uuid' => $user->getUserUuid(),
              ':role_id' => $trainer_role_id,
            ]);
        }

        return $user->getUserUuid();
    }
}
