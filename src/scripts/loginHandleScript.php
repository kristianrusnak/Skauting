<?php

// Check if user is already signed in
if ($session->areAllValuesSet()) {
    header('Location: ../pages/home.php');
}

// Errors
$error1 = false;

if (isset($_POST['submit'])) {
    $error1 = true;
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = sanitizeInput($_POST['email']);
        $password = sanitizeInput($_POST['password']);

        if ($user->logInUserByPassword($email, $password)) {
            header('Location: ../pages/home.php');
            exit;
        }
        else {
            $error1 = true;
        }
    }
}