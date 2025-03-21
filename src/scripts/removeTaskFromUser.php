<?php
include 'connector.php';

$data = json_decode(file_get_contents('php://input'), true);

$task_id = $data['task_id'] ?? '';

$task_id = sanitizeInput($task_id);

$response = [
    'operation' => "remove",
    'error' => False,
    'errorMessage' => ''
];

try {
    if (!$completedTasks->deleteTaskFromUser($task_id, $scoutPaths, $meritBadges)) {
        $response['error'] = True;
        $response['errorMessage'] = 'There was an error while trying to process your request.';
        echo 'delete';
    }
} catch (Exception $ex) {
    $response['error'] = True;
    $response['errorMessage'] = $ex->getMessage();
}

echo json_encode($response);
?>