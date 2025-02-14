<?php
include '../scripts/connector.php';
$cookies->KickIfCookiesNotSet();
$body->printMainHeader( "Odborky");
$differentTaskView->alertHeader();
include '../scripts/handleDifferentTaskView.php';
include 'menu.php';

if (isset($_GET['id']) && $meritBadges->isMeritBadgeIdValid($_GET['id'])){
?>

    <div id="tasksContainer1" >

        <?php
        echo '<h1>'.$meritBadges->getMeritBadgeName($_GET['id']).'</h1>';
        $taskLister->listMeritBadgeTasks($_GET['id']);
        $taskLister->printScript();
        ?>

    </div>

<?php
}
else{
?>

    <div id="tasksContainer1" >
        <h1>Odborky</h1>

        <?php
        $containers->printContainerStart();
        $containers->listMeritBadges();
        $containers->printContainerEnd();
        ?>

    </div>

<?php
}
$body->printFooter();
?>