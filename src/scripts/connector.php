<?php

/*
 * -------
 * Classes
 * -------
 * */
session_start();
// Utility
include 'HtmlBody.php';
include 'SessionManager.php';
include 'MysqliService.php';
include 'DatabaseService.php';

// User
include 'GroupManager.php';
include 'PositionManager.php';
include 'UserManager.php';

// Tasks and user's tasks
include 'CompletedTasksManager.php';
include 'TaskManager.php';

// Merit badges
include 'MeritBadgeTaskManager.php';
include 'MeritBadgeLevelManager.php';
include 'MeritBadgeManager.php';
include 'CategoriesOfMeritBadgeManager.php';
include 'AdditionalInformationAboutMeritBadgeManager.php';

// Scout paths
include 'ScoutPathTaskManager.php';
include 'ChaptersOfScoutPathManager.php';
include 'AreaOfScoutPathManager.php';
include 'ScoutPathManager.php';
include 'RequiredPointsManager.php';

// Services
include 'UserService.php';
include 'CompletedTasksService.php';
include 'ScoutPathService.php';
include 'MeritBadgeService.php';

// HTML generators
include 'Containers.php';
include '../scripts/TasksLister.php';
include 'GroupsLister.php';
include 'DifferentTasksManager.php';
include 'TaskApprovalContainer.php';
include 'MeritBadgeTaskEditor.php';
include 'ScoutPathTaskEditor.php';

/*
 * ----------------
 * Global variables
 * ----------------
 */

// Utility
$body = new HtmlBody();
$session = new SessionManager();
$database = new DatabaseService();

// Service
$user = new UserService($database);
$completedTasks = new CompletedTasksService($database);
$scoutPaths = new ScoutPathService($database);
$meritBadges = new MeritBadgeService($database);

// HTML generators
$containers = new Containers($completedTasks, $scoutPaths, $meritBadges);
$taskLister = new TasksLister($completedTasks, $scoutPaths, $meritBadges);
$groupsLister = new GroupsLister($user);
$differentTaskView = new DifferentTasksManager();
$taskApproval = new TaskApprovalContainer($user, $completedTasks);
$meritBadgeTaskEditor = new MeritBadgeTaskEditor($meritBadges, $scoutPaths);
$scoutPathTaskEditor = new ScoutPathTaskEditor($scoutPaths);

//$cookies

/*
 * ---------
 * Functions
 * ---------
 * */

/**
 * Sanitize a string by removing potentially dangerous characters for PHP, MySQL, and other systems.
 *
 * @param string $input - The input string to sanitize.
 * @return string - The sanitized string.
 */
function sanitizeInput($input): string
{
    // Remove risky characters using regex
    $input = preg_replace('/[;\'"\\\|&`<>]/', '', $input); // Remove characters ; ' " \ | & ` < >

    // Strip PHP tags and HTML tags (prevents embedding harmful code)
    $input = strip_tags($input);

    // Trim whitespace (not necessarily a security risk, but helpful for cleaning input)
    $input = trim($input);

    // Remove null bytes (used for injection attacks)
    $input = str_replace("\0", '', $input);

    // Limit further encoding-based attacks
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    return $input;
}

/**
 * @param $id
 * @param $arrays
 * @return array
 */
function getStructuredArray($id, $arrays): array
{
    $result = array();

    $last_id = '';
    $newArray = array();
    foreach ($arrays as $array) {
        if ($last_id == ''){
            $last_id = $array[$id];
        }
        else if ($last_id != $array[$id]){
            $result += [$last_id => $newArray];
            $newArray = array();
            $last_id = $array[$id];

        }
        $newArray[] = $array;
    }

    $result += [$last_id => $newArray];
    return $result;

}

/**
 * @param $file
 * @param $uploadDir
 * @return string
 */
function uploadImage($file, $targetFilePath): String
{
    $uploadDir = "../images/";

    // Ensure the directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if a file is uploaded
    if (!isset($file)) {
        return "No file uploaded.";
    }

    if ($file["size"] > 2 * 1024 * 1024) { // 2MB limit
        return "File is too large.";
    }

    // Validate MIME type (ensure it's actually a PNG file)
    $fileType = mime_content_type($file["tmp_name"]);
    if ($fileType !== "image/png") {
        return "Invalid file type. Only PNG images are allowed.";
    }

    // Move the uploaded file to the target location
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return "File uploaded successfully.";
    } else {
        return "Error uploading file.";
    }
}

function deleteImage($filePath) {
    // Check if the file exists
    if (file_exists($filePath)) {
        // Try to delete the file
        if (unlink($filePath)) {
            return "File deleted successfully: " . basename($filePath);
        } else {
            return "Error: Could not delete the file.";
        }
    } else {
        return "Error: File does not exist.";
    }
}
?>