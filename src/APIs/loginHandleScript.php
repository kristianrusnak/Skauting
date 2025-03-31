<?php

require_once dirname(__DIR__) . '/scripts/Utilities/SessionManager.php';
require_once dirname(__DIR__) . '/scripts/Users/Service/UserService.php';
require_once dirname(__DIR__) . '/scripts/Utilities/Functions.php';

use Utility\SessionManager as Session;
use User\Service\UserService as User;
use Utility\Functions as Functions;

// Check if user is already signed in
if (Session::areAllValuesSet()) {
    header('Location: ../pages/home.php');
}

$submit = $_POST['submit'] ?? false;
$email = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";

// Errors
$error1 = false;

if ($submit) {

    if (!empty($email) && !empty($password)) {
        $email = Functions::sanitizeInput($_POST['email']);
        $password = Functions::sanitizeInput($_POST['password']);

        $user = new User();

        if ($user->logInUserByPassword($email, $password)) {
            header('Location: ../pages/home.php');
            exit;
        }
    }

    $error1 = true;
}