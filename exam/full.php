<?php
// Подключение к базе данных
$host = 'MySQL-5.7';
$db = 'good';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

session_start();

// Проверьте, хочет ли пользователь выйти из системы
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    echo '<h1>Вы вышли из системы!</h1>';
    echo '<a href="index.php">Обновить страницу</a>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
</head>
<body>
<h2>Главная страница</h2>
    <?php
    if (isset($_SESSION['user_id'])) {
        echo 'Привет, ' . htmlspecialchars($_SESSION['username']) . '! <a href="index.php?action=logout">Выйти</a>';
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Обрабатывать регистрацию
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $confirm_password = $_POST['confirm_password'];

                // Проверка совпадения паролей
                if ($password !== $confirm_password) {
                    echo '<h1>Пароли не совпадают!</h1>';
                    exit();
                }
								//Хешируем пароль
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
                    echo '<a href="index.php">Обновить страницу</a>';
                }
            } else {
                // Обрабатывать вход в систему
                $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
                $stmt->execute([$username, $username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    echo '<h1>Авторизация успешна!</h1>';
                    echo '<a href="index.php">Главная</a>';
                } else {
                    echo '<h1>Неверное имя пользователя или пароль!</h1>';
                }
            }
        }
    ?> <br><br><hr>
    
			<div style="display: grid; grid-auto-flow: row;">
				<div>
				<h2>Авторизация</h2>
				<form action="" method="post">
					<label for="username">Имя пользователя или Email:</label>
					<input type="text" id="username" name="username" required><br>
					<label for="password">Пароль:</label>
					<input type="password" id="password" name="password" required><br>
					<input type="submit" value="Войти">
    		</form>
				</div>

				<div>
				<h2>Регистрация</h2>
				<form action="" method="post">
						<label for="username">Имя пользователя:</label>
						<input type="text" id="username" name="username" required><br>
						<label for="email">Email:</label>
						<input type="email" id="email" name="email" required><br>
						<label for="password">Пароль:</label>
						<input type="password" id="password" name="password" required><br>
						<label for="confirm_password">Подтверждение пароля:</label>
						<input type="password" id="confirm_password" name="confirm_password" required><br>
						<input type="submit" value="Зарегистрироваться">
				</form>
				</div>
			</div>
    <?php } ?>
</body>
</html>
