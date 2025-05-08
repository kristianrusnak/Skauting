<?php

require_once dirname(__DIR__) . '/scripts/connector.php';
require_once dirname(__DIR__) . '/scripts/Users/Service/UserService.php';
require_once dirname(__DIR__) . '/scripts/Utilities/Functions.php';

use User\Service\UserService as UserService;
use Utility\Functions as Functions;

// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true); // Convert JSON to PHP array

$leader_id = $data['leader_id'] ?? "";
$name = $data['name'] ?? "";

$leader_id = Functions::sanitizeInput($leader_id);
$name = Functions::sanitizeInput($name);

$response = [
    'error' => false,
    'errorMessage' => ''
];

$user = new UserService();

if (!empty($leader_id) && !empty($name)) {
    try {
        $result = $user->changeGroupName($leader_id, $name);
        if (!$result) {
            $response['error'] = true;
            $response['errorMessage'] = 'Nepodarilo sa zmenit meno druziny';
        }
    }
    catch (Error|Exception $e) {
        $response['error'] = true;
        $response['errorMessage'] = $e->getMessage();
    }
}
else {
    $response['error'] = true;
    $response['errorMessage'] = 'No data received';
}

echo json_encode($response);