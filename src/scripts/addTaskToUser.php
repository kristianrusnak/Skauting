<?php
include 'connector.php';

$task_id = $_POST['task_id'] ?? '';

try{
    if ($completedTasks->addTaskToUser($task_id, $scoutPaths, $meritBadges)){
        echo 'line';
    }
    else {
        echo 'text';
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>