<?php

require_once '../scripts/connector.php';
require_once '../scripts/HtmlBuilder/HtmlBody.php';
require_once '../scripts/Utilities/SessionManager.php';
require_once '../scripts/HtmlBuilder/DifferentTasksManager.php';
require_once '../scripts/HtmlBuilder/GroupsLister.php';

require_once '../APIs/handleDifferentTaskView.php';

use HtmlBuilder\HtmlBody as Body;
use Utility\SessionManager as Session;
use HtmlBuilder\DifferentTasksManager as DifferentTasksManager;
use HtmlBuilder\GroupsLister as GroupsLister;

Session::KickIfSessionNotSet();

$groupsLister = new GroupsLister();

Body::printMainHeader( "správa");
DifferentTasksManager::alertHeader();
Body::printMenu();
?>

    <div id="tasksContainer1" >
        <h1>Správa</h1>
        <?php
        if ($_SESSION['position_id'] <= 2) {
            header("Location: ../pages/home.php");
            exit();
        }

        $groupsLister->printGroupContainerStart();

        if ($_SESSION['position_id'] >= 5) {
            // TODO - list admins
        }
        if ($_SESSION['position_id'] >= 4) {
            $groupsLister->listLeaders();
            $groupsLister->listUncategorized();
            $groupsLister->listTeams();
            $groupsLister->printSubmitButton();
        }
        else if ($_SESSION['position_id'] == 3) {
            $groupsLister->listTeam($_SESSION['user_id']);
        }

        $groupsLister->printGroupContainerEnd();
        ?>
    </div>

<?php
Body::printFooter(["GroupsLister"]);
?>