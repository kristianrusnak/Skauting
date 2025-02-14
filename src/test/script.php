<?php
// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true); // Convert JSON to PHP array

if ($data) {
    print_r($data); // Debugging: Print received array
} else {
    echo "No valid data received";
}
?>
