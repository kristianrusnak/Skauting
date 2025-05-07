<?php

require_once dirname(__DIR__) . "/scripts/connector.php";
require_once dirname(__DIR__) . "/scripts/Utilities/Functions.php";
require_once dirname(__DIR__) . "/scripts/Tasks/Service/CommentService.php";

use Utility\Functions as Functions;
use Task\Service\CommentService as CommentService;

$data = json_decode(file_get_contents('php://input'), true);
$comment = new CommentService();

$task_id = $data['task_id'] ?? '';
$user_id = $data['user_id'] ?? '';
$comment_text = $data['comment'] ?? '';
$operation = $data['operation'] ?? '';

$task_id = Functions::sanitizeInput($task_id);
$user_id = Functions::sanitizeInput($user_id);
$comment_text = Functions::sanitizeInput($comment_text);
$operation = Functions::sanitizeInput($operation);

$response = [
    'error' => false,
    'errorMessage' => '',
];

try {
    if ($operation == "write") {
        $result = $comment->addOrUpdate($user_id, $task_id, $comment_text);
        if (!$result) {
            $response['error'] = true;
            $response['errorMessage'] = "Komentar sa nepodarilo pridat";
        }
    }
    else if ($operation == "remove") {

        $comment->remove($user_id, $task_id);
    }
    else {
        $response['error'] = true;
        $response['errorMessage'] = "Neplatna operacia";
    }
}
catch (Error|Exception $e) {
    $response['error'] = true;
    $response['errorMessage'] = "Nastala chyba: " . $e->getMessage();
}

echo json_encode($response);
?>