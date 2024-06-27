<?php
require 'conn.php';

if (!isset($_SESSION['user_id'])) {
    echo '<h1>Вы не авторизованы!</h1>';
    exit();
}

echo '<h1> Вы авторизованы как ' . $_SESSION['username'];
?>
