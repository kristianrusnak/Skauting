<?php

require_once dirname(__DIR__) . '/scripts/connector.php';
require_once dirname(__DIR__) . '/scripts/Utilities/Functions.php';
require_once dirname(__DIR__) . '/scripts/ScoutPath/Service/ScoutPathService.php';

use Utility\Functions as Functions;
use ScoutPath\Service\ScoutPathService as ScoutPathService;

$paths = new ScoutPathService();

// Read JSON input
$json = file_get_contents('php://input');
$input = json_decode($json, true);

$operation = $input['operation'] ?? "";
$data = $input['data'] ?? array();

$operation = Functions::sanitizeInput($operation);
$data = Functions::sanitizeArray($data);

$response = [
    'error' => false,
    'error_message' => '',
];



if ($operation === 'create_chapter' && $_SESSION['position_id'] >= 5) {

    foreach ($data as $chapter) {

        $newChapter = [
            'name' => $chapter['name'] ?? "",
            'scout_path_id' => $chapter['scout_path_id'] ?? "",
            'area_id' => $chapter['area_id'] ?? "",
        ];

        if (empty($newChapter['name']) || empty($newChapter['scout_path_id']) || empty($newChapter['area_id'])) {
            continue;
        }

        if (!$paths->addNewChapter($newChapter)) {
            $response['error'] = true;
            $response['error_message'] = "";
        }

    }

}
else if ($operation === 'update_chapter' && $_SESSION['position_id'] >= 5) {

    foreach ($data as $chapter_id => $name) {

        if (empty($name)) {
            continue;
        }

        if (!$paths->updateChapter($chapter_id, $name)) {
            $response['error'] = true;
            $response['error_message'] .= "Kapitolu s id: $chapter_id sa nepodarilo upraviť\n";
        }

    }

}
else if ($operation === 'delete_chapter' && $_SESSION['position_id'] >= 5){

    foreach ($data as $chapter_id) {

        //TODO
        if (!$paths->deleteChapter($chapter_id)) {
            $response['error'] = true;
            $response['error_message'] = "";
        }

    }

}
else if ($operation === 'create_task' && $_SESSION['position_id'] >= 5){

    foreach ($data as $task) {

        $newTask = [
            'chapter_id' => $task['chapter_id'] ?? "",
            'mandatory' => $task['mandatory'] ?? 1,
            'task' => $task['task'] ?? "",
            'name' => $task['name'] ?? "",
            'position_id' => $task['position'] ?? "",
            'points' => $task['points'] ?? null
        ];

        if ($newTask['points'] == "") {
            $newTask['points'] = null;
        }

        if (empty($newTask['task']) || empty($newTask['name']) || empty($newTask['position_id'])) {
            continue;
        }

        if (!$paths->addNewTask($newTask)) {
            $response['error'] = true;
            $response['error_message'] = "";
        }

    }

}
else if ($operation === 'update_task' && $_SESSION['position_id'] >= 5){

    foreach ($data as $task_id => $task) {

        $newTask = array();

        if (!empty($task['task'])) {
            $newTask['task'] = $task['task'];
        }

        if (!empty($task['name'])) {
            $newTask['name'] = $task['name'];
        }

        if (!empty($task['position'])) {
            $newTask['position_id'] = $task['position'];
        }

        if (!empty($task['points'])) {
            $newTask['points'] = $task['points'];
        }
        else{
            $newTask['points'] = null;
        }

        if (!$paths->updateTask($task_id, $newTask)) {
            $response['error'] = true;
            $response['error_message'] = "";
        }

    }

}
else if ($operation === 'delete_task' && $_SESSION['position_id'] >= 5){

    foreach ($data as $task_id) {

        if ($paths->deleteTask($task_id)) {
            $response['error'] = true;
            $response['error_message'] = "";
        }

    }

}
else {
    $response["error"] = true;
    $response["error_message"] = "";
}

echo json_encode($response);