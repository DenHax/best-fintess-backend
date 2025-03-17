<?php

namespace App\Controller\User;

use App\Core\Request;
use App\Helpers\ContentLoader;
use App\Domain\User\User;
use App\Repository\User\UserRepo;
use Exception;

class UserController
{
    private UserRepo $userRepo;

    public function __construct(UserRepo $repo)
    {
        $this->userRepo = $repo;
    }

    private function char_validate($string): bool
    {
        if (preg_match('/[^a-zA-Zа-яА-ЯёЁ\s]/u', $string)) {
            return true;
        }
        return false;
    }

    public function handleUserRegistration()
    {
        /*$userData = $request->getBodyParams();*/
        $surname = $_POST['surname'] ?? '';
        $firstname = $_POST['firstname'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $age = (int)($_POST['age'] ?? 0);
        $weight = (int)($_POST['weight'] ?? 0);
        $height = (int)($_POST['height'] ?? 0);
        $is_trainer = $_POST['isTrainer'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $repeat_password = $_POST['repeat_password'] ?? '';

        if ($this->char_validate($surname) || $this->char_validate($firstname)) {
            http_response_code(400);
            error_log("has numeric");
            echo json_encode(['status' => 'failed', "error" => "Literal is numeric"]);
            exit;
        }

        if ($age < 8 || $age > 120) {
            http_response_code(400);
            error_log("has numeric");
            echo json_encode(['status' => 'failed', "error" => "Incorrect age"]);
            exit;
        }

        if ($weight < 20 || $weight > 300) {
            http_response_code(400);
            error_log("incorrect ");
            echo json_encode(['status' => 'failed', "error" => "Incorrect weight"]);
            exit;
        }

        if ($height < 100 || $height > 280) {
            http_response_code(400);
            echo json_encode(['status' => 'failed', "error" => "Incorrect height"]);
            exit;
        }

        if ($phone[0] === "+" && $phone[0] === "7" || $phone[1] === "8") {
            $checkedPhoneLen = strlen(str_replace('+', '', $phone));
            if ($checkedPhoneLen !== 11) {
                http_response_code(400);
                echo json_encode(['status' => 'failed', "error" => "Incorrect phone"]);
                exit;
            }
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/', $password)) {
            http_response_code(400);
            echo json_encode(['status' => 'failed', "error" => "Password don't strong"]);
            exit;
        }

        if ($password !== $repeat_password) {
            http_response_code(400);
            echo json_encode(['status' => 'failed', "error" => "Passwords don't match"]);
            exit;
        }

        $is_trainer = $is_trainer === 'true' ? true : false;

        $avatar_path = '';
        if (isset($_FILES['avatar'])) {
            error_log("avatar exist");
            $avatar = $_FILES['avatar'];
            if ($avatar['error'] !== UPLOAD_ERR_OK) {
                http_response_code(415);
                echo json_encode(['error' => 'File upload error.']);
                exit;
            }

            $fileType = mime_content_type($avatar['tmp_name']);
            if ($fileType !== 'image/jpeg' && $fileType !== 'image/png') {
                http_response_code(415);
                echo json_encode(['error' => 'Only JPG and PNG files are allowed.']);
                exit;
            }
        } else {
            echo json_encode(['message' => 'No file uploaded.']);
        }

        $avatar_path = ContentLoader::uploadImage($avatar, 'avatar');

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $newUser = new User(
                null,
                $surname,
                $firstname,
                $gender,
                $age,
                $height,
                $weight,
                $is_trainer,
                $phone,
                $hashedPassword,
                $avatar_path
            );

            $newUserUuid = $this->userRepo->createUser($newUser);

            if ($newUserUuid !== null) {
                $response = [
                    'status' => 'success',
                    'message' => 'POST request received',
                    'user_uuid' => $newUserUuid
                ];
                echo json_encode($response);
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'User not register']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            error_log($e);
            echo json_encode(['status' => 'error', 'message' => 'User not register']);
        }
    }

    public function handleAllUsers(Request $request)
    {
        $filters = $request->getQueryParams();
        try {
            $users = $this->userRepo->getAllUsers($filters);
            $usersArray = array_map(function (User $user) {
                return [
                    'user_uuid' => $user->getUserUuid(),
                    'user_surname' => $user->getUserSurname(),
                    'user_firstname' => $user->getUserFirstname(),
                    'user_gender' => $user->getUserGender(),
                    'user_age' => $user->getUserAge(),
                    'user_height' => $user->getUserHeight(),
                    'user_weight' => $user->getUserWeight(),
                    'user_phone' => $user->getUserPhone(),
                    'user_avatar_path' => 'storage/' .$user->getUserAvatarPath(),
                    'is_trainer' => $user->getUserIsTrainer() === true ? '✔' : '✖',
                ];
            }, $users);

            header('Content-Type: application/json');

            echo json_encode($usersArray);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        /*echo json_encode($usersData, 0, 2);*/
    }
}
