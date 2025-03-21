<?php

// Check if user is already signed in
if ($session->areAllValuesSet()) {
    header('Location: ../pages/home.php');
}

// Errors
$error1 = false;
if (isset($_POST['submit'])) {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password1']) && isset($_POST['password2'])) {
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $password1 = sanitizeInput($_POST['password1']);
        $password2 = sanitizeInput($_POST['password2']);

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