<?php
require_once __DIR__.'/boot.php';

$user = null;
$admFlag = false;
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
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
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Пользователи</title>
</head>
<body>
<div class="container mx-auto">
    <div class="row">
        <div class="col-lg-6">
            <?php if ($user && $admFlag) { ?>
                <h1 class="mb-4">Пользователи</h1>
                <div class="container-fluid" id="passesList">
                    <?php
                    flash();
                    $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
                    mysqli_select_db($connection, $config['db_name']);
                    $query = "SELECT * FROM users";
                    $result = mysqli_query($connection, $query);
                    echo "<table class='table table-bordered table-hover'>";
                    echo "<thead><tr><th>№ пользователя</th><th>Логин</th><th>Хеш пароля</th><th>Группа</th><th>Действие</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_array($result)) {
                        if($row['admFlag'] == 1){
                            $row['admFlag'] = 'Администратор';
                        }else{
                            $row['admFlag'] = 'Пользователь';
                        }
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                        echo "<td>" . $row['admFlag'] . "</td>";
                        echo "<td>";
                        $idshka = $row['id'];
                        echo '<form action="delUser.php" method="POST">';?>
                        <a href="delUser.php&id=<?php echo $row['id']; ?>" class="btn btn-danger">Удалить</a>
                        <?php echo '</form>';
                        echo '<form action="updUser.php" method="POST">';?>
                        <a href="updUser.php?&id=<?php echo $row['id']; ?>" class="btn btn-success">Изменить</a>                            <?php echo '</form>';
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    ?>
                </div>
            <?php } elseif ($user && !$admFlag) { ?>
                <h1 class="mb-4">Заявки</h1>
                <a class="btn btn-outline-primary mb-3" href="addPass.php">Новая заявка</a>
                <div class="mb-3" id="passesList">
                    <?php
                    $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
                    mysqli_select_db($connection, $config['db_name']);
                    $query = "SELECT * FROM passes WHERE idUsers =" . $user['id'];
                    $result = mysqli_query($connection, $query);
                    echo "<table class='table table-bordered table-hover'>";
                    echo "<thead><tr><th>Номер заявки</th><th>Автомобиль</th><th>Дата подачи</th><th>Дата завершения</th><th>Разрешение</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_array($result)) {
                        if($row['pass']){
                            $row['pass'] = 'Одобрен';
                        }else {
                            $row['pass'] = 'Не одобрен';}
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['typeVeh']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['timeFrom']) . "</td>";
                        echo "<td>" . $row['timeTo'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['pass']) . "</td>";

                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    ?>
                </div>
            <?php } else { ?>
                <?php flash(); ?>
            <?php } ?>
            <a class="btn btn-outline-primary" href="/index.php">Назад</a>
        </div>
    </div>
</div>
</body>
</html>

