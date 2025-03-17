<?php

namespace App\Repository\Plan;

use App\Core\Database;
use App\Domain\Plan\Plan;
use PDO;

class PlanRepo
{
    private PDO $pdo;

    public function __construct(array $db_conn_info)
    {
        $this->pdo = Database::connect($db_conn_info);
    }

    public function createPlan(Plan $plan): string
    {
        $stmt = $this->pdo->prepare("
          INSERT INTO plan (
              plan_trainer_id,
              plan_name,
              plan_cost,
              plan_duration
          )
          VALUES (
              :plan_trainer_id,
              :plan_name,
              :plan_cost,
              :plan_duration
          )
          RETURNING plan_uuid;
        ");
        $stmt->execute([
              ':plan_trainer_id' => $plan->getTrainerId(),
              ':plan_name' => $plan->getName(),
              ':plan_cost' => $plan->getCost(),
              ':plan_duration' => $plan->getDuration(),
        ]);

        $plan->setUuid($stmt->fetchColumn());

        return $plan->getUuid();
    }

    public function getPlanById()
    {

    }

    public function getAllPlan()
    {

    }
}
