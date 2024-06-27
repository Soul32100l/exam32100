<?php
require 'conn.php';

session_unset();
session_destroy();

echo '<h1>Вы вышли из системы!</h1>';
echo '<a href="index.php">Главная</a>';
?>
