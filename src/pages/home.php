<?php
include '../scripts/connector.php';
$cookies->KickIfCookiesNotSet();
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
    ?>
</div>
<?php
$body->printFooter();
?>