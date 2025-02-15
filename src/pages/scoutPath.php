<?php
include '../scripts/connector.php';
$session->KickIfSessionNotSet();
$body->printMainHeader( "skautsky-chodnik");
$differentTaskView->alertHeader();
include '../scripts/handleDifferentTaskView.php';
include 'menu.php';

if (isset($_GET['id']) && $scoutPaths->isValidScoutPathId($_GET['id'])){
?>

    <div id="tasksContainer1" >

        <?php
        echo '<h1>'.$scoutPaths->getNameOfScoutPath($_GET['id']).'</h1>';
        $taskLister->listScoutPathTasks($_GET['id']);
        $taskLister->printScript();
        ?>

    </div>

<?php
}
else{
?>

    <div id="tasksContainer1" >
        <h1>Skautský chodník</h1>

        <?php
            $containers->printContainerStart();
            $containers->listScoutPaths();
            $containers->printContainerEnd();
        ?>

    </div>

<?php
}
$body->printFooter();
?>