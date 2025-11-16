<?php
require_once __DIR__.'/boot.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подача заявки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1 class="mb-4 text-center">Добавление заявки</h1>

    <?php flash(); ?>

    <form method="post" action="doAdd.php">
        <div class="mb-3">
            <label for="typeVehLab" class="form-label">Автомобиль</label>
            <input type="text" class="form-control" id="typeVehLab" name="typeVehLab" required>
        </div>
        <div class="mb-3">
            <label for="timeFromLab" class="form-label">Время приезда</label>
            <input type="datetime-local" class="form-control" id="timeFromLab" name="timeFromLab" required>
        </div>
        <div class="d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Отправить</button>
            <a class="btn btn-outline-primary" href="/passes.php">Назад</a>
        </div>
    </form>
</div>

</body>
</html>
