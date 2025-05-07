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

$data = json_decode(file_get_contents('php://input'), true);
$completedTasks = new CompletedTasks();
$scoutPath = new ScoutPath();
$meritBadge = new MeritBadge();

$task_id = $data['task_id'] ?? '';
$points = $data['points'] ?? '';

$task_id = Functions::sanitizeInput($task_id);
$points = Functions::sanitizeInput($points);
$task = $scoutPath->getTask($task_id) ?? $meritBadge->getTask($task_id);

$response = [
    'operation' => "add",
    'error' => False,
    'errorMessage' => '',
    'is_approved' => False,
    'has_to_be_approved' => False
];

if ($task) {
    try{
        if ($completedTasks->addTaskToUser($task, $points)){
            $response['is_approved'] = True;
        }
        else {
            $response['has_to_be_approved'] = True;
        }
    } catch (Error $ex) {
        $response['error'] = True;
        $response['errorMessage'] = $ex->getMessage();
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