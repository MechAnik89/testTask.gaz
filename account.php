<?php
require_once __DIR__ . '/boot.php';

if (!check_auth()) {
    header('Location: /');
    die;
}

$userId = $_SESSION['user_id'];
$pdo = pdo();

// Получаем текущие данные пользователя
$stmt = $pdo->prepare("SELECT id, username, admFlag FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Обработка отправки формы
$flashMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
    $newUsername = trim($_POST['username'] ?? '');
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($newUsername === '') {
        $flashMessage = 'Имя пользователя не может быть пустым.';
    } elseif ($newPassword !== '' && $newPassword !== $confirmPassword) {
        $flashMessage = 'Пароли не совпадают.';
    } else {
        // обновление данных
        if ($newPassword !== '') {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
            $stmt->execute([
                    'username' => $newUsername,
                    'password' => $hash,
                    'id' => $userId
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE id = :id");
            $stmt->execute([
                    'username' => $newUsername,
                    'id' => $userId
            ]);
        }
        $flashMessage = 'Данные успешно обновлены!';
        $user['username'] = $newUsername;
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аккаунт</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Мой аккаунт</h2>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $flashMessage): ?>
        <div class="alert alert-info"><?= htmlspecialchars($flashMessage) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">ID пользователя</label>
            <input type="text" class="form-control" value="<?= $user['id'] ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Администратор</label>
            <input type="text" class="form-control" value="<?= $user['admFlag'] ? 'Да' : 'Нет' ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Имя пользователя</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Новый пароль</label>
            <input type="password" name="password" class="form-control" placeholder="Оставьте пустым, если не меняете">
        </div>

        <div class="mb-3">
            <label class="form-label">Подтверждение пароля</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Повторите пароль">
        </div>

        <div class="d-flex justify-content-center gap-2">
            <a class="btn btn-secondary" href="index.php">Назад</a>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
</div>
</body>
</html>
