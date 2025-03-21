<?php
require_once '../scripts/connector.php';

session_start();

session_unset();   // Unset all session variables
session_destroy();

header('Location: ../pages/login.php');
exit;
?>