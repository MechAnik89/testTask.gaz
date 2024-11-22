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
if (isset($_GET['action']) && isset($_GET['id']) && $admFlag) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'approve') {
        $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
        mysqli_select_db($connection, $config['db_name']);
        $request = mysqli_query($connection, "SELECT * FROM `passes` WHERE `id` = $id");
        $pullR = mysqli_fetch_array($request);
        $datetimeFrom = $pullR['timeFromLab'];
        $datetime = new DateTime($datetimeFrom);
        $datetime->modify('+10 hours');
        $formattedDatetime = $datetime->format('Y-m-d H:i:s');
        $query = "UPDATE `passes` SET `timeToLab` = '$formattedDatetime', `pass` = 1 WHERE `passes`.`id` = $id";
        $result = mysqli_query($connection, $query);
        flash($query);
    } elseif ($action == 'reject') {
        $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
        mysqli_select_db($connection, $config['db_name']);
        $query = "UPDATE `passes` SET `timeToLab` = null, `pass` = 0 WHERE `passes`.`id` = $id";
        $result = mysqli_query($connection, $query);
    }
    else{
        flash("!!!!!!!!!!");

    }

    header('Location: passes.php');
    exit;
}
?>