<?php

header('Content-Type: application/json');

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'GET') {
    $csvFile = __DIR__ .  './static/train.csv';

    if (!file_exists($csvFile)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'CSV file not found']);
        exit;
    }

    $file = fopen($csvFile, 'r');

    $data = [];

    while (($row = fgetcsv($file, 1000, ',')) !== false) {
        $data[] = [
            'name' => $row[0],
            'email' => $row[1]
        ];
    }

    fclose($file);

    echo json_encode($data);

} elseif ($requestMethod === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;

        $csvFile = __DIR__ . '/static/train.csv';

        $dataRow = [$name, $email];

        if (($file = fopen($csvFile, 'a')) !== false) {
            fputcsv($file, $dataRow);
            fclose($file);
            $response = [
                'status' => 'success',
                'message' => 'POST request received',
            ];
            echo json_encode($response);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
}
