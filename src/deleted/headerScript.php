<?php
function getMainHeader($title){
    echo '<!DOCTYPE html>
        <lang="sk">
        <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Skauting-'.$title. '</title>
        <link rel="stylesheet" href="../styles/menuStyle.css">
        <link rel="stylesheet" href="../styles/tasksContainerStyle.css">
        <link rel="stylesheet" href="../styles/mainStyle.css">
        <link rel="stylesheet" href="../styles/userStyle.css">
        <link rel="stylesheet" href="../styles/tasksListerStyle.css">
        <link rel="stylesheet" href="../styles/groupContainerStyle.css">
        </head>
        <body>';
}

function getLoginHeader(){
    echo '<!DOCTYPE html>
        <lang="sk">
        <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Skauting-login</title>
        <link rel="stylesheet" href="../styles/loginStyle.css">
        </head>
        <body>';
}
?>