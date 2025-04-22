<?php

require_once '../scripts/connector.php';
require_once '../scripts/HtmlBuilder/HtmlBody.php';
require_once '../scripts/Utilities/SessionManager.php';
require_once '../scripts/HtmlBuilder/Containers.php';
require_once '../scripts/htmlBuilder/TasksLister.php';
require_once '../scripts/htmlBuilder/ScoutPathTaskEditor.php';
require_once '../scripts/ScoutPath/Service/ScoutPathService.php';
require_once '../scripts/HtmlBuilder/DifferentTasksManager.php';

require_once '../APIs/handleScoutPathCreateUpdate.php';
require_once '../APIs/handleDifferentTaskView.php';

use HtmlBuilder\HtmlBody as Body;
use Utility\SessionManager as Session;
use HtmlBuilder\DifferentTasksManager as DifferentTasksManager;
use HtmlBuilder\Containers as Containers;
use HtmlBuilder\TasksLister as TasksLister;
use HtmlBuilder\ScoutPathTaskEditor as ScoutPathTaskEditor;
use ScoutPath\Service\ScoutPathService as ScoutPathService;


Session::KickIfSessionNotSet();

$scoutPaths = new ScoutPathService();
$scoutPathTaskEditor = new ScoutPathTaskEditor();
$taskLister = new TasksLister();
$containers = new Containers();

Body::printMainHeader( "skautsky-chodnik");
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

else if (isset($_GET['id']) && $scoutPaths->isValidScoutPathId($_GET['id'])){

    if (isset($_GET['alter']) && $_GET['alter'] == "settings" && $_SESSION['position_id'] == 5) {
        echo '<h1>Editacia uloh: '.$scoutPaths->getNameOfScoutPath($_GET['id']).'</h1>';
        echo '<a href="../pages/scoutPath.php?id='.$_GET['id'].'" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>';
        $scoutPathTaskEditor->listTasks($_GET['id']);
    }
    else if (isset($_GET['alter']) && $_GET['alter'] == "update" && $_SESSION['position_id'] == 5) {
        echo '<h1>Editacia skautskeho chodnika: '.$scoutPaths->getNameOfScoutPath($_GET['id']).'</h1>';
        echo '<a href="../pages/scoutPath.php?id='.$_GET['id'].'" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>';
        $scoutPathTaskEditor->listUpdateForm($_GET['id']);
    }
    else {
        echo '<h1>'.$scoutPaths->getNameOfScoutPath($_GET['id']).'</h1>';

        if ($_SESSION['position_id'] == 5) {
            echo '
            <a href="../pages/scoutPath.php" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>
            <a href="../pages/scoutPath.php?id='.$_GET['id'].'&alter=delete" class="addContainer"><img class="groupsIcon" src="../images/delete.png" alt="odstranit"></a>
            <a href="../pages/scoutPath.php?id='.$_GET['id'].'&alter=settings" class="addContainer"><img class="groupsIcon" src="../images/task.png" alt="nastavenia"></a>
            <a href="../pages/scoutPath.php?id='.$_GET['id'].'&alter=update" class="addContainer"><img class="groupsIcon" src="../images/path.png" alt="nastavenia"></a>
        ';
        }
        else {
            echo '
            <a href="../pages/scoutPath.php" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>
            ';
        }

        $taskLister->listScoutPathTasks($_GET['id']);

        if ($_SESSION['position_id'] == 5 && isset($_GET['alter']) && $_GET['alter'] == "delete") {
            $scoutPathTaskEditor->deleteScoutPath($_GET['id']);
        }

        $taskLister->printScript();
    }
}


else if (isset($_GET['alter']) && $_GET['alter'] == 'add' && $_SESSION['position_id'] == 5) {
    echo '<h1>Novy skautsky chodnik</h1>';
    echo '<a href="../pages/scoutPath.php" class="addContainer"><img class="groupsIcon" src="../images/back.png" alt="odstranit"></a>';
    $scoutPathTaskEditor->listCreateForm();
}


else{
    echo '<h1>Skautský chodník</h1>';
        if ($_SESSION['position_id'] == 5) {
            echo '
                <a href="../pages/scoutPath.php?alter=add" class="addContainer"><img class="groupsIcon" src="../images/plus.png" alt="pridaj"></a>
            ';
        }

        $containers->printContainerStart();
        $containers->listScoutPaths();
        $containers->printContainerEnd();
}


Body::printFooter();
?>