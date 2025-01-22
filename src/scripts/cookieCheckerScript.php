<?php
if (!isset($_COOKIE['user_id']) || !isset($_COOKIE['name']) || !isset($_COOKIE['position_id'])) {
    header('Location: ../pages/login.php');
    exit;
}
?>