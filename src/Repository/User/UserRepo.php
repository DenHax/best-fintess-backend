<?php

namespace App\Repository\User;

use App\Core\Database;
use App\Domain\User\User;
use PDO;

class UserRepo
{
    private PDO $pdo;
    /**
     * @param array<int,mixed> $db_conn_info
     */
    public function __construct(array $db_conn_info)
    {
        $this->pdo = Database::connect($db_conn_info);
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    public function createUser(User $user): string
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
    /**
     * @param array<int,mixed> $filters
     * @return User[]
     */
    public function getAllUsers(array $filters = []): array
    {
        $query = "
            SELECT 
                c.client_uuid,
                c.client_surname,
                c.client_firstname,
                c.client_gender,
                c.client_age,
                c.client_height,
                c.client_weight,
                c.client_phone,
                c.client_avatar_path,
                EXISTS(
                    SELECT 1 FROM client_role cr
                    JOIN role r ON cr.role_id = r.role_id
                    WHERE cr.client_id = c.client_uuid
                    AND r.role_name = 'тренер'
                ) as is_trainer
            FROM client c
        ";

        $conditions = [];
        $params = [];

        if (isset($filters['is_trainer'])) {
            $isTrainer = filter_var($filters['is_trainer'], FILTER_VALIDATE_BOOLEAN);

            if ($isTrainer) {
                $query .= " WHERE EXISTS(
                SELECT 1 FROM client_role cr
                JOIN role r ON cr.role_id = r.role_id
                WHERE cr.client_id = c.client_uuid
                AND r.role_name = 'тренер'
            )";
            } else {
                $query .= " WHERE NOT EXISTS(
                SELECT 1 FROM client_role cr
                JOIN role r ON cr.role_id = r.role_id
                WHERE cr.client_id = c.client_uuid
                AND r.role_name = 'тренер'
            )";
            }
        }

        if (!empty($filters['gender'])) {
            $conditions[] = "c.client_gender = :gender";
            $params[':gender'] = $filters['gender'];
        }

        if (!empty($filters['age_min'])) {
            $conditions[] = "c.client_age >= :age_min";
            $params[':age_min'] = (int)$filters['age_min'];
        }

        if (!empty($conditions)) {
            $query .= isset($filters['is_trainer']) ? " AND " : " WHERE ";
            $query .= implode(" AND ", $conditions);
        }
        $stmt = $this->pdo->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User(
                $row['client_uuid'],
                $row['client_surname'],
                $row['client_firstname'],
                $row['client_gender'],
                $row['client_age'],
                $row['client_height'],
                $row['client_weight'],
                $row['is_trainer'],
                $row['client_phone'],
                null,
                $row['client_avatar_path']
            );
            $users[] = $user;
        }

        return $users;
    }

}
