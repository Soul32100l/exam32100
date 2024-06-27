<?php
require 'conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
<h2>Главная страница</h2>
    <?php
    if (isset($_SESSION['user_id'])) {
        echo 'Привет, ' . $_SESSION['username'] . '! <a href="logout.php">Выйти</a>';
    } else {
        echo '<a href="login.php">Войти</a> | <a href="register.php">Регистрация</a> | <a href="aplication.php">хз что хочу</a>';
    }
    ?>
</body>
</html>