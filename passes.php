<?php
require_once __DIR__.'/boot.php';

$user = null;
$admFlag = false;

if (check_auth()) {
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $admFlag = !empty($user['admFlag']);
} else {
    header('Location: /');
    exit;
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
            justify-content: center;
            align-items: flex-start;
            padding-top: 50px;
            background-color: #f8f9fa;
        }
        .container-main {
            width: 100%;
            max-width: 900px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn {
            margin-bottom: 10px;
        }
        table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container-main">
    <?php if ($user) { ?>
        <h1>Заявки</h1>

        <?php if ($admFlag) {
            $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
            mysqli_select_db($connection, $config['db_name']);
            $query = "SELECT * FROM passes";
            $result = mysqli_query($connection, $query);
            ?>

            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Номер заявки</th>
                    <th>Автомобиль</th>
                    <th>Дата подачи</th>
                    <th>Дата завершения</th>
                    <th>Разрешение</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($result)) {
                    $status = $row['pass'] == 1 ? 'Одобрен' : ($row['pass'] == 2 ? 'Просрочен' : 'Не одобрен'); ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['typeVehLab']) ?></td>
                        <td><?= htmlspecialchars($row['timeFromLab']) ?></td>
                        <td><?= htmlspecialchars($row['timeToLab'] === null ? '' : $row['timeToLab']) ?></td>
                        <td><?= $status ?></td>
                        <td>
                            <?php if ($status == 'Одобрен') { ?>
                                <a href="doPass.php?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Отклонить</a>
                            <?php } elseif ($status == 'Не одобрен') { ?>
                                <a href="doPass.php?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Подтвердить</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        <?php } else {
            $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
            mysqli_select_db($connection, $config['db_name']);
            $query = "SELECT * FROM passes WHERE idUsers =" . $user['id'];
            $result = mysqli_query($connection, $query); ?>

            <a class="btn btn-outline-primary w-100" href="addPass.php">Новая заявка</a>

            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Номер заявки</th>
                    <th>Автомобиль</th>
                    <th>Дата подачи</th>
                    <th>Дата завершения</th>
                    <th>Разрешение</th>
                    <th>Пропуск</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($result)) {
                    $status = $row['pass'] == 1 ? 'Одобрен' : ($row['pass'] == 2 ? 'Просрочен' : 'Не одобрен'); ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['typeVehLab']) ?></td>
                        <td><?= htmlspecialchars($row['timeFromLab']) ?></td>
                        <td><?= htmlspecialchars($row['timeToLab']) ?></td>
                        <td><?= $status ?></td>
                        <td>
                            <?php if ($status == 'Одобрен') { ?>
                                <a href="pass.php?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Открыть</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>

        <a href="index.php" class="btn btn-outline-primary w-100 mt-3">Назад</a>

    <?php } else { ?>
        <?php flash(); ?>
    <?php } ?>
</div>
</body>
</html>
