<?php
include 'dbFunctions.php';

$task_id = $_POST['task_id'];
$user_id = $_POST['user_id'];
$points = $_POST['points'];

$sql = '';

if ($points < 0) {
    $sql = 'UPDATE complited_tasks SET verified = 1 WHERE task_id = '.$task_id.' AND user_id = '.$user_id;
}
else{
    $sql = 'UPDATE complited_tasks SET verified = 1, points = '.$points.' WHERE task_id = '.$task_id.' AND user_id = '.$user_id;
}

if (!$mysqli->connect_errno) {
    if ($mysqli->query($sql) === TRUE) {
        echo 'verified';
    }
    else{
        echo 'ERROR';
    }
}
else{
    echo 'ERROR';
}
?>