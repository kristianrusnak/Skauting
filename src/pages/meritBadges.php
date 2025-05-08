<?php

require_once '../scripts/connector.php';
require_once '../scripts/HtmlBuilder/HtmlBody.php';
require_once '../scripts/Utilities/SessionManager.php';
require_once '../scripts/HtmlBuilder/Containers.php';
require_once '../scripts/HtmlBuilder/DifferentTasksManager.php';
require_once '../scripts/MeritBadge/Service/MeritBadgeService.php';
require_once '../scripts/HtmlBuilder/MeritBadgeTaskEditor.php';
require_once '../scripts/htmlBuilder/TasksLister.php';

require_once '../APIs/handleDifferentTaskView.php';
require_once '../APIs/handleMeritBadgeCreateUpdate.php';

use HtmlBuilder\HtmlBody as Body;
use Utility\SessionManager as Session;
use HtmlBuilder\DifferentTasksManager as DifferentTasksManager;
use HtmlBuilder\Containers as Containers;
use MeritBadge\Service\MeritBadgeService as MeritBadge;
use HtmlBuilder\MeritBadgeTaskEditor as MeritBadgeTaskEditor;
use HtmlBuilder\TasksLister as TasksLister;

Session::KickIfSessionNotSet();

$meritBadges = new MeritBadge();
$meritBadgeTaskEditor = new MeritBadgeTaskEditor();
$taskLister = new TasksLister();
$containers = new Containers();

Body::printMainHeader( "odborky");
DifferentTasksManager::alertHeader();
Body::printMenu();


if (isset($_GET['task_id']) && isset($_GET['id'])) {

    echo '
            <h1>Podobné úlohy</h1>
            <a href="../pages/meritBadges.php?id='.$_GET['id'].'" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="spat"></a>
        ';

    $taskLister->listMatchTasks($_GET['task_id']);
    $taskLister->printScript();
}

else if (isset($_GET['id']) && $meritBadges->isMeritBadgeIdValid($_GET['id'])){

    if (isset($_GET['alter']) && $_GET['alter'] == "settings" && $_SESSION['position_id'] == 5) {
        echo '<h1>Editacia Uloh: '.$meritBadges->getMeritBadgeName($_GET['id']).'</h1>';
        echo '<a href="../pages/meritBadges.php?id='.$_GET['id'].'" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>';
        $meritBadgeTaskEditor->listTasks($_GET['id']);
        $meritBadgeTaskEditor->printScript();
    }
    else if (isset($_GET['alter']) && $_GET['alter'] == "update" && $_SESSION['position_id'] == 5) {
        echo '<h1>Editacia Odborky: '.$meritBadges->getMeritBadgeName($_GET['id']).'</h1>';
        echo '<a href="../pages/meritBadges.php?id='.$_GET['id'].'" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>';
        $meritBadgeTaskEditor->listUpdateForm($_GET['id']);
    }
    else {
        echo '<h1>'.$meritBadges->getMeritBadgeName($_GET['id']).'</h1>';

        if ($_SESSION['position_id'] == 5) {
            echo '
            <a href="../pages/meritBadges.php" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>
            <a href="../pages/meritBadges.php?id='.$_GET['id'].'&alter=delete" class="addContainer"><img class="groupsIcon" src="../images/delete.png" alt="odstranit"></a>
            <a href="../pages/meritBadges.php?id='.$_GET['id'].'&alter=settings" class="addContainer"><img class="groupsIcon" src="../images/task.png" alt="nastavenia"></a>
            <a href="../pages/meritBadges.php?id='.$_GET['id'].'&alter=update" class="addContainer"><img class="groupsIcon" src="../images/merit_badge.png" alt="update"></a>
        ';
        }
        else {
            echo '
            <a href="../pages/meritBadges.php" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>
            ';
        }

        $taskLister->listMeritBadgeTasks($_GET['id']);

        if ($_SESSION['position_id'] == 5 && isset($_GET['alter']) && $_GET['alter'] == "delete") {
            $meritBadgeTaskEditor->deleteMeritBadge($_GET['id']);
        }

        //$taskLister->printScript();
    }
}

else if (isset($_GET['alter']) && $_GET['alter'] == 'add' && $_SESSION['position_id'] == 5) {

        echo '
            <h1>Nova odborka</h1>
        ';

        echo '
            <a href="../pages/meritBadges.php" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="spat"></a>
        ';

        $meritBadgeTaskEditor->listCreateForm();

}

else{

    echo '<h1>Odborky</h1>';

    if ($_SESSION['position_id'] == 5) {
        echo '
            <a href="../pages/meritBadges.php?alter=add" class="addContainer"><img class="groupsIcon" src="../images/plus.png" alt="pridaj"></a>
        ';
    }

    $containers->printContainerStart();
    $containers->listMeritBadges();
    $containers->printContainerEnd();

}

Body::printFooter(["TasksLister"]);