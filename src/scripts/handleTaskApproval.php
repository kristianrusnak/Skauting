<?php
include 'dbFunctions.php';

$task_id = $_POST['task_id'];
$user_id = $_POST['user_id'];
$points = $_POST['points'] ?? -1;
$get_points = get_points_for_task($mysqli, $task_id);

$sql = '';

if (!is_my_leader($mysqli, $user_id, $_COOKIE['user_id'], $_COOKIE['position_id'])) {
    echo 'FORBIDDEN ACCESS';
    exit;
}
else if (!is_task_scout_path($mysqli, $task_id) || $get_points != null){
    $sql = 'UPDATE complited_tasks SET verified = 1 WHERE task_id = '.$task_id.' AND user_id = '.$user_id;
}
else if ($get_points == null && $points > 0) {
    $sql = 'UPDATE complited_tasks SET verified = 1, points = '.$points.' WHERE task_id = '.$task_id.' AND user_id = '.$user_id;
}
else{
    echo 'FORBIDDEN INPUT';
}

if (!$mysqli->connect_errno) {
    $attempt = 0;
    $maxRetries = 3;
    while ($attempt < $maxRetries){
        try{
            if ($mysqli->query($sql) === TRUE) {
                echo 'verified';
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