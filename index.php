<?php
require_once __DIR__.'/boot.php';

$user = null;
$admFlag = false;
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user['admFlag']){
        $admFlag = True;
    }else{
        $admFlag = False;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Пропуска</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></head>
<body>

<div class="container">
    <div class="row py-5">
        <div class="col-lg-6">

            <?php if ($user) { ?>

                <h1>Добро пожаловать, <?=htmlspecialchars($user['username'])?>!</h1>
                <div class="mb-3" id = "menuAdmin">
                    <form class="mt-5" method="post" action="passes.php">
                        <button type="submit" class="btn btn-primary">Заявки</button>
                    </form>
                    <form class="mt-5" method="post" action="do_logout.php">
                        <button type="submit" class="btn btn-primary">Выйти</button>
                    </form>
                </div>


            <?php } else { ?>

                <h1 class="mb-5">Регистрация</h1>

                <?php flash(); ?>

                <form method="post" action="do_register.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Имя пользователя</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Регистрация</button>
                        <a class="btn btn-outline-primary" href="login.php">Войти</a>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>