<?php

require_once dirname(__DIR__) . '/scripts/Utilities/SessionManager.php';
require_once dirname(__DIR__) . '/scripts/Users/Service/UserService.php';
require_once dirname(__DIR__) . '/scripts/Utilities/Functions.php';

use Utility\SessionManager as Session;
use Utility\Functions as Functions;
use User\Service\UserService as User;

// Check if user is already signed in
if (Session::areAllValuesSet()) {
    header('Location: ../pages/home.php');
}

$name = $_POST['name'] ?? "";
$email = $_POST['email'] ?? "";
$password1 = $_POST['password1'] ?? "";
$password2 = $_POST['password2'] ?? "";
$submit = $_POST['submit'] ?? false;

$name = Functions::sanitizeInput($name);
$email = Functions::sanitizeInput($email);
$password1 = Functions::sanitizeInput($password1);
$password2 = Functions::sanitizeInput($password2);
$submit = Functions::sanitizeInput($submit);

$user = new User();

$error1 = false;

if ($submit) {
    if ($name && $email && $password1 && $password2) {

        if ($password1 != $password2) {
            $error1 = true;
        }
        else {
            if(!$user->registerUser($name, $email, $password1)){
                echo 'chyba počas prihlasovania';
            }
            else if ($user->logInUserByPassword($email, $password1)) {
                header('Location: ../pages/home.php');
                exit;
            }
            else{
                echo "Chyba počas prihlasovania";
            }
        }

    }
}
?>