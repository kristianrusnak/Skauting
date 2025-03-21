<?php
require_once '../scripts/connector.php';
$session->KickIfSessionNotSet();
$body->printMainHeader( "domov");
$differentTaskView->alertHeader();
require_once '../APIs/handleDifferentTaskView.php';
require_once 'menu.php';
?>
<div id="tasksContainer1" >
    <h1>Rozpracované úlohy</h1>
    <?php

        $containers->printContainerStart();
        $containers->listScoutPathsInProgress();
        $containers->listMeritBadgesInProgress();
        $containers->printContainerEnd();

    if ($_SESSION['position_id'] >= 3) {
        echo '<h1>Úlohy na schálenie</h1>';
        $taskApproval->printContainerStart();
        if ($_SESSION['position_id'] == 3) {
            $taskApproval->listUnverifiedOfMyTeam($_SESSION['user_id']);
        }
        else {
            $taskApproval->listUnverifiedForLeader();
        }
        $taskApproval->printContainerEnd();
        $taskApproval->printCssScript();
        $taskApproval->printScript();
    }
    ?>
</div>
<?php
$body->printFooter();
?>