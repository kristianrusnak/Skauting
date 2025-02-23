<?php
include '../scripts/connector.php';
$session->KickIfSessionNotSet();
$body->printMainHeader( "odborky");
$differentTaskView->alertHeader();
include '../scripts/handleDifferentTaskView.php';
include '../scripts/handleMeritBadgeCreateUpdate.php';
include 'menu.php';

if (isset($_GET['id']) && $meritBadges->isMeritBadgeIdValid($_GET['id'])){

    if (isset($_GET['alter']) && $_GET['alter'] == "settings" && $_SESSION['position_id'] == 5) {
        $meritBadgeTaskEditor->listTasks($_GET['id']);
        $meritBadgeTaskEditor->printScript();
    }
    else if (isset($_GET['alter']) && $_GET['alter'] == "update" && $_SESSION['position_id'] == 5) {
        $meritBadgeTaskEditor->listUpdateForm($_GET['id']);
    }
    else {
        echo '<h1>'.$meritBadges->getMeritBadgeName($_GET['id']).'</h1>';

        if ($_SESSION['position_id'] == 5) {
            echo '
            <a href="../pages/meritBadges.php?id='.$_GET['id'].'&alter=delete" class="addContainer"><img class="groupsIcon" src="../images/delete.png" alt="odstranit"></a>
            <a href="../pages/meritBadges.php?id='.$_GET['id'].'&alter=settings" class="addContainer"><img class="groupsIcon" src="../images/task.png" alt="nastavenia"></a>
            <a href="../pages/meritBadges.php?id='.$_GET['id'].'&alter=update" class="addContainer"><img class="groupsIcon" src="../images/merit_badge.png" alt="update"></a>
        ';
        }

        $taskLister->listMeritBadgeTasks($_GET['id']);

        if ($_SESSION['position_id'] == 5 && isset($_GET['alter']) && $_GET['alter'] == "delete") {
            $meritBadgeTaskEditor->deleteMeritBadge($_GET['id']);
        }

        $taskLister->printScript();
    }
}

else if (isset($_GET['alter']) && $_GET['alter'] == 'add' && $_SESSION['position_id'] == 5) {
?>
    <div class="tasksLister">

    <?php
        $meritBadgeTaskEditor->listCreateForm();
    ?>
    </div>
<?php
}

else{
?>

    <h1>Odborky</h1>

    <?php
    if ($_SESSION['position_id'] == 5) {
        echo '
            <a href="../pages/meritBadges.php?alter=add" class="addContainer"><img class="groupsIcon" src="../images/plus.png" alt="pridaj"></a>
        ';
    }

    $containers->printContainerStart();
    $containers->listMeritBadges();
    $containers->printContainerEnd();
    ?>

<?php
}
$body->printFooter();
?>