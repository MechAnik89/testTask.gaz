<?php

require_once __DIR__.'/boot.php';

if (!check_auth()) {
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
    <title>Заявки</title>
</head>
<body>
<?php echo print_r($user);?>
</body>
</html>