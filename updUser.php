<?php

require_once __DIR__.'/boot.php';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Пропуска</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></head>
</head>
<body>

<div class="container">
    <div class="row py-5">
        <div class="col-lg-6">

            <h1 class="mb-5">Введите новые данные</h1>

            <?php flash();
                $id = $_GET['id'];?>

            <form method="post" action="doUpdUsers.php?&id=<?php echo $id; ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Имя пользователя</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="admFlag" class="form-label">Админ</label>
                    <input type="checkbox" id="admFlag" name="admFlag" checked>
                </div>
                <div class="d-flex justify-content-between">
                    <a class="btn btn-outline-primary" href="users.php">Назад</a>
                    <button type="submit" class="btn btn-primary">Подтвердить</button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>