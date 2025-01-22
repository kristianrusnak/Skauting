<?php
date_default_timezone_set('Europe/Bratislava');
include('db.php');
include('sanitizeScript.php');
include('tasksContainerScript.php');
include('tasksListerScript.php');

function verifyUserAndSetCookies($mysqli, $email, $password){
    if (!$mysqli->connect_errno) {
        $sql = "SELECT * FROM `users` WHERE email = '$email'";
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];
            if (password_verify($password, $stored_password)) {
                setcookie("user_id", $row['id'], time() + (86400 * 30), "/");
                setcookie("name", $row['name'], time() + (86400 * 30), "/");
                setcookie("position_id", $row['position_id'], time() + (86400 * 30), "/");
                return true;
            }
        }
    }
    return false;
}

function check_get_for_merit_badge($mysqli, $id){
    $id = sanitizeInput($id);
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM merit_badges WHERE id = ".$id;
        try{
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
                return true;
            }
        }
        catch (Exception $e){
            return false;
        }
    }
    return false;
}

function get_name_of_merit_badge($mysqli, $id){
    $id = sanitizeInput($id);
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM merit_badges WHERE id = ".$id;
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            $row = $result->fetch_assoc();
            return $row['name'];
        }
    }
    return '';
}

function check_get_for_scout_path($mysqli, $id){
    $id = sanitizeInput($id);
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM scout_path WHERE id = ".$id;
        try{
            if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
                return true;
            }
        }
        catch (Exception $e){
            return false;
        }
    }
    return false;
}

function get_name_of_scout_path($mysqli, $id)
{
    $id = sanitizeInput($id);
    if (!$mysqli->connect_errno){
        $sql = "SELECT * FROM scout_path WHERE id = ".$id;
        if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
            $row = $result->fetch_assoc();
            return $row['name'];
        }
    }
    return '';

}
?>