<?php

require_once dirname(__DIR__) . '/scripts/HtmlBuilder/DifferentTasksManager.php';

use HtmlBuilder\DifferentTasksManager as DifferentTasksView;

$end = $_POST['endDifferentTaskView'] ?? false;
$change = $_POST['changeToUserView'] ?? false;
$user_id = $_POST['idOfUser'] ?? 0;
$name = $_POST['nameOfUser'] ?? '';

if ($end) {
    DifferentTasksView::endDifferentTaskView();
}
else if ($change && $user_id && $name) {

    DifferentTasksView::setDifferentTaskView($user_id, $name);

    if (isset($_POST['website'])) {
        header('Location: ../src' . $_POST['website']);
    }
    else {
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
    exit;
}
?>