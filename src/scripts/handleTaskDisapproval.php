<?php
include 'dbFunctions.php';

$task_id = $_POST['task_id'];
$user_id = $_POST['user_id'];
$is_null = $_POST['is_null'];

$sql = '';
if ($is_null == 'true'){
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