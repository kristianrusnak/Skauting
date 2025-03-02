<?php

include 'connector.php';

// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true); // Convert JSON to PHP array

if ($data && $_SESSION['position_id'] >= 5) {
    if (isset($_GET['create_chapter']) && $_GET['create_chapter'] == 'true'){
        foreach ($data as $chapter) {
            if (isset($chapter['name'])) {
                if ($scoutPaths->addNewChapter($chapter['scout_path_id'], $chapter['area_id'], $chapter['name'])) {
                    echo "Ok";
                }
                else {
                    echo "Error while try to add new chapter!";
                }
            }
        }
    }
    else if (isset($_GET['update_chapter']) && $_GET['update_chapter'] == 'true') {
        foreach ($data as $chapter_id => $name) {
            if ($scoutPaths->updateChapter($chapter_id, $name)) {
                echo "Ok";
            }
            else {
                echo "Error while trying to update chapter $chapter_id!";
            }
        }
    }
    else if (isset($_GET['delete_chapter']) && $_GET['delete_chapter'] == 'true'){
        foreach ($data as $chapter_id) {
            if ($scoutPaths->deleteChapter($chapter_id)) {
                echo "Ok";
            }
            else {
                echo "Error while trying to delete chapter $chapter_id!";
            }
        }
    }
    else if (isset($_GET['create_task']) && $_GET['create_task'] == 'true'){
        foreach ($data as $task) {
            if (isset($task['task']) && $task['task'] != "" && isset($task['name']) && $task['name'] != "" &&
                isset($task['position']) && $task['position'] != "" && isset($task['points'])) {
                if ($scoutPaths->addNewTask($task['chapter_id'], $task['mandatory'], $task['name'], $task['task'], $task['points'], $task['position'])) {
                    echo "ok";
                }
                else {
                    echo "Error while trying to add new task!";
                }
            } else {
                echo "Some value are not set to add new task!";
            }
        }
    }
    else if (isset($_GET['update_task']) && $_GET['update_task'] == 'true'){
        foreach ($data as $task_id => $task) {
            if ($scoutPaths->updateTask($task_id, $task['name'], $task['task'], $task['points'], $task['position'])) {
                echo "ok";
            }
            else {
                echo "Error while trying to update task $task_id!";
            }
        }
    }
    else if (isset($_GET['delete_task']) && $_GET['delete_task'] == 'true'){
        foreach ($data as $task_id) {
            if ($scoutPaths->deleteTask($task_id)) {
                echo "Ok";
            }
            else {
                echo "Error while trying to delete task $task_id!";
            }
        }
    }
}
else {
    if ($data) {
        echo 'No valid data received!';
    }
    else {
        echo 'No permission for such a operation!';
    }
}
?>