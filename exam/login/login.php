<!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
</head>
<body>
<?php
require '../conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
		
		$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$username]);
    $email = $stmt->fetch();

    if (($email && password_verify($password, $email['password']) || ($user && password_verify($password, $user['password'])) )) {
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			echo '<h1>Авторизация успешна!</h1>';
			echo '<a href="../index.php">Главная</a>';
    } else {
        echo '<h1>Неверное имя пользователя или пароль!</h1>';
    }
}
?>
    <h2>Авторизация</h2>
    <form action="" method="post">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Войти">
    </form>
</body>
</html>
