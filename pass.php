<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/boot.php';

// Создаем соединение с базой данных
try {
    $pdo = pdo();
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}

// Получаем значение id из параметра GET
$id = $_GET['id'];

// Выполняем SQL-запрос для получения данных о конкретном пропуске
$sql = "SELECT passes.*, users.username 
        FROM passes 
        INNER JOIN users ON passes.idUsers = users.id
        WHERE passes.id = :id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Ошибка выполнения запроса: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пропуск</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div id="printableArea">
<div class="container">
    <h1>Пропуск #<?= $row['id'] ?></h1>
    <table class="table table-bordered">
        <tr>
            <th>Номер пропуска</th>
            <td><?= $row['id'] ?></td>
        </tr>
        <tr>
            <th>Имя пользователя</th>
            <td><?= htmlspecialchars($row['username']) ?></td>
        </tr>
        <tr>
            <th>Тип автомобиля</th>
            <td><?= htmlspecialchars($row['typeVehLab']) ?></td>
        </tr>
        <tr>
            <th>Дата и время приезда</th>
            <td><?= $row['timeFromLab'] ?></td>
        </tr>
        <tr>
            <th>Дата и время завершения</th>
            <td><?= $row['timeToLab'] ?: 'Нет' ?></td>
        </tr>
        <tr>
            <th>Состояние пропуска</th>
            <td><?= $row['pass'] ? 'Одобрен' : 'Не одобрен' ?></td>
        </tr>
    </table>
</div>
</div>
<div class="d-flex justify-content-center gap-2 mt-3">
    <a class="btn btn-primary" href="passes.php">Вернуться к списку пропусков</a>
    <button class="btn btn-success btn-print" onclick="window.print()">Печать</button>
</div>


</body>
</html>
