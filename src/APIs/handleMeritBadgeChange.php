<?php
require_once '../scripts/connector.php';

// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true); // Convert JSON to PHP array

if ($data) {
    if (isset($_GET['change_task']) && $_GET['change_task'] == 'true' && $_SESSION['position_id'] >= 5) {
        foreach ($data as $task_id => $task) {
            if ($meritBadges->UpdateMeritBadgeTask($task_id, $task)) {
            echo 'Ok';
            }
            else {
                echo 'Error while trying to update merit badge task: ' . $task_id;
                break;
            }
        }
    }
    else if (isset($_GET['add_task']) && $_GET['add_task'] == 'true' && $_SESSION['position_id'] >= 5) {
        foreach ($data as $new_task_id => $task) {
            if ($meritBadges->createNewMeritBadgeTask($task[0], $task[1], $task[2])) {
                echo 'Ok';
            }
            else {
                echo 'Error while trying to create merit badge task';
                break;
            }
        }

    }
    else if (isset($_GET['delete_task']) && $_GET['delete_task'] == 'true' && $_SESSION['position_id'] >= 5) {
        foreach ($data as $task_id) {
            if ($meritBadges->deleteMeritBadgeTask($task_id)) {
                echo 'Ok';
            }
            else {
                echo 'Error while trying to delete merit badge task: ' . $task_id;
                break;
            }
        }
    }
    else {
        echo 'No valid parameter received or no permission granted!';
    }
}
else {
    echo 'No valid data received!';
}

?>