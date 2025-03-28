<?php

require_once dirname(__DIR__) . "/scripts/connector.php";
require_once dirname(__DIR__) . "/scripts/Utilities/Functions.php";
require_once dirname(__DIR__) . "/scripts/ScoutPath/Service/ScoutPathService.php";
require_once dirname(__DIR__) . "/scripts/Tasks/Service/CompletedTasksService.php";
require_once dirname(__DIR__) . "/scripts/MeritBadge/Service/MeritBadgeService.php";

use Utility\Functions as Functions;
use Task\Service\CompletedTasksService as CompletedTasks;
use ScoutPath\Service\ScoutPathService as ScoutPath;
use MeritBadge\Service\MeritBadgeService as MeritBadge;

require_once '../scripts/connector.php';

$data = json_decode(file_get_contents('php://input'), true);
$completedTasks = new CompletedTasks($database);
$scoutPath = new ScoutPath();
$meritBadge = new MeritBadge();

$task_id = $data['task_id'] ?? '';

$task_id = Functions::sanitizeInput($task_id);
$task = $scoutPath->getTask($task_id) ?? $meritBadge->getTask($task_id);

$response = [
    'operation' => "remove",
    'error' => False,
    'errorMessage' => ''
];

if ($task) {
    try {
        if (!$completedTasks->deleteTaskFromUser($task)) {
            $response['error'] = True;
            $response['errorMessage'] = 'There was an error while trying to process your request.';
        }
    } catch (Exception $ex) {
        $response['error'] = True;
        $response['errorMessage'] = $ex->getMessage();
    }
}
else {
    $response['error'] = True;
    $response['errorMessage'] = "Task not found";
}

echo json_encode($response);
?>