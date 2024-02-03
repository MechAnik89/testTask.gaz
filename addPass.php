<?php
require_once __DIR__.'/boot.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Подача заявки</title>
</head>
<body>

<?php flash(); ?>
<div class="container">
    <h1>Добавление заявки</h1>
<form method="post" action="doAdd.php">
    <div class="mb-3">
        <label for="typeVehLab" class="form-label">Автомобиль</label>
        <input type="text" class="form-control" id="typeVehLab" name="typeVehLab" required>
    </div>
    <div class="mb-3">
        <label for="timeFromLab" class="form-label">Время приезда</label>
        <input type="datetime-local" class="form-control" id="timeFromLab" name="timeFromLab" required>
    </div>
    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Отправить</button>
        <a class="btn btn-outline-primary" href="/passes.php">Назад</a>
    </div>
</form>
</div>
</body>
</html>
