<?php

require_once '../scripts/connector.php';
require_once '../scripts/HtmlBuilder/HtmlBody.php';
require_once '../scripts/Utilities/SessionManager.php';
require_once '../scripts/HtmlBuilder/Containers.php';
require_once '../scripts/HtmlBuilder/DifferentTasksManager.php';
require_once '../scripts/HtmlBuilder/TaskApprovalContainer.php';

require_once '../APIs/handleDifferentTaskView.php';

use HtmlBuilder\HtmlBody as Body;
use Utility\SessionManager as Session;
use HtmlBuilder\DifferentTasksManager as DifferentTasksManager;
use HtmlBuilder\Containers as Containers;
use HtmlBuilder\TaskApprovalContainer as TaskApproval;

Session::KickIfSessionNotSet();

$containers = new Containers();
$taskApproval = new TaskApproval();

Body::printMainHeader( "domov");
DifferentTasksManager::alertHeader();
Body::printMenu();
?>
<div id="tasksContainer1" >
    <h1>Rozpracované úlohy</h1>
    <?php

        $containers->printContainerStart();
        $containers->listScoutPathsInProgress($_SESSION['user_id']);
        $containers->listMeritBadgesInProgress($_SESSION['user_id']);
        $containers->printContainerEnd();

    if ($_SESSION['position_id'] >= 3 && $_SESSION['position_id'] <= 4) {
        echo '<h1>Úlohy na schálenie</h1>';
        $taskApproval->printContainerStart();
        if ($_SESSION['position_id'] == 3) {
            $taskApproval->listUnverifiedForPatrolLeaders($_SESSION['user_id']);
        }
        else {
            $taskApproval->listUnverifiedForLeader();
        }
        $taskApproval->printContainerEnd();
        $taskApproval->printCssScript();
        $taskApproval->printScript();
    }
    ?>
    <h1>Hotové úlohy</h1>
    <?php
        $containers->printContainerStart();
        $containers->listScoutPathWithFilter($_SESSION['user_id']);
        $containers->listMeritBadgesWithFilter($_SESSION['user_id']);
        $containers->printContainerEnd();
    ?>
</div>
<?php
Body::printFooter();
?>