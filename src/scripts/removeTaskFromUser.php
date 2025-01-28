<?php
include 'dbFunctions.php';

$task_id = $_POST['task_id'] ?? '';
$task_position = get_position_from_task($mysqli, $task_id);

$user_id = $_COOKIE['user_id'];

if (!$mysqli->connect_errno && ($task_position <= $_COOKIE['position_id'] || !is_task_verified($mysqli, $task_id))) {
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