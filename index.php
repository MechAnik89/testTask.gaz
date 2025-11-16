<?php
require_once __DIR__.'/boot.php';

$user = null;
$admFlag = false;

if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $admFlag = !empty($user['admFlag']);
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пропуска</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .menu-container {
            width: 100%;
            max-width: 400px;
            text-align: center;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .menu-container h1 {
            margin-bottom: 30px;
        }
        .menu-container .btn {
            width: 100%;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="menu-container">
    <?php if ($user) { ?>
        <h1>Добро пожаловать, <?= htmlspecialchars($user['username']) ?>!</h1>

        <a href="passes.php" class="btn btn-primary">Заявки</a>

        <a href="account.php" class="btn btn-primary">Аккаунт</a>

    <?php if ($admFlag) { ?>
        <a href="users.php" class="btn btn-primary">Пользователи</a>
    <?php } ?>

        <form method="post" action="do_logout.php">
            <button type="submit" class="btn btn-danger">Выйти</button>
        </form>

        <script>
            // Обработка нажатия клавиши "Esc"
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const logoutButton = document.querySelector('form button[type="submit"]');
                    if (logoutButton) logoutButton.click();
                }
            });
        </script>

    <?php } else { ?>
        <h1>Регистрация</h1>

        <?php flash(); ?>

        <form method="post" action="do_register.php">
            <div class="mb-3 text-start">
                <label for="username" class="form-label">Имя пользователя</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-2">Регистрация</button>
            <a href="login.php" class="btn btn-outline-primary w-100">Войти</a>
        </form>
    <?php } ?>
</div>

</body>
</html>
