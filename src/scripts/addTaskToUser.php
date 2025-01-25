<?php
include 'dbFunctions.php';

$task_id = $_POST['task_id'] ?? '';
$user_id = $_COOKIE['user_id'];
$points = get_points_for_task($mysqli, $task_id);
$position_id = get_position_from_task($mysqli, $task_id);
$my_position_id = $_COOKIE['position_id'];
$message = '';
$sql = '';

if ($my_position_id >= $position_id && is_task_merit_badge($mysqli, $task_id)){
    $sql = "INSERT INTO complited_tasks (user_id, task_id, points, verified) VALUES ('$user_id', '$task_id', null, true)";
    $message = 'line';
}
else if ($points == null){
    $sql = "INSERT INTO complited_tasks (user_id, task_id, points, verified) VALUES ('$user_id', '$task_id', null, false)";
    $message = 'text';
}
else if ($my_position_id >= $position_id){
    $sql = "INSERT INTO complited_tasks (user_id, task_id, points, verified) VALUES ('$user_id', '$task_id', '$points', true)";
    $message = 'line';
}
else{
    $sql = "INSERT INTO complited_tasks (user_id, task_id, points, verified) VALUES ('$user_id', '$task_id', '$points', false)";
    $message = 'text';
}

if (!$mysqli->connect_errno) {
    if ($mysqli->query($sql) === TRUE) {
        echo $message;
    }
    else{
        echo 'ERROR';
    }
}
else{
    echo 'ERROR';
}
?>