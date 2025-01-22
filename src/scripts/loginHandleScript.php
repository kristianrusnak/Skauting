<?php
include 'dbFunctions.php';

if(isset($_COOKIE['user_id']) && isset($_COOKIE['name']) && isset($_COOKIE['position_id'])){
    header('Location: ../pages/home.php');
}

$error1 = false;

if (isset($_POST['submit'])){
    $error1 = false;
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = sanitizeInput($_POST['email']);
        $password = sanitizeInput($_POST['password']);
        if (verifyUserAndSetCookies($mysqli, $email, $password)){
            header('Location: ../pages/home.php');
        }
        else{
            $error1 = true;
        }
    }
    else{
        $error1 = true;
    }
}