<?php
require_once __DIR__.'/boot.php';
$id = $_GET['id'] ?? 0;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Изменение пользователя</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
        }
        .card {
            border-radius: 12px;
            padding: 25px;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center mt-5">
    <div class="col-lg-5">

        <div class="card shadow">
            <h2 class="mb-4 text-center">Обновление данных</h2>

            <?php flash(); ?>

            <form method="post" action="doUpdUsers.php?id=<?= htmlspecialchars($id) ?>">

                <div class="mb-3">
                    <label for="username" class="form-label">Имя пользователя</label>
                    <input
                            type="text"
                            class="form-control"
                            id="username"
                            name="username"
                            placeholder="Введите новое имя"
                            required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Введите новый пароль"
                            required>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="admFlag" name="admFlag">
                    <label class="form-check-label" for="admFlag">Администратор</label>
                </div>

                <div class="d-flex justify-content-between">
                    <a class="btn btn-outline-secondary" href="users.php">Назад</a>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>

            </form>
        </div>

    </div>
</div>

</body>
</html>
