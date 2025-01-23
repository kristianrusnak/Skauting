<?php
include 'dbFunctions.php';

$task_id = $_POST['task_id'] ?? '';
$user_id = $_COOKIE['user_id'];

if (!$mysqli->connect_errno) {
    $sql = "DELETE FROM complited_tasks WHERE user_id = '$user_id' AND task_id = '$task_id'";
    if ($mysqli->query($sql) === TRUE) {
        echo 'delete';
    }
    else{
        echo 'ERROR';
    }
}
else{
    echo 'ERROR';
}
?>