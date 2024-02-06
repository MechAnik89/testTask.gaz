<?php
require_once __DIR__.'/boot.php';

$user = null;
$admFlag = false;
if (check_auth()) {
    if (file_exists(__DIR__ . '/config.php')) {
        $config = include __DIR__.'/config.php';
    } else {
        $msg = 'Создайте и настройте config.php на основе config.sample.php';
        trigger_error($msg, E_USER_ERROR);
    }
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user['admFlag']){
        $admFlag = True;
    }else{
        $admFlag = False;
    }
}else{
    header('Location: /');
    die;
}
if (isset($_GET['action']) && isset($_GET['id']) && ($admFlag == True)) {
    $action = $_GET['action'];
    $id = $_GET['id'];
        $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
        mysqli_select_db($connection, $config['db_name']);
        $request = mysqli_query($connection, "SELECT * FROM `passes` WHERE `id` = $id");
        $pullR = mysqli_fetch_array($request);
        $datetimeFrom = $pullR['timeFrom'];
        $datetime = new DateTime($datetimeFrom);
        $datetime->modify('+10 hours');
        $formattedDatetime = $datetime->format('Y-m-d H:i:s');
        $query = "DELETE FROM `users` WHERE `id` = $id";
        $result = mysqli_query($connection, $query);

    header('Location: users.php');
    exit;
}
?>