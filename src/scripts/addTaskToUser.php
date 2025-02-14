<?php
include 'connector.php';

$task_id = $_POST['task_id'] ?? '';
$points = $_POST['points'] ?? '';

$task_id = sanitizeInput($task_id);
$points = sanitizeInput($points);

try{
    if ($completedTasks->addTaskToUser($task_id, $points, $scoutPaths, $meritBadges)){
        echo 'line';
    }
    else {
        echo 'text';
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>