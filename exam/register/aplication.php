<?php
require '../conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка совпадения паролей
    if ($password !== $confirm_password) {
        echo '<h1>Пароли не совпадают!</h1>';
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Проверка уникальности имени пользователя и email
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        echo '<h1>Имя пользователя или email уже занято!</h1>';
    } else {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $password_hash]);
        echo '<h2>Регистрация успешна!</h2>';
        echo '<a href="../login/index.php">Войти</a> | <a href="../index.php">Главная</a>';
    }
}
?>