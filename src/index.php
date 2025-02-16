<?php

header('Content-Type: application/json');

/** Get file that will be upload to /upload/:uploadType directory with random filename and return string to this file */
function uploadFile($file, $uploadType): string | null
{
    $uploadPath = __DIR__ . '../upload';
    if (!empty($file)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $filename = $uploadType . time() . ".$ext";
        $path = "$uploadPath/$uploadType/$filename";
        if (!move_uploaded_file($file['tmp_name'], $path)) {
            die('Error to upload file on server');
        }
        return $path;
    } else {
        return null;
    }
}

/** Return all users from csv file in /data/user if this file exist */
function getUsers()
{
    $fileDBPath = __DIR__ . '../data';
    $userFile = $fileDBPath . '/user.csv';
    if (!file_exists($userFile)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'CSV file not found']);
        exit;
    }

    $file = fopen($userFile, 'r');

    $data = [];
    while (($row = fgetcsv($file, 1000, ',')) !== false) {
        $data[] = [
          "firstname" => $row[0],
          "surname" => $row[1],
          "email" => $row[2],
          "pasword" => $row[3],
          "isTrainer" => $row[4],
        ];
    }
    fclose($file);
}

/** Create file /data/user.csv and put into this file data about current register user */
function registerCSV($dataRow)
{
    $fileDBPath = __DIR__ . '../data';
    $userFile = $fileDBPath . '/user.csv';
    if (($file = fopen($userFile, 'a')) !== false) {
        fputcsv($file, $dataRow);
        fclose($file);
    }
}

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'GET') {
    $usersData = getUsers();

    echo json_encode($usersData);

} elseif ($requestMethod === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $surname = $data['surname'] ?? null;
        $firstname = $data['firstname'] ?? null;
        $avatar = $data['avatar'] ?? null;
        $weight = $data['weight'] ?? null;
        $height = $data['height'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $isTrainer = $data['isTrainer'] ?? null;

        $avatarPath = uploadFile($avatar, 'avatar');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $dataRow = [$surname, $firstname, $weight, $height, $email, $hashedPassword, $isTrainer ? 'true' : 'false', $avatarPath];

        $registerStatus = registerCSV($dataRow);

        $response = [
            'status' => 'success',
            'message' => 'POST request received',
        ];
        echo json_encode($response);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
}
