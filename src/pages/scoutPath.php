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
        if (isset($_GET['alter']) && $_GET['alter'] == "settings" && $_SESSION['position_id'] == 5) {
            $scoutPathTaskEditor->listTasks($_GET['id']);
        }
        else {
            echo '<h1>'.$scoutPaths->getNameOfScoutPath($_GET['id']).'</h1>';

            if ($_SESSION['position_id'] == 5) {
                echo '
                <a href="../pages/scoutPath.php?id='.$_GET['id'].'&alter=delete" class="addContainer"><img class="groupsIcon" src="../images/delete.png" alt="odstranit"></a>
                <a href="../pages/scoutPath.php?id='.$_GET['id'].'&alter=settings" class="addContainer"><img class="groupsIcon" src="../images/settings.png" alt="nastavenia"></a>
            ';
            }

            $taskLister->listScoutPathTasks($_GET['id']);
            $taskLister->printScript();
        }
        ?>

    </div>

<?php
}


else if (isset($_GET['alter']) && $_GET['alter'] == 'add' && $_SESSION['position_id'] == 5) {
    ?>
    <div id="tasksContainer1">

    </div>
    <?php
}


else{
?>

    <div id="tasksContainer1" >
        <h1>Skautský chodník</h1>

        <?php
            if ($_SESSION['position_id'] == 5) {
                echo '
                    <a href="../pages/scoutPath.php?alter=add" class="addContainer"><img class="groupsIcon" src="../images/plus.png" alt="pridaj"></a>
                ';
            }

            $containers->printContainerStart();
            $containers->listScoutPaths();
            $containers->printContainerEnd();
        ?>

    </div>

<?php
}
$body->printFooter();
?>