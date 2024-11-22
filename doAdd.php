<?php
require_once __DIR__.'/boot.php';
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    if (file_exists(__DIR__ . '/config.php')) {
        $config = include __DIR__.'/config.php';
    } else {
        $msg = 'Создайте и настройте config.php на основе config.sample.php';
        trigger_error($msg, E_USER_ERROR);
    }
}
$user = null;
$admFlag = false;
if (check_auth()) {
// Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
$connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']); //The Blank string is the password
mysqli_select_db($connection, $config['db_name']);
$datetimeValue = $_POST['timeFromLab'];
$typeVeh1 = $_POST['typeVehLab'];
$id1 = $user['id'];
$datetime = new DateTime($datetimeValue);
$formattedDatetime = $datetime->format('Y-m-d H:i:s');

$query = "INSERT INTO passes (user_id, typeVehLab, timeFromLab,pass) VALUES ('$id1', '$typeVeh1','$formattedDatetime',0)";
$sql = mysqli_query($connection,$query);
if($sql){
flash('Успешно!');
header('Location: /passes.php');
die;}
else{
    $error = mysqli_error($connection); // Получение текста ошибки SQL
    flash('Ошибка! ' . $error); // Вывод текста ошибки в flash
    header('Location: /addPass.php');
    die;
}




?>
