<?php
$config = include __DIR__ . '/config.php';
session_start();
$connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
mysqli_select_db($connection, $config['db_name']);
$query = "SELECT * FROM passes";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_array($result)) {
    $datetimeFrom = $row['timeFrom'];
    $datetime = new DateTime($datetimeFrom);
    $datetime->modify('+10 hours');
    $datetimeNow = new DateTime();
    if(!($datetime>$datetimeNow) and ($row['pass'] == 1)){
        $query = "UPDATE passes SET pass = 2 WHERE id = ".$row['id'];
        mysqli_query($connection,$query);
    }elseif(($datetime>$datetimeNow) and ($row['pass'] != 1)){
        $query = "UPDATE passes SET pass = 0 WHERE id = ".$row['id'];
        mysqli_query($connection,$query);
    }
}
// Простой способ сделать глобально доступным подключение в БД
function pdo(): PDO
{
    static $pdo;

    if (!$pdo) {
        if (file_exists(__DIR__ . '/config.php')) {
            $config = include __DIR__.'/config.php';
        } else {
            $msg = 'Создайте и настройте config.php на основе config.sample.php';
            trigger_error($msg, E_USER_ERROR);
        }
        // Подключение к БД
        $dsn = 'mysql:dbname='.$config['db_name'].';host='.$config['db_host'];
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}

function flash(?string $message = null)
{
    if ($message) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
            <div class="alert alert-danger mb-3">
                <?=$_SESSION['flash']?>
            </div>
        <?php }
        unset($_SESSION['flash']);
    }
}

function check_auth(): bool
{
    return !!($_SESSION['user_id'] ?? false);
}