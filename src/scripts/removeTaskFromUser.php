<?php
include 'connector.php';

$task_id = $_POST['task_id'] ?? '';

try {
    if ($completedTasks->deleteTaskFromUser($task_id, $scoutPaths, $meritBadges)) {
        echo 'delete';
    }
    else {
        echo 'something went wrong with deleting task from user';
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>