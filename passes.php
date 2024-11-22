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
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="container mx-auto">
    <div class="row">
        <div class="col-lg-6">
            <?php if ($user && $admFlag) { ?>
                <h1 class="mb-4">Заявки</h1>
                <div class="container-fluid" id="passesList">
                    <?php
                    $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
                    mysqli_select_db($connection, $config['db_name']);
                    $query = "SELECT * FROM passes";
                    $result = mysqli_query($connection, $query);
                    echo "<table class='table table-bordered table-hover'>";
                    echo "<thead><tr><th>Номер заявки</th><th>Автомобиль</th><th>Дата подачи</th><th>Дата завершения</th><th>Разрешение</th><th>Действие</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_array($result)) {
                        if($row['pass'] == 1){
                            $row['pass'] = 'Одобрен';}
                        elseif($row['pass'] == 2){
                            $row['pass'] = 'Просрочен';
                        } else {
                            $row['pass'] = 'Не одобрен';}
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['typeVehLab']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['timeFromLab']) . "</td>";
                        echo "<td>" . $row['timeToLab'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['pass']) . "</td>";
                        echo "<td>";
                        $idshka = $row['id'];
                        if ($row['pass'] == 'Одобрен') {
                            echo '<form action="doPass.php" method="POST">';?>
                            <a href="doPass.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-danger">Отклонить</a>
                            <?php echo '</form>';
                        } elseif ($row['pass'] == 'Не одобрен') {
                            echo '<form action="doPass.php" method="POST">';?>
                            <a href="doPass.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success">Подтвердить</a>                            <?php echo '</form>';
                        }else{
                            echo '';
                        }
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
                    $query = "SELECT * FROM passes WHERE user_id =" . $user['id'];
                    $result = mysqli_query($connection, $query);
                    echo "<table class='table table-bordered table-hover'>";
                    echo "<thead><tr><th>Номер заявки</th><th>Автомобиль</th><th>Дата подачи</th><th>Дата завершения</th><th>Разрешение</th><th>Пропуск</th></tr></thead>";
                    echo "<tbody>";
                    if($result){
                    while ($row = mysqli_fetch_array($result)) {
                        if($row['pass'] == 1){
                            $row['pass'] = 'Одобрен';
                        }elseif($row['pass'] == 2){
                            $row['pass'] = 'Просрочен';
                        }else {
                            $row['pass'] = 'Не одобрен';}
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['typeVeh']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['timeFrom']) . "</td>";
                        echo "<td>" . $row['timeTo'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['pass']) . "</td>";
                        echo "<td>";
                        if ($row['pass'] == 'Одобрен') {
                            echo '<form action="pass.php" method="POST">';?>
                            <a href="pass.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-danger">Открыть</a>
                            <?php echo '</form>';
                        } elseif ($row['pass'] == 'Не одобрен' or $row['pass'] == 'Просрочен') {
                            echo '';
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    }
                    ?>
                </div>
            <?php } else { ?>
                <?php flash(); ?>
            <?php } ?>
            <a class="btn btn-outline-primary" href="/index.php" id = "back">Назад</a>

            <script>
                // Обработка нажатия клавиши "Esc"
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') { // Проверяем, что нажата именно клавиша "Esc"
                        event.preventDefault(); // Предотвращаем стандартное поведение
                        const backButton = document.getElementById('back');
                        if (backButton) {
                            backButton.click(); // Программно вызываем событие "нажатия"
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>
</body>
</html>