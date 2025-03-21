<?php

/*
 * -------
 * Classes
 * -------
 * */
session_start();
// Utility
require_once 'Utilities/SessionManager.php';
require_once 'Utilities/MysqliService.php';
require_once 'Utilities/DatabaseService.php';

// User
require_once 'Users/GroupManager.php';
require_once 'Users/PositionManager.php';
require_once 'Users/UserManager.php';

// Tasks and user's tasks
require_once 'Tasks/CompletedTasksManager.php';
require_once 'Tasks/TaskManager.php';
require_once 'Tasks/MatchTaskManager.php';

// Merit badges
require_once 'MeritBadges/MeritBadgeTaskManager.php';
require_once 'MeritBadges/MeritBadgeLevelManager.php';
require_once 'MeritBadges/MeritBadgeManager.php';
require_once 'MeritBadges/CategoriesOfMeritBadgeManager.php';
require_once 'MeritBadges/AdditionalInformationAboutMeritBadgeManager.php';

// Scout paths
require_once 'ScoutPaths/ScoutPathTaskManager.php';
require_once 'ScoutPaths/ChaptersOfScoutPathManager.php';
require_once 'ScoutPaths/AreaOfScoutPathManager.php';
require_once 'ScoutPaths/ScoutPathManager.php';
require_once 'ScoutPaths/RequiredPointsManager.php';

// Services
require_once 'Users/UserService.php';
require_once 'Tasks/CompletedTasksService.php';
require_once 'ScoutPaths/ScoutPathService.php';
require_once 'MeritBadges/MeritBadgeService.php';
require_once 'Tasks/MatchTaskService.php';

// HTML generators
require_once 'HtmlBuilder/HtmlBody.php';
require_once 'HtmlBuilder/Containers.php';
require_once 'HtmlBuilder/TasksLister.php';
require_once 'HtmlBuilder/GroupsLister.php';
require_once 'HtmlBuilder/DifferentTasksManager.php';
require_once 'HtmlBuilder/TaskApprovalContainer.php';
require_once 'HtmlBuilder/MeritBadgeTaskEditor.php';
require_once 'HtmlBuilder/ScoutPathTaskEditor.php';

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
$matchTasks = new MatchTaskService($database);

// HTML generators
$containers = new Containers($completedTasks, $scoutPaths, $meritBadges);
$taskLister = new TasksLister($completedTasks, $scoutPaths, $meritBadges, $matchTasks);
$groupsLister = new GroupsLister($user);
$differentTaskView = new DifferentTasksManager();
$taskApproval = new TaskApprovalContainer($user, $completedTasks);
$meritBadgeTaskEditor = new MeritBadgeTaskEditor($meritBadges);
$scoutPathTaskEditor = new ScoutPathTaskEditor($scoutPaths, $user);

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