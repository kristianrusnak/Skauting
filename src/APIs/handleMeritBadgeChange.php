<?php

require_once dirname(__DIR__) . '/scripts/connector.php';
require_once dirname(__DIR__) . '/scripts/Utilities/Functions.php';
require_once dirname(__DIR__) . '/scripts/MeritBadge/Service/MeritBadgeService.php';

use Utility\Functions as Functions;
use MeritBadge\Service\MeritBadgeService as MeritBadgeService;

$badges = new MeritBadgeService();

// Read JSON input
$json = file_get_contents('php://input');
$input = json_decode($json, true); // Convert JSON to PHP array

$operation = $input['operation'] ?? "";
$data = $input['data'] ?? array();

$operation = Functions::sanitizeInput($operation);
$data = Functions::sanitizeArray($data);

$response = [
    'error' => false,
    'error_message' => '',
];



if ($operation == 'change' && $_SESSION['position_id'] >= 5) {

    foreach ($data as $task_id => $task) {

        if (empty($task)) {
            continue;
        }

        if (!$badges->UpdateMeritBadgeTask($task_id, $task)) {
            $response['error'] = true;
            $response['error_message'] .= "Úlohu s id: " . $task_id. " sa nepodarilo zmeniť\n";
        }

    }

}
else if ($operation == 'add' && $_SESSION['position_id'] >= 5) {

    foreach ($data as $new_task_id => $task) {

        $newTask = [
            'task' => $task['task'] ?? "",
            'level_id' => $task['level_id'] ?? "",
            'merit_badge_id' => $task['merit_badge_id'] ?? "",
        ];

        if (empty($newTask['task']) || empty($newTask['level_id']) || empty($newTask['merit_badge_id'])) {
            continue;
        }

        if (!$badges->createNewMeritBadgeTask($newTask)) {
            $response['error'] = true;
            $response['error_message'] .= "Úlohu so znením: " . $newTask['task']. ". sa nepodarilo pridať\n";
        }

    }

}
else if ($operation == 'delete' && $_SESSION['position_id'] >= 5) {

    foreach ($data as $task_id) {

        if (!$badges->deleteMeritBadgeTask($task_id)) {
            $response['error'] = true;
            $response['error_message'] .= "Úlohu s id: " . $task_id. " sa nepodarilo vymazať\n";
        }

    }

}
else {
    $response['error'] = true;
    $response['error_message'] = "Zle zadaná operácia alebo nemáte dostatočné práva";
}

echo json_encode($response);