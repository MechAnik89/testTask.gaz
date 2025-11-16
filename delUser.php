<?php
require_once __DIR__.'/boot.php';

// Проверяем авторизацию
if (!check_auth()) {
    header('Location: /');
    exit;
}

// Загружаем конфиг
if (file_exists(__DIR__ . '/config.php')) {
    $config = include __DIR__ . '/config.php';
} else {
    trigger_error('Создайте config.php на основе config.sample.php', E_USER_ERROR);
}

// Получаем данные текущего пользователя
$stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка — админ ли это
if (!$currentUser || !$currentUser['admFlag']) {
    flash("У вас нет прав для удаления пользователей!", "danger");
    header('Location: users.php');
    exit;
}

// Проверяем наличие id
if (!isset($_GET['id'])) {
    flash("Не указан ID пользователя!", "danger");
    header('Location: users.php');
    exit;
}

$id = (int)$_GET['id'];

// Запрет удалять самого себя
if ($id === $currentUser['id']) {
    flash("Нельзя удалить свой аккаунт!", "danger");
    header('Location: users.php');
    exit;
}

// Удаляем пользователя
$connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
mysqli_select_db($connection, $config['db_name']);

$query = "DELETE FROM `users` WHERE `id` = $id";
mysqli_query($connection, $query);

// Готово
flash("Пользователь успешно удалён!", "success");
header('Location: users.php');
exit;
