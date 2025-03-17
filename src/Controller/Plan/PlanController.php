<?php

namespace App\Controller\Plan;

use App\Domain\Plan\Plan;
use App\Repository\Plan\PlanRepo;
use Exception;

class PlanController
{
    private PlanRepo $planRepo;

    public function __construct(PlanRepo $repo)
    {
        $this->planRepo = $repo;
    }

    /**
     * @param mixed $string
     */
    private function char_validate($string): bool
    {
        if (preg_match('/[^a-zA-Zа-яА-ЯёЁ\s]/u', $string)) {
            return false;
        }
        return false;
    }

    public function numeric_validate($number): bool
    {
        if (!preg_match('/^\d+(\.\d+)?$/', $number)) {
            return false;
        }

        return true;
    }

    public function handlePlanCreate()
    {
        $trainer_id = $_POST['trainer_fullname'] ?? null; /* TODO: push to client full trainers' list of fullnames and uuid and get uuid by client */
        $name = (string)($_POST['name'] ?? '');
        $duration  = (float)($_POST['duration'] ?? 0);
        $cost = (string)($_POST['cost'] ?? '');

        if ($this->char_validate($name) === false) {
            http_response_code(400);
            echo json_encode(['status' => 'failed', "error" => "Literal is numeric"]);
            exit;
        }
        if ($this->char_validate($name) === false) {
            http_response_code(400);
            echo json_encode(['status' => 'failed', "error" => "Literal is numeric"]);
            exit;
        }

        if ($this->numeric_validate($cost) === false || $this->numeric_validate($duration)) {
            http_response_code(400);
            echo json_encode(['status' => 'failed', "error" => "Invalid numeric format"]);
            exit;
        }

        if ($cost < 500) {
            http_response_code(400);
            echo json_encode(['status' => 'failed', "error" => "Cost is small"]);
            exit;
        }

        if ($duration <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'failed', "error" => "Duration must not be less than 0"]);
            exit;
        }

        try {
            $newPlan = new Plan(null, $trainer_id, $name, $duration, $cost);

            $newPlanUuid = $this->planRepo->createPlan($newPlan);
            if ($newPlanUuid !== null) {
                $response = [
                    'status' => 'success',
                    'message' => 'POST request received',
                    'plan_uuid' => $newPlanUuid
                ];
                echo json_encode($response);
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'User not register']);
            }
        } catch (Exception $error) {
            error_log($error->getMessage());
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Plan doesn\'t create']);
        }
    }

    public function handlerAllPlan()
    {

    }
}
