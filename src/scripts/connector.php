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
require_once 'Utilities/QueryBuilder.php';

/*
 * ----------------
 * Global variables
 * ----------------
 */

// Utility
$database = new DatabaseService();
?>