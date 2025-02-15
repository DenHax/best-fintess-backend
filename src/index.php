<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');

  $csvFile = "data.csv";

  $dataRow = [$name, $email];

  if (($file = fopen($csvFile, 'a')) !== false) {
    fputcsv($file, $dataRow);
    fclose($file);
    $message = 'Данные успешно сохранены';
  }
  else {
    $message = 'Ошибка при сохранении';
  }
}
?>


  <form action="" method="POST">
    <label for="name">Имя: </label>
    <input type="text" name="name" id="name">
}
