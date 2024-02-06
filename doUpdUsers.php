<?php

require_once __DIR__.'/boot.php';

if (isset($_GET['id'])) {
    $user = null;
    $admFlag = false;
    if (check_auth()) {
        // Получим данные пользователя по сохранённому идентификатору
        if (file_exists(__DIR__ . '/config.php')) {
            $config = include __DIR__ . '/config.php';
        } else {
            $msg = 'Создайте и настройте config.php на основе config.sample.php';
            trigger_error($msg, E_USER_ERROR);
        }
        $idpost = $_GET['id'];
        $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
        mysqli_select_db($connection, $config['db_name']);
        $query = 'SELECT * FROM `users` WHERE `id` ='.$idpost;
        $request = mysqli_query($connection, $query);
        $result = mysqli_fetch_array($request);
        if(!($result['username'] == $_POST['username'])) {
            $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `username` = :username");
            $stmt->execute(['username' => $_POST['username']]);
            if ($stmt->rowCount() > 0) {
                flash('Это имя пользователя уже занято.');
                header('Location: /users.php');
                die;
            }
        }

        $usernamepost = $_POST['username'];
        $pass = $_POST['password'];
        if (isset($_POST['admFlag'])) {
            $admFlagpost = 1;
        }
        else{
            $admFlagpost = 0;
        }
        $query = 'UPDATE `users` SET `username` = "'.$usernamepost.'", `password` = "'.$pass.'", `admFlag` = "'.$admFlagpost.'" WHERE `id` = '.$idpost;
        $request = mysqli_query($connection, $query);
        $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = pdo()->prepare('UPDATE `users` SET `password` = :password WHERE `id` = '.$idpost);
        $stmt->execute([
            'password' => $newHash,
        ]);
            flash('Успешно!');
            header('Location: /users.php');
            die;
        }
    } else {
        header('Location: /users.php');
        die;
    }



?>