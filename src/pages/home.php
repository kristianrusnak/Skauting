<?php
include '../scripts/connector.php';
$session->KickIfSessionNotSet();
$body->printMainHeader( "domov");
$differentTaskView->alertHeader();
include '../scripts/handleDifferentTaskView.php';
include 'menu.php';
?>
<div id="tasksContainer1" >
    <h1>Rozpracované úlohy</h1>
    <?php

        $containers->printContainerStart();
        $containers->listScoutPathsInProgress();
        $containers->listMeritBadgesInProgress();
        $containers->printContainerEnd();

        //include 'tasksContainer.php';
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