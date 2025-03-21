<?php
include 'connector.php';

$data = json_decode(file_get_contents('php://input'), true);

$task_id = $data['task_id'] ?? '';
$points = $data['points'] ?? '';

$task_id = sanitizeInput($task_id);
$points = sanitizeInput($points);

$response = [
    'operation' => "add",
    'error' => False,
    'errorMessage' => '',
    'is_approved' => False,
    'has_to_be_approved' => False
];

try{
    if ($completedTasks->addTaskToUser($task_id, $points, $scoutPaths, $meritBadges)){
        $response['is_approved'] = True;
    }
    else {
        $response['has_to_be_approved'] = True;
    }
} catch (Exception $ex) {
    $response['error'] = True;
    $response['errorMessage'] = $ex->getMessage();
}

echo json_encode($response);
?>