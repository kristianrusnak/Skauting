<?php
include 'connector.php';

$cookies->deleteAllCookies();
header('Location: ../pages/login.php');
exit;
?>