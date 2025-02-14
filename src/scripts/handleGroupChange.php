<?php
include 'connector.php';

// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true); // Convert JSON to PHP array

if ($data) {
/*    print_r($data); // Debugging: Print received array*/
    foreach ($data as $user_id => $changes) {
        if ($changes[0] == 4) {
            if ($user->setLeader($user_id)){
                echo 'OK';
            }
            else {
                echo 'Something went wrong with setting leader';
            }
        }
        else if ($changes[0] == 3) {
            if ($user->setAdvisor($user_id)){
                echo 'OK';
            }
            else {
                echo 'Something went wrong with setting advisor';
            }
        }
        else if ($changes[0] == 2) {
            echo 'Forbidden data received';
        }
        else if ($changes[0] == 1) {
            if ($user->setScout($user_id, $changes[1])){
                echo 'OK';
            }
            else {
                echo 'Something went wrong with setting scout';
            }
        }
        else {
            echo 'No valid data received';
        }
    }
}
else {
    echo "No valid data received";
}
?>