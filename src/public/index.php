<?php

require_once __DIR__ . "/../vendor/autoload.php";

$q = $_GET['q'];
$qstr = $q . " str";
$params = explode("/", $q);
/** HTTP-method http://www.domain.host:port/api/:domainName/:id */
$domainName = $params[1];
/*$id = $params[2];*/

function char_validate($string)
{
    if (preg_match('/[^a-zA-Zа-яА-ЯёЁ\s]/u', $string)) {
        return true;
    }
    return false;
}

function handleUserWithAvatar()
{
    $surname = $_POST['surname'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $weight = $_POST['weight'] ?? 0;
    $height = $_POST['height'] ?? 0;
    $is_trainer = $_POST['isTrainer'] ?? false;
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $repeat_password = $_POST['repeat_password'] ?? '';

    if (char_validate($surname) || char_validate($firstname)) {
        http_response_code(400);
        echo json_encode(['status' => 'failed', "error" => "Literal is numeric"]);
        exit;
    }

    if ($age < 8 || $age > 120) {
        http_response_code(400);
        echo json_encode(['status' => 'failed', "error" => "Incorrect age"]);
        exit;
    }

    if ($weight < 20 || $weight > 300) {
        http_response_code(400);
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

    $avatar_path = uploadImage($avatar, 'avatar');
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $dataRow = [$surname, $firstname, $age, $gender, $weight, $height, $phone, $hashedPassword, $is_trainer, $avatar_path];
    $registerStatus = registerCSV($dataRow);

    if ($registerStatus) {
        $response = [
            'status' => 'success',
            'message' => 'POST request received',
        ];
        echo json_encode($response);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'User not register']);
    }
}

/** Get file that will be upload to /upload/:uploadType directory with random filename and return string to this file */
function uploadImage($avatar, $uploadType): string | null
{
    $uploadPath = '../storage/upload/';
    if (!empty($avatar)) {
        $fileTmpPath = $_FILES[$uploadType]['tmp_name'];
        $fileName = $_FILES[$uploadType]['name'];
        $fileSize = $_FILES[$uploadType]['size'];
        $fileType = $_FILES[$uploadType]['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($fileExtension, $allowedfileExtensions) && in_array($fileType, $allowedMimeTypes)) {
            $newFileName = uniqid() . '.' . $fileExtension;
            $path = $uploadPath . '/' . $uploadType . '/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $path)) {
                return $path;
            } else {
                return null;
            }
        }
    } else {
        $defaultPath = $uploadPath . '/' . $uploadType . '/' .'default.jpg';
        return $defaultPath;
    }
}

/** Return all users from csv file in /data/user if this file exist */
function getUsers()
{
    $fileDBPath =  '../storage/db';
    $userFile = $fileDBPath . '/user.csv';

    if (!file_exists($userFile)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'CSV file not found']);
        exit;
    }

    $data = [];

    if (($handle = fopen($userFile, 'r')) !== false) {
        $headers = fgetcsv($handle, 1000, ',');

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($handle);
    }
    return $data;
}

/** Create file /data/user.csv and put into this file data about current register user */
function registerCSV($dataRow)
{
    $fileDBPath = __DIR__ . '/storage/db';
    $userFile = $fileDBPath . '/user.csv';

    if (($avatar = fopen($userFile, 'a')) !== false) {
        fputcsv($avatar, $dataRow);
        fclose($avatar);
        $registerStatus = true;
    } else {
        $registerStatus = false;
    }
    return $registerStatus;
}

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($domainName) {
    case 'user':
        {
            error_log("user method");
            switch ($requestMethod) {
                case 'GET':
                    {
                        $usersData = getUsers();
                        echo json_encode($usersData, 0, 2);
                    }
                    break;
                case 'POST':
                    $response = [];
                    if (strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false) {
                        handleUserWithAvatar();
                    } elseif (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                        $input = json_decode(file_get_contents('php://input'), true);
                        handleUserWithoutAvatar($input);
                    }
                    break;
                default:
                    http_response_code(405);
                    error_log('error to method');
                    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
                    break;
            }
            break;
        }
    case 'plan':
        {
            switch ($requestMethod) {
                case 'value':

                    break;

                default:
                    http_response_code(405);
                    error_log('error to method');
                    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
                    break;
            }
            break;
        }

    default:
        http_response_code(405);
        error_log('error to method');
        echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        break;
}
