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
include 'TasksLister.php';
include 'GroupsLister.php';
include 'DifferentTasksManager.php';

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
?>