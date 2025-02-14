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
    $attempt = 0;
    $maxRetries = 3;
    while ($attempt < $maxRetries){
        try{
            if ($mysqli->query($sql) === TRUE) {
                echo 'unVerified';
                break;
            }
            else{
                echo 'query error';
                break;
            }
        }
        catch (Exception $e){
            if ($e->getCode() == 1213){
                $attempt++;
                if ($attempt < $maxRetries){
                    $waitTime = rand(300, 1000);
                    usleep($waitTime * 1000);
                }
                else{
                    echo 'deadlock';
                    break;
                }
            }
            else{
                echo 'mysql thrown exception';
            }
        }
    }
}
else{
    echo 'database connection error';
}
?>