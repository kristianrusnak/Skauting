<?php
setcookie('user_id', '', time() - 3600, '/');
setcookie('name', '', time() - 3600, '/');
setcookie('position', '', time() - 3600, '/');
unset($_COOKIE['user_id']);
unset($_COOKIE['name']);
unset($_COOKIE['position']);
header('Location: ../pages/login.php');
exit;
?>