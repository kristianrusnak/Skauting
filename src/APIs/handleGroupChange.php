<?php

require_once dirname(__DIR__) . '/scripts/connector.php';
require_once dirname(__DIR__) . '/scripts/Users/Service/UserService.php';

use User\Service\UserService as UserService;

// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true); // Convert JSON to PHP array

$response = [
    'error' => false,
    'errorMessage' => ''
];

$user = new UserService();

if ($data) {
    try
    {
        foreach ($data as $user_id => $changes) {
            if ($changes[0] == 4) {
                if (!$user->setLeader($user_id)){
                    $response['error'] = true;
                    $response['errorMessage'] = 'Something went wrong with setting leader';
                    break;
                }
            }
            else if ($changes[0] == 3) {
                if (!$user->setPatrolLeader($user_id)){
                    $response['error'] = true;
                    $response['errorMessage'] = 'Something went wrong with setting patrol leader';
                    break;
                }
            }
            else if ($changes[0] == 2) {
                $response['error'] = true;
                $response['errorMessage'] = 'Forbidden data received';
                break;
            }
            else if ($changes[0] == 1) {
                if (!$user->setScout($user_id, $changes[1])){
                    $response['error'] = true;
                    $response['errorMessage'] = 'Something went wrong with setting scout';
                }
            }
            else {
                $response['error'] = true;
                $response['errorMessage'] = 'No valid data received';
            }
        }
    }
    catch (Exception $e)
    {
        $response['error'] = true;
        $response['errorMessage'] = $e->getMessage();
    }
    catch (Error $e)
    {
        $response['error'] = true;
        $response['errorMessage'] = $e->getMessage();
    }
}
else {
    $response['error'] = true;
    $response['errorMessage'] = 'No valid data received';
}

echo json_encode($response);
?>