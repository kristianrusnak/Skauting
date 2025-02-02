<?php
include 'dbFunctions.php';

$task_id = $_POST['task_id'];
$user_id = $_POST['user_id'];
$get_points = get_points_for_task($mysqli, $task_id);

$sql = '';

if (!is_my_leader($mysqli, $user_id, $_COOKIE['user_id'], $_COOKIE['position_id'])){
    echo 'FORBIDDEN';
    exit;
}
if ($get_points == null){
    $sql = "UPDATE complited_tasks SET verified = 0, points = null WHERE task_id = ".$task_id." AND user_id = ".$user_id;
}
else{
    $sql = 'UPDATE complited_tasks SET verified = 0 WHERE task_id = '.$task_id.' AND user_id = '.$user_id;
}

if (!$mysqli->connect_errno) {
    if ($mysqli->query($sql) === TRUE) {
        echo 'unVerified';
    }
    else{
        echo 'ERROR';
    }
}
else{
    echo 'ERROR';
}
?>