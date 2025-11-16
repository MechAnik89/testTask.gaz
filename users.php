<?php
require_once __DIR__.'/boot.php';

$user = null;
$admFlag = false;
if (check_auth()) {
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $admFlag = $user['admFlag'] ? true : false;
} else {
    header('Location: /');
    die;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 40px;
        }
        .table-container {
            width: 100%;
            max-width: 1000px;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="table-container">
    <?php if ($user && $admFlag) { ?>
        <h1 class="mb-4 text-center">Пользователи</h1>
        <?php flash(); ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>№ пользователя</th>
                    <th>Логин</th>
                    <th>Хеш пароля</th>
                    <th>Группа</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = pdo()->query("SELECT * FROM users");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $role = $row['admFlag'] ? 'Администратор' : 'Пользователь';
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                    echo "<td>$role</td>";
                    echo "<td class='d-flex gap-2'>";
                    echo "<a href='updUser.php?id={$row['id']}' class='btn btn-success btn-sm'>Изменить</a>";
                    echo "<a href='delUser.php?id={$row['id']}' class='btn btn-danger btn-sm'>Удалить</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            <a class="btn btn-primary" href="/index.php">Назад</a>
        </div>
    <?php } else { ?>
        <h1 class="mb-4 text-center">Нет доступа</h1>
        <div class="d-flex justify-content-center">
            <a class="btn btn-primary" href="/index.php">Назад</a>
        </div>
    <?php } ?>
</div>
</body>
</html>
