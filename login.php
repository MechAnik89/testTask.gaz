<?php
require_once __DIR__.'/boot.php';

if (check_auth()) {
    header('Location: /');
    die;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .form-container {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1 class="mb-4 text-center">Войти</h1>

    <?php flash(); ?>

    <form method="post" action="do_login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Имя пользователя</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Войти</button>
            <a class="btn btn-outline-primary" href="index.php">Регистрация</a>
        </div>
    </form>
</div>
</body>
</html>
